library(MASS)
rm(list=ls())
args = commandArgs(trailingOnly=TRUE)
excel.file =args[1]; 
excel.sheet=args[2]; 
qqnorm='';
cellnumber='';
metabolite='';
if(args[3]=="-qqnorm")
{
   qqnorm = args[4];
} else if(args[3]=="-cn")
{
   cellnumber = args[4];
} else if(args[3]=="-mtb")
{
  metabolite=as.numeric(args[4]);
}
groupfile<-read.table(args[5],header=FALSE)
print(qqnorm);
print(cellnumber);
print(metabolite);
rexp<-paste(groupfile[,1], collapse = '|')
print(rexp)
library(lme4)
library(beeswarm)

maxNA = (108*0.2) # maximum number of NAs allowed in a metabolite vector
minp = 0.05 / 300 # only models more significant than this shall be printed
debug = 0 # switch on debugging, 1=plots and prints, 10=stop after first good hit

if (debug >= 10) {minp=1e-8}
nmodel = 1                   
nstudy = 1
nselect = 3
if (nstudy == 1) {
} else {
  stop("chosen value of nstudy not supported")
}

row1 = 16   # row of first data point in EXCEL sheet
col1 = 15   # column of first data point in EXCEL sheet

options(java.parameters = "-Xmx4g" )
library("XLConnect")

endCol = 0
endRow = 0

if (debug >=10) { # read only data for the first 10 metabolites 
  endRow = row1 + 9
} 

data<- readWorksheetFromFile(excel.file, sheet=excel.sheet,
                                     startRow=15, endRow=endRow, startCol=15, endCol=endCol, header=TRUE)
title<-colnames(data[1,]);

ndata<-ncol(data);
rt<-data[grep(rexp, names(data))]
data<-rt
ndata<-ncol(data);
data.m = as.matrix(data)
#print(rt)
header<-unname(unlist(data[1,]))
#cat(header,file="testgroup.txt",sep="\n");

print(ndata);



celldata<-readWorksheetFromFile(excel.file, sheet=excel.sheet, startRow=7, endRow=7, startCol=15, endCol=endCol, header=FALSE)
ncell<-ncol(celldata)

for (i in 1:(ndata-ncell))
{
   celldata<-cbind(celldata,1);
}
v1=c(unlist(unname(celldata)))

#print(title)
#print(v1)

cellnumdata<-setNames(celldata, title)
ct<-data[grep(rexp, names(cellnumdata))]
print(ct);


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

if(metabolite !='')
{
  metabodata<-readWorksheetFromFile(excel.file, sheet=excel.sheet, 
                                     startRow=metabolite, endRow=metabolite, startCol=15, endCol=endCol, header=FALSE)
  #print(metabodata);
  metabolnumber<-(unname(unlist(metabodata)));
  print(metabolnumber);
  print(length(metabolnumber))
  metanorm<-t(t(data.m)/metabolnumber);
  metanorm.f<-rbind2(metanorm[1:(metabolite-15-1),],metanorm[(metabolite-15+1):nrow(metanorm),])
  print(metanorm[27,])
  write.matrix(metanorm.f, file = "metabolite_matrix.txt", sep = " ")
  #metanorm<-data.m/metabolnumber;
  #stop("Message")
}

library(limma)
if(qqnorm!='')
{
    
    #print(data.m)
    qq.data = (normalizeQuantiles(data.m))
    write.matrix(qq.data, file = "qqnorm.txt", sep = " ")
    #stop("Message")
}










