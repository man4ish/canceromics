args = commandArgs(trailingOnly=TRUE)
excel.file =args[1]; 
excel.sheet=args[2]; 
tmpdir<-args[3];
excel.sheet<-sub("[\r\n]", "", excel.sheet)
cat(args[2],file=paste0(tmpdir,"selected_sheet.txt"),sep="\n");


library(lme4)
library(beeswarm)
library(purrr)

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


print(excel.file);

print(excel.sheet);

data<- readWorksheetFromFile(excel.file, sheet=excel.sheet,
                                     startRow=0, endRow=endRow, startCol=0, endCol=endCol, header=FALSE)

index<-detect_index(data[1:20,1], function(x) x != "")

data<- readWorksheetFromFile(excel.file, sheet=excel.sheet,
                                     startRow=index, endRow=endRow, startCol=17, endCol=endCol, header=FALSE)


header<-unname(unlist(data[1,]))
print(unique(header))
cat(unique(header),file=paste0(tmpdir,"group.txt"),sep="\n")
