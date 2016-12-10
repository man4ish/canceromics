library(MASS)
require(graphics);
require(grDevices)

rm(list=ls())
args = commandArgs(trailingOnly=TRUE)
excel.file =args[1]; 
excel.sheet=args[2]; 
groupfile<-read.table(args[3],header=FALSE)
qqnorm='';
cellnumber='';
metabolitename='';
if(args[4]=="-qqnorm")
{
   qqnorm = 1;
} else if(args[4]=="-cn")
{
   cellnumber = 1;
} else if(args[4]=="-mtb")
{
  #metabolite=as.numeric(args[4]);
  metabolitename=args[5];
  metabolitename<-gsub("[\r\n]", "", metabolitename)
}

print(qqnorm);
print(cellnumber);
print(metabolitename);
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
titledata<-colnames(data[1,]);

ndata<-ncol(data);
rt<-data[grep(rexp, names(data))]

rawdata<-rt
biochemical <- readWorksheetFromFile(excel.file, sheet=excel.sheet,
                                     startRow=row1, endRow=endRow, startCol=2, endCol=2, header=FALSE)

rawdata[is.na(rawdata)] <- 0

#rawdata<-cbind(biochemical,rawdata);

print(biochemical)
print(nrow(rawdata));
htdata<-data.matrix(rawdata);
rownames(htdata) <- unlist(biochemical)

print(htdata);
png("data.png")
heatmap(htdata, Rowv = NA, Colv = NA, scale = "column",cex.axis=0.05)
dev.off()
write.matrix(rt, file = "group_selected_data.txt", sep = " ")
data<-rt
title<-colnames(data[1,]);
ndata<-ncol(data);
data.m = as.matrix(data)
#print(rt)
header<-unname(unlist(data[1,]))
#cat(title,file="testgroup.txt",sep="\n");

#print(ndata);



celldata<-readWorksheetFromFile(excel.file, sheet=excel.sheet, startRow=7, endRow=7, startCol=15, endCol=endCol, header=FALSE)
ncell<-ncol(celldata)

#for (i in 1:(ndata-ncell))
#{
#   celldata<-cbind(celldata,1);
#}
#v1=c(unlist(unname(celldata)))
#title<-colnames(celldata[1,]);


#print(v1)

cellnumdata<-setNames(celldata, title)
ct<-data[grep(rexp, names(cellnumdata))]
#print(ct[1,])
#print(ncol(ct))
#stop("data done")
#print(ct);
library(limma)

if(cellnumber !='')
{

   colnumber<-(unname(unlist(ct[1,])));
   print(length(colnumber));                                                 # number of elements in cell number are lesser than data matrix
   cellnorm<-t(t(data.m)/as.numeric(colnumber));
   write.matrix(cellnorm, file = "cellnumber.txt", sep = " ")
   #stop("Message")
   
} else if(metabolitename !='')
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
  metabolondata<-setNames(metabodata, titledata)
  print(metabolondata)
  mt<-metabolondata[grep(rexp, names(metabolondata))]
  print(mt[1,]);
  #stop("Message")
  metabolnumber<-(unname(unlist(mt[1,])));
  print(metabolnumber);
  print(length(metabolnumber))
  metanorm<-t(t(data.m)/metabolnumber);
  metanorm.f<-rbind2(metanorm[1:(metabolite-15-1),],metanorm[(metabolite-15+1):nrow(metanorm),])
  print(metanorm[27,])
  write.matrix(metanorm.f, file = "metabolite_matrix.txt", sep = " ")
  #metanorm<-data.m/metabolnumber;
  #stop("Message")
} else if(qqnorm!='')
{
    
    #print(data.m)
    qq.data = (normalizeQuantiles(data.m))
    write.matrix(qq.data, file = "qqnorm.txt", sep = " ")
    #stop("Message")
} else 
{
   write.matrix(data.m, file = "nonorm.txt", sep = " ")
}










