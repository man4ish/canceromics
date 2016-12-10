#7.03.16

# clean up
rm(list=ls())
########################################################################
# print model summary to a file
# - use with print.header = TRUE to print a header
# - use lead = "" to put additional data in front of the model, 
#   such as variable ids
########################################################################
print_summary = function (s, outfile = outfile, print.header = FALSE, lead = "") {
  coef = s$coefficients
  if (print.header) { # print the headers
    cat("print_summary printing to", outfile, "\n")
    cat(file = outfile, append = FALSE, lead)
    for (i in dimnames(coef)[[1]]) {
      for (j in dimnames(coef)[[2]]) {
        cat(file = outfile, append = TRUE, "\t",paste(j, "(", i, ")", sep=""))
      }
    }
  } else { # print the data}
    cat(file = outfile, append = TRUE, lead)
    for (i in dimnames(coef)[[1]]) {
      for (j in dimnames(coef)[[2]]) {
        cat(file = outfile, append = TRUE, "\t",coef[i,j])
      }
    }
  }
  cat(file = outfile, append = TRUE, "\n")
  return ()
}

########################################################################


#install.packages("lme4")

library(lme4)
library(limma)
library(beeswarm)


sink(file="Control_HEPES.Normalized_Medium&Cell.txt", append = FALSE, split=TRUE)


# open a PDF file
pdffile = "Control_HEPES.NormalizedMedium&Cell.pdf"
cat ("-> output to", pdffile, "\n")
try(dev.off(), silent = TRUE) # close the previous file, if open
pdf(pdffile, height = 11.69, width = 8.27, paper = "a4")
# make some plots
par(mfcol = c(3,1))


load(file ="data.rda")

# parameters
maxNA = (108*0.2) # maximum number of NAs allowed in a metabolite vector
minp = 0.05 / 660 # only models more significant than this shall be printed
debug = 0 # switch on debugging, 1=plots and prints, 10=stop after first good hit

if (debug >= 10) {minp=1e-8}

minp=99
######
# this switch selects the model that needs to be evaluated
#                         model 1: default
nmodel = 1
#
# ----------
#
#select a subset of datasets
#0 : take all
#1 : only untreated
#2 : only treated
#3 : all wo blank
#4 : all wo blank and wo control
#5 : only untreated and blank

nselect = 1

#selection of data subset

treatment = unname(as.character(as.vector(phenodata[13,])))
lvalid = array(data = TRUE, dim=c(length(treatment)))
if (nselect == 0) {#take all data
  cselect = "using all records"
  
}else if (nselect == 1) { # take only untreated
    cselect = "using only records for untreated cells"
    lvalid[which(treatment == "treated")]= FALSE
    lvalid[which(treatment == "DMSO")] = FALSE
    lvalid[which(treatment == "Blank")] = FALSE
} else if (nselect == 2) { # take only treated
      cselect = "using only records for untreated cells"
      lvalid[which(treatment == "Untreated")]= FALSE
      lvalid[which(treatment == "DMSO")] = FALSE
      lvalid[which(treatment == "Blank")] = FALSE
} else if (nselect == 3) { # take cells wo blank
  cselect = "using cells wo blank"
  lvalid[which(treatment == "Blank")] = FALSE
} else if (nselect == 4) { # take cells wo blank and wo control
  cselect = "using cells wo blank"
  lvalid[which(treatment == "Blank")] = FALSE
  lvalid[which(DOSE == "1")] = FALSE
} else if (nselect == 5) { # take only untreated and blank
  cselect = "only untreated and blank"
  lvalid[which(treatment == "DMSO")] = FALSE
  lvalid[which(treatment == "treated")] = FALSE
} else {
        stop("nselect option not implemented\n")
}

cat(cselect, "\n")

#create data and phenotype for the selected conditions
data = data[, lvalid]
phenodata = phenodata[, lvalid]
#define cell number
c.n = phenodata[7,]
c.n = as.vector(t(c.n))
#define cell number factor == original cell number/1xE05
c.f = phenodata[8,]
c.factor = as.vector(t(c.f))

#reformat and structure data
TYPE = as.factor(as.character(phenodata[5,]))
str(TYPE)
DOSE = as.factor(as.character(phenodata[9,]))
str(DOSE)
TIMEPOINT = as.factor(as.character(phenodata[11,]))
str(TIMEPOINT)
TIME = as.numeric(sub('h', '',phenodata[11,]))
str(TIME)
print(table(TYPE))
print(table(TIME))
print(table(DOSE))
print(table(TIMEPOINT))
metabolites = as.vector(t(biochemical[,2]))

################################Data matrix############
data.m = as.matrix(data)
##############Normalization options###############

doc.0 = (cbind(biochemical,log10(data.m)))
write.csv(doc.0, "NotNormalized_CellExtract.txt")
###QQNormalization######

#boxplot(log10(data.m), main = "Cell extract no normalization", xlab = "Sample", ylab = "Log10 Metabolite" )

#qq.data = (normalizeQuantiles(data.m))
#boxplot(log10(qq.data), main = "Cell extract after QQ normalization", xlab = "Sample", ylab = "Log10 Metabolite" )

#doc.1 = (cbind(biochemical,log10(qq.data)))
#write.csv(doc.1,"QQNormalized_CellExtract.txt")

##Cell number normalization##

C.norm.data.m=matrix(data=0,nrow=dim(data.m)[1],ncol=dim(data.m)[2])
for (i in 1:dim(data)[2])
{
  C.norm.data.m[,i]=(data.m[,i])/as.numeric(c.f)[i]
}

boxplot(log10(C.norm.data.m), main = "Cell extract after normalization on cell number", xlab = "Sample", ylab = "Log10 Metabolite")

doc.2 = (cbind(biochemical,log10(C.norm.data.m)))
write.csv(doc.2,"CellNumberNorm_CellExtract.txt")

# QQ normalization on cell number normalized data

#qq.C.norm = (normalizeQuantiles(C.norm.data.m))
#boxplot(log10(qq.C.norm), main = "Cell extract after cell number and QQ normalization", xlab = "Sample", ylab = "Log10 Metabolite" )

#doc.3 = (cbind(biochemical,log10(qq.C.norm)))
#write.csv(doc.3,"CellNumberandQQNorm_CellExtract.txt")         

#HEPES normalization

#ind = grep("HEPES [cell]", metabolites, fixed=TRUE)
#HEPES = as.vector(t(data[ind,]))
#str(HEPES)

#H.norm = matrix(data=0,nrow=dim(data.m)[1],ncol=dim(data.m)[2])
#for (i in 1:dim(data)[2])
#{
 # H.norm[,i]=(data.m[,i])/as.numeric(HEPES)[i]
#}

#boxplot(log10(H.norm), main = "Cell extract after normalization on HEPES", xlab = "Sample", ylab = "Log10 Metabolite")

#doc.3 = (cbind(biochemical,log10(H.norm)))
#write.csv(doc.2,"HEPESNorm_CellExtract.txt")
## define metabolites and metabolite name

metname = as.character(t(biochemical[,2]))

met = as.matrix(t(data))
nexp = dim(met)[1]
cat(nexp, "experiments\n")
nmet = dim(met)[2]
cat(nmet, "metabolites\n")

#qq.met = as.matrix(t(qq.data))
#qq.nexp = dim(qq.met)[1]
#cat(nexp, "experiments\n")
#qq.nmet = dim(qq.met)[2]
#cat(qq.nmet, "metabolites\n")

c.n.met = as.matrix(t(C.norm.data.m))
c.n.nexp = dim(c.n.met)[1]
cat(nexp, "experiments\n")         
c.n.nmet = dim(c.n.met)[2]
cat(c.n.nmet, "metabolites\n")

#qq.c.n.met = as.matrix(t(qq.C.norm))
#qq.c.n.nexp = dim(qq.c.n.met)[1]
#cat(nexp, "experiments\n")         
#qq.c.n.nmet = dim(qq.c.n.met)[2]
#cat(qq.c.n.met, "metabolites\n")


#H.met = as.matrix(t(H.norm))
#H.nexp = dim(H.met)[1]
#cat(nexp, "experiments\n")         
#H.n.met = dim(H.met)[2]
#cat(H.n.met, "metabolites\n")

# loop over all metabolites; z-score and only keep metabolites with <= maxNA
lfirst = 1
k = 0
nhit = 0
for (i in c(1:nmet)) 
{
  # logscale
  MET = log10(met[,i])
  s = summary(MET)
  nNA = s[7]
  if (is.na(nNA)) {nNA=0}
  if (nNA<= maxNA) 
  { 
    # only proceed with variables that have little NAs
    k = k + 1
    # z-scoring by cell line    
    ZMET = array(data=NA,dim=length(MET))
    for (j in c(1:length(MET))) 
    {
      m = mean(MET,na.rm=TRUE)
      s = sd(MET,na.rm=TRUE)
      ZMET[j] = (MET[j]- m) / s  
    } 
    
    # limit data to a subset
    
    # TIMEPOINT[which((TIMEPOINT != '0h') & (TIMEPOINT != '48h'))]  = NA
    # uncomment some of the below to eliminate some toime points
   
    #ZMET[which((TIMEPOINT == '0h') )]  = NA
    #ZMET[which((TIMEPOINT == '10h') )]  = NA
    #ZMET[which((TIMEPOINT == '24h') )]  = NA
    #ZMET[which((TIMEPOINT == '48h') )]  = NA
    #ZMET[which((TIMEPOINT == '72h') )]  = NA
    
    # model the data
    # model the data
    
    nDOSE = as.numeric(DOSE)
    
#    fm1 = lm(ZMET ~ 1 + TIME)
#    fm2 = lm(ZMET ~ 1 + TIME+nDOSE)
#    a12 = anova(fm1,fm2)
#    p = a13$'Pr(>F)'[2]

    #fm3 = lm(ZMET ~ 1 + TIME + TIME*nDOSE)
    fm3 = lm(ZMET ~ 1 + TIME)
    s3 = summary(fm3)
    #p = s3$coef["TIME:nDOSE","Pr(>|t|)"]
    p = s3$coef["TIME","Pr(>|t|)"]

    # output all statistics for the three models
    if (lfirst == 1) { # on first pass open file and write header}  
      lfirst = 0
      lead=paste("i", "metname", sep="\t")
      print_summary(s3, lead = lead, print.header = TRUE, outfile = "outfile_s1.tsv")
    }
    lead=paste(i, metname [i], sep="\t")
    print_summary(s3, lead = lead, outfile = "outfile_s1.tsv")
    
        
    # report significant hits
    if (p < minp) { # print only significant model
      nhit = nhit + 1
      
      cat("##########################################################################\n")
      cat("hit", nhit, "metabolite", i, ":", metname [i], ", p =", p, "\n")
      cat("##########################################################################\n")
      
      if (debug >= 1) 
      { # debug mode
        cat("=========== fm2 ==============\n")
        print(fm2)
        cat("=========== summary ==============\n")
        print(summary(fm2))
        cat("=========== anova ==============\n")
        print(a12)
        cat("=========== coef ==============\n")
        print(coef(fm2))
        cat("=========== summary(fitted) ==============\n")
        print(summary(fitted(fm1)))
        print(summary(fitted(fm2)))
        cat("=========== residuals(fitted) ==============\n")
        print(summary(residuals(fm1)))
        print(summary(residuals(fm2)))
      }
      
      #cc = c("gray94","darkgrey","olivedrab2","olivedrab4", "black")
      cc = c("gray94")
      #cc = c("darkgrey","olivedrab2","olivedrab4")

     if(length(levels(DOSE))==2)    
      {cc =c("black", "gray94","gray94","gray94","gray94") } ## cc[1:length(levels(DOSE))]
            
      sub=paste("hit", nhit, "metabolite", i, ":", metname [i], ", p =", format(p, digits=2), sep=" ")
      
      #boxplot(ZMET ~ 1 + DOSE + TIME, 
              #col = cc, outline = FALSE,## avoid double-plotting outliers, if any
              #main = metname[i], xlab="Time [h]", ylab="Log 10 metabolite", sub = sub)
      #beeswarm(ZMET ~ 1 + DOSE + TIME, 
      #pch = 21, bg = cc, add = TRUE)

      boxplot(ZMET ~ 1 + TIME, 
        col = cc, outline = FALSE,## avoid double-plotting outliers, if any
        main = metname[i], xlab="TIME", ylab="Log 10 metabolite", sub = sub)
      beeswarm(ZMET ~ 1 + TIME, 
      pch = 21, bg = cc, add = TRUE)
      
     
      
      #if (i == 9) {stop()}
      
      
    }
  }
}

dev.off()
sink()
