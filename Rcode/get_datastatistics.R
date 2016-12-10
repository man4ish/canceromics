args = commandArgs(trailingOnly=TRUE)
excel.file =args[1]; 
excel.sheet=args[2]; 
excel.sheet<-sub("[\r\n]", "", excel.sheet)

#cat(args[2],file="group3.txt",sep="\n");


push <- function(vec, item) {
vec=substitute(vec)
eval.parent(parse(text = paste(vec, ' <- c(', vec, ', ', item, ')', sep = '')), n = 1)
}


getnacount<-function(selectedgroup)
{  
   narray<-numeric(0);
   #print(selectedgroup)
   for (i in 1:nrow(selectedgroup))
   {
     push(narray,sum(is.na(selectedgroup[i,])))
   }
   return(narray);
}

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
                                     startRow=15, endRow=endRow, startCol=17, endCol=endCol, header=FALSE)

header<-unname(unlist(data[1,]))
groups<-unique(header)


datawithheader<- readWorksheetFromFile(excel.file, sheet=excel.sheet,
                                     startRow=15, endRow=endRow, startCol=17, endCol=endCol, header=TRUE)
overall<-numeric(0);

   biochemical <- readWorksheetFromFile(excel.file, sheet=excel.sheet,
                                     startRow=row1, endRow=endRow, startCol=2, endCol=2, header=FALSE)

  biochemicalllist<-unname(unlist(biochemical))

  print(biochemicalllist)
  

for (i in 2:nrow(data))
{
  push(overall,sum(is.na(data[i,])))
}

summary <- data.frame(Total=overall)
print(data[1,])
print(nrow(data))


print(overall);
print(groups);

for (c in 1:length(groups))
{
   print(groups[c]);
   regexp<-groups[c];
  
   selectedgroup<-datawithheader[grep(regexp, names(datawithheader))]
   
   #print(selectedgroup)
   #print(nrow(selectedgroup));
   
   selectedna<-getnacount(selectedgroup);
   summary<-cbind(summary,regexp=selectedna)
   #print(selectedna);
}

colnames(summary) <- c("Total", groups)
rownames(summary) <- biochemicalllist
write.table(summary, file = "summary.txt",sep="\t")

cat(unique(header),file="group.txt",sep="\n")
