
rm(list=ls())
args = commandArgs(trailingOnly=TRUE)
excel.file =args[1]; 
excel.sheet=args[2]; 

groupfile<-read.table(args[3],header=FALSE)
tmpdir<-args[4];
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
selected_data<-data[grep(rexp, names(data))]
biochemical <- readWorksheetFromFile(excel.file, sheet=excel.sheet,
                                     startRow=row1, endRow=endRow, startCol=2, endCol=2, header=FALSE)

biochemicalllist<-unname(unlist(biochemical))
ndata<-ncol(data);
write.table(log10(selected_data), file = paste0(tmpdir,"group_selected.txt"),row.names=biochemicalllist,sep="\t");
#selected_data[is.na(selected_data)] <- 1
#write.table(cbind(biochemicalllist,log10(selected_data)), file = paste0(tmpdir,"group_selected.txt"),row.names=FALSE,sep="\t");




celldata<-readWorksheetFromFile(excel.file, sheet=excel.sheet, startRow=7, endRow=7, startCol=15, endCol=endCol, header=FALSE)
ncell<-ncol(celldata)

for (i in 1:(ndata-ncell))
{
   celldata<-cbind(celldata,1);
}

cellnumdata<-setNames(celldata, title)
selected_celldata<-cellnumdata[grep(rexp, names(cellnumdata))]

write.table(selected_celldata, file = paste0(tmpdir,"cellnumber_group_selected.txt"))










