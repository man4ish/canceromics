push <- function(vec, item) {
vec=substitute(vec)
eval.parent(parse(text = paste(vec, ' <- c(', vec, ', ', item, ')', sep = '')), n = 1)
}

insert <- function(vec, item) {
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



datawithheader<-read.table(paste0(tmpdir,"group_selected.txt"),header=TRUE,row.names=1)
#print(datawithheader)

groups<-read.table(paste0(tmpdir,"selected_group.txt"));
groups<-unname(unlist(groups))
header<- names(datawithheader);

namegroup<-numeric(0)
for(i in 1:length(header))
{  
  pos = regexpr('\\.', header[i])
  #print(pos[1]);
  if(pos[1] != -1)
  {   
    keep = substr(header[i], 1, (pos[1]-1))
    #insert(namegroup,as.character(keep));
    #print(keep)
    namegroup <- c(namegroup, keep)
  } else {
    namegroup <- c(namegroup, header[i])
    #insert(namegroup,toString(header[i]));
  }
}

namegroup<-sort(unique(namegroup));
print(unname(namegroup));
print(groups)
#print(pos[1,1:length(pos)]);


overall<-numeric(0);



biochemicalllist<-row.names(datawithheader)


print(biochemicalllist)
  
#stop("Message");
############################ Heat Map ##########################################
if(FALSE){
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
}
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
  
   print(regexp)
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
print(rownames(summary));
print(rownames(summary));
write.table(summary, file = paste0(tmpdir,"summary.txt"),sep="\t")


