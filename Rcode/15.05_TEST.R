# read 231 cancer data treated with glutaminase inhibitor
# AH, 7. January 2015
#

# clean up
rm(list=ls())

####If the mix model will be used the lme4 packages has to be install

#install.packages("lme4")

args = commandArgs(trailingOnly=TRUE)


excel.file =args[1]; 
excel.sheet=args[2]; 



library(lme4)

library(beeswarm)

#instalation of packages

#install.packages("proto")
#install.packages("ggplot2")
#install.packages("ggfortify")
#library("ggfortify")

# parameters
maxNA = (108*0.2) # maximum number of NAs allowed in a metabolite vector
minp = 0.05 / 300 # only models more significant than this shall be printed
debug = 0 # switch on debugging, 1=plots and prints, 10=stop after first good hit

if (debug >= 10) {minp=1e-8}

######
# this switch selects the model that needs to be evaluated
#                         model 1: default
nmodel = 1
#
# switch between studies 1: WCQA-01-11VW+
#                       
nstudy = 1
#
# ----------
#
#select a subset of datasets
                  #0 : take all
                  #1 : only untreated
                  #2 : only treated
                  #3 : all wo blank

nselect = 3




####
# define here the excel file and sheet name
if (nstudy == 1) {
  #excel.file  = file.path("origdata_c.n..xlsx")
  #excel.sheet = "OrigScale (media)" #for media
  #excel.sheet = "OrigScale (cell extract)"#for cells
} else {
  stop("chosen value of nstudy not supported")
}


# set the position of the first cell containing metabolomics data
row1 = 16   # row of first data point in EXCEL sheet
col1 = 15   # column of first data point in EXCEL sheet
#
#
# define the row and column of first data point in the Excel sheet
#
#
#
outfile = paste("outfile",nstudy,".tsv", sep="")       # output file for assiciation data
exportfile = paste("outfile",nstudy,".Rdata", sep="")  # output file for storage of metabolite data


#####
# start JAVA
# increase memory to avoid error (need to restart R) --> Error: OutOfMemoryError (Java): GC overhead limit exceeded
options(java.parameters = "-Xmx4g" )
library("XLConnect")
#
endCol = 0
endRow = 0
#
# read the data
if (debug >=10) { # read only data for the first 10 metabolites 
  endRow = row1 + 9
} 
#
data        <- readWorksheetFromFile(excel.file, sheet=excel.sheet, 
                                     startRow=row1, endRow=endRow, startCol=col1, endCol=endCol, header=FALSE)
str(data)


data        <- readWorksheetFromFile(excel.file, sheet=excel.sheet,
                                     startRow=15, endRow=endRow, startCol=16, endCol=endCol, header=FALSE)
str(data)


header<-unname(unlist(data[1,]))

#print(unique(header));

#stop("Message")

#
# read the information on biochemicals
biochemical <- readWorksheetFromFile(excel.file, sheet=excel.sheet, 
                                     startRow=row1, endRow=endRow, startCol=2, endCol=2, header=FALSE)
str(biochemical)
print(biochemical)
stop("Message")
#
# read the header for the information on biochemicals
bioheader <- readWorksheetFromFile(excel.file, sheet=excel.sheet, 
                                   startRow=row1-1, endRow=row1-1, startCol=1, endCol=col1-1, header=FALSE)
str(bioheader)

#
# read the available phenotype data
phenodata   <- readWorksheetFromFile(excel.file, sheet=excel.sheet, 
                                     startRow=1, endRow=row1-1, startCol=col1, endCol=endCol, header=FALSE)
str(phenodata)
#
# read the labels for the phenotype data
phenoheader <- readWorksheetFromFile(excel.file, sheet="OrigScale (media)", 
                                     startRow=1, endRow=row1-1, startCol=col1-1, endCol=col1-1, header=FALSE)
str(phenoheader)
#

#selection of data subset

treatment = unname(as.character(as.vector(phenodata[13,])))
lvalid = array(data = TRUE, dim=c(length(treatment)))
if (nselect == 0) {#take all data
cselect = "using all records"
 
}else 
  if (nselect == 1) { # take only untreated
cselect = "using only records for untreated cells"
lvalid[which(treatment == "treated")]= FALSE
lvalid[which(treatment == "DMSO")] = FALSE
lvalid[which(treatment == "Blank")] = FALSE
} else 
  if (nselect == 2) { # take only treated
    cselect = "using only records for untreated cells"
    lvalid[which(treatment == "Untreated")]= FALSE
    lvalid[which(treatment == "DMSO")] = FALSE
    lvalid[which(treatment == "Blank")] = FALSE
  } else 
    if (nselect == 3) { # take cells wo blank
      cselect = "using cells wo blank"
      lvalid[which(treatment == "Blank")] = FALSE
      } else {
  stop("nselect option not implemented\n")
}

cat(cselect, "\n")

#create data and phenotype for the selected conditions
data = data[, lvalid]
phenodata = phenodata[, lvalid]
#define cell number
c.n = phenodata[6,]
c.n = as.vector(t(c.n))
#define cell number factor == original cell number/1xE05
c.f = phenodata[7,]
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
metabolites = as.vector(t(biochemical[,2]))
################################Data matrix############

data.m = as.matrix(data)

##############Normalization options###############

#doc.0 = (cbind(biochemical,log10(data.m)))
#write.csv(doc.0, "NotNormalized_CellExtract.txt")

###QQNormalization######


boxplot(log10(data.m), main = "Cell extract no normalization", xlab = "Sample", ylab = "Log10 Metabolite" )

#library(limma)
#qq.data = (normalizeQuantiles(data.m))
#boxplot(log10(qq.data), main = "Cell extract after QQ normalization", xlab = "Sample", ylab = "Log10 Metabolite" )

#doc.1 = (cbind(biochemical,log10(qq.data)))
        # write.csv(doc.1,"QQNormalized_CellExtract.txt")
         
##Cell number normalization##
         
         C.norm.data.m=matrix(data=0,nrow=dim(data.m)[1],ncol=dim(data.m)[2])
         for (i in 1:dim(data)[2])
         {
           C.norm.data.m[,i]=(data.m[,i])/as.numeric(c.f)[i]
         }
         
         boxplot(log10(C.norm.data.m), main = "Cell extract after normalization on cell number", xlab = "Sample", ylab = "Log10 Metabolite")
         
         #doc.2 = (cbind(biochemical,log10(C.norm.data.m)))
         #write.csv(doc.2,"CellNumberNorm_CellExtract.txt")
         
# QQ normalization on cell number normalized data
         
         #qq.C.norm = (normalizeQuantiles(C.norm.data.m))
         #boxplot(log10(qq.C.norm), main = "Cell extract after cell number and QQ normalization", xlab = "Sample", ylab = "Log10 Metabolite" )
         
         #doc.3 = (cbind(biochemical,log10(qq.C.norm)))
         #write.csv(doc.3,"CellNumberandQQNorm_CellExtract.txt")         
         

## define metabolites and metabolite name

metname = as.character(t(biochemical[,2]))

met = as.matrix(t(data))
nexp = dim(met)[1]
cat(nexp, "experiments\n")
nmet = dim(met)[2]
cat(nmet, "metabolites\n")
         
#qq.met = as.matrix(t(qq.data))
#nexp = dim(qq.met)[1]
#cat(nexp, "experiments\n")
#qq.nmet = dim(qq.met)[2]
#cat(qq.nmet, "metabolites\n")
         
c.n.met = as.matrix(t(C.norm.data.m))
nexp = dim(c.n.met)[1]
cat(nexp, "experiments\n")         
c.n.nmet = dim(c.n.met)[2]
cat(c.n.nmet, "metabolites\n")

#qq.c.n.met = as.matrix(t(qq.C.norm))
#nexp = dim(qq.c.n.met)[1]
#cat(nexp, "experiments\n")         
#qq.c.n.nmet = dim(qq.c.n.met)[2]
#cat(qq.c.n.met, "metabolites\n")



#####Just to see the data#####

#boxplot(data.m [1,] ~ DOSE * TIME)
#boxplot(qq.data [1,] ~ DOSE * TIME)
#boxplot(C.norm.data.m [1,] ~ DOSE * TIME)
#boxplot(qq.C.norm [1,] ~ DOSE * TIME)

#pdf("qq.C.norm.pdf")

#for (i in 1:dim(qq.C.norm))
#  boxplot(qq.C.norm [i,] ~ 1 + DOSE + TIME, main = metname[i])
#dev.off()


########correlation#####

##ind = grep("folate", metname, fixed=TRUE)
##folate = as.vector(t(data[ind,]))
##str(folate)
##
##lm(c.n.2 ~ folate)

#sink(file="ania.txt", append = FALSE)

#for (i in c(1:length(metabolites))) 
#{
#label = metname[i]
#met.2 = as.vector(t(data[i,]))

#if (!is.na(sd(met.2, na.rm = TRUE))) {
#  m1 = lm(c.n.con ~   met.2)
  #m1 = lm(c.n.con ~   met.2 + TIME)
#  sum1 = summary(m1)
#  if (dim(sum1$coefficients)[1] == 2) {
    #if (dim(sum1$coefficients)[1] == 3) {
#    p = sum1$coefficients[2,4]
    #p = sum1$coefficients[3,4]
#    if (is.finite(p)) {
#      if (p < 0.05/368) {
#        cat(label, "\n")
#        print(sum1)
#      }      
#    }
#  }
#}
#}



sink(file="TEST.txt", append = FALSE)
pdf("TEST.pdf")


# loop over all metabolites; z-score and only keep metabolites with <= maxNA
# loop over all metabolites; z-score and only keep metabolites with <= maxNA
k = 0
nhit = 0
for (i in c(1:nmet)) 
  #for (i in c(1:c.n.nmet)) # for cell number normalized
  {
  #for (i in c(415:415)) {
  # logscale
  MET = log10(met[,i])
  # MET = log10(c.n.met[,i]) # for cell number normalized
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
    
    fm1 = lm(ZMET ~ 1 + TIME)
    fm2 = lm(ZMET ~ 1 + TIME + as.numeric(DOSE))
    a12 = anova(fm1,fm2)
    p = a12$'Pr(>F)'[2]
    
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
          
           cc = c("gray94","darkgrey","olivedrab2","olivedrab4")
           boxplot(ZMET ~ 1 + DOSE + TIME, 
                   col = cc, outline = FALSE,## avoid double-plotting outliers, if any
                   main = metname[i], xlab="Time [h]", ylab="Log 10 metabolite")
           beeswarm(ZMET ~ 1 + DOSE + TIME, 
                    pch = 21, bg = cc, add = TRUE)
          
           
         }
       }
    }
 
dev.off()
        sink()
      
