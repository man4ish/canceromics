rm(list=ls())



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


biochemical <- readWorksheetFromFile(excel.file, sheet=excel.sheet, 
                                     startRow=row1, endRow=endRow, startCol=2, endCol=2, header=FALSE)

cat(unname(unlist(biochemical)),sep="\n",file="metabolite.txt")


      
