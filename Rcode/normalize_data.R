# read 231 cancer data treated with glutaminase inhibitor
# AH, 7. January 2015
#

# clean up




rm(list=ls())

library(MASS)
library(lme4)
library(beeswarm)
library(limma)
####If the mix model will be used the lme4 packages has to be install

#install.packages("lme4")

args = commandArgs(trailingOnly=TRUE)


excel.file =args[1]; 
excel.sheet=args[2]; 
qqnorm='';
cellnumber='';
metabolite='';
metabolitename<-'';
if(args[3]=="-qqnorm")
{
   qqnorm = args[4];
} else if(args[3]=="-cn")
{
   cellnumber = args[4];
} else if(args[3]=="-mtb")
{
  #metabolite=as.numeric(args[4]);
  metabolitename=args[4];
  
}

print(qqnorm);
print(cellnumber);
print(metabolitename);




createjsonfile<-function(data,filename,normalized)
{
     if(normalized)
     {
        cat(",{\"name\": \"Observations\",\"yAxis\": 0,\"data\":[",file=flname,append = TRUE,sep="\n");
     } else {
         cat("[{\"name\": \"Observations\",\"yAxis\": 1,\"data\":[",file=flname,append = TRUE,sep="\n");
     }   
     
     flname<-filename
     data[is.na(data)] <- 0
     
     for (i in 1:(nrow(data)-1))
     {
        min<-min(data[i,])
        max<-max(data[i,])
        boxparameter<-quantile(data[i,], c(.25, .5, .75));
        cat(paste0("[",min,",",boxparameter[1],",",boxparameter[2],",",boxparameter[3],",",max,"],"),file=flname,append = TRUE,sep="\n");
     }

     c<-nrow(data)
     min<-min(data[c,])
     max<-max(data[c,])
     boxparameter<-quantile(data[c,], c(.25, .5, .75));
     cat(paste0("[",min,",",boxparameter[1],",",boxparameter[2],",",boxparameter[3],",",max,"]"),file=flname,append = TRUE,sep="\n");
     cat("],\"tooltip\": {\"headerFormat\": \"<em>Experiment No {point.key}</em><br/>\"}}",file=flname,append = TRUE,sep="\n");
     if(normalized){cat("]",file=flname,append = TRUE,sep="\n"); }
     #stop("done");
}


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
#str(data)


data        <- readWorksheetFromFile(excel.file, sheet=excel.sheet,
                                     startRow=16, endRow=endRow, startCol=15, endCol=endCol, header=FALSE)
#str(data)
ndata<-ncol(data);
data.m = as.matrix(log10(data))
flname<-"data.json"
if (file.exists(flname)) file.remove(flname)
createjsonfile(data.m,flname,0);

normalizedmatrix<-data.m
if(cellnumber !='')
{
   celldata<-readWorksheetFromFile(excel.file, sheet=excel.sheet, startRow=7, endRow=7, startCol=15, endCol=endCol, header=FALSE)
   ncell<-ncol(celldata)

   for (i in 1:(ndata-ncell))
   {
     celldata<-cbind(celldata,1);
   }
   colnumber<-(unname(unlist(celldata)));
   print(length(colnumber));                                                 # number of elements in cell number are lesser than data matrix
   cellnorm<-t(t(data.m)/as.numeric(colnumber));
   write.matrix(cellnorm, file = "cellnumber.txt", sep = " ")
   #stop("Message")
}

if(metabolitename !='')
{

  biochemical <- readWorksheetFromFile(excel.file, sheet=excel.sheet,
                                     startRow=row1, endRow=endRow, startCol=2, endCol=2, header=FALSE)

  biochemicalllist<-unname(unlist(biochemical))

  print(biochemicalllist)
  #metabolitename<-"2-hydroxybutyrate/2-hydroxyisobutyrate[media]" 
  print(metabolitename);
  index<-which(biochemical==metabolitename)
  print(index);
  metabolite<-index+15


  
  
  metabodata<-readWorksheetFromFile(excel.file, sheet=excel.sheet, 
                                     startRow=metabolite, endRow=metabolite, startCol=15, endCol=endCol, header=FALSE)
  print(metabodata);
  metabolnumber<-(unname(unlist(metabodata)));
  print(metabolnumber);
  print(length(metabolnumber))
  metanorm<-t(t(data.m)/metabolnumber);
  metanorm.f<-rbind2(metanorm[1:(metabolite-15-1),],metanorm[(metabolite-15+1):nrow(metanorm),])
  print(metanorm[27,])
  write.matrix(metanorm.f, file = "metabolite_matrix.txt", sep = " ")
  #metanorm<-data.m/metabolnumber;
  
}


if(qqnorm!='')
{
    
    #print(data.m)
    qq.data = (normalizeQuantiles(data.m))
    write.matrix(qq.data, file = "qqnorm.txt", sep = " ")
    
}

createjsonfile(data.m,flname,1);


