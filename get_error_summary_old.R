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


args = commandArgs(trailingOnly=TRUE)
tmpdir =args[1]; 


if(FALSE){
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

}

datawithheader<-read.table(paste0(tmpdir,"group_selected.txt"),header=TRUE)
groups<-read.table(paste0(tmpdir,"selected_group.txt"));
groups<-unname(unlist(groups))
header<- names(datawithheader);

#print(header);

overall<-numeric(0);



  biochemicalllist<-datawithheader[,1]

  print(datawithheader[1,])
  

############################ Heat Map ##########################################
rawdata<-datawithheader[,2:ncol(datawithheader)]
rawdata[is.na(rawdata)] <- 1
htdata<-data.matrix(log10(rawdata));
rownames(htdata) <- unlist(biochemicalllist)

print(htdata);
fn <- "data.png"
if (file.exists(fn)) file.remove(fn)
png("data.png")
plot.new()
# svg("heatmap.svg",width = 7, height = 70)
#pdf("plot.pdf",width=7,height=42);

heatmap(htdata, Rowv = NA, Colv = NA, scale = "column",cex.axis=0.05)
dev.off()
################################################################################

for (i in 1:nrow(datawithheader))
{
  push(overall,sum(is.na(datawithheader[i,])))
}

summary <- data.frame(Total=overall)
print(nrow(summary))
#print(datawithheader[1,])
#print(nrow(datawithheader))


print(overall);


for (c in 1:length(groups))
{
   #print(groups[c]);
   regexp<-groups[c];
  
   selectedgroup<-datawithheader[grep(regexp, names(datawithheader))]
   #print(nrow(selectedgroup))
   #print(selectedgroup)
   #print(nrow(selectedgroup));
   
   selectedna<-getnacount(selectedgroup);
   
   summary<-cbind(summary,regexp=selectedna)
   #print(selectedna);
}



colnames(summary) <- c("Total", as.character(groups))
rownames(summary) <- biochemicalllist
write.table(summary, file = paste0(tmpdir,"summary.txt"),sep="\t")


