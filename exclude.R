#filter rows
#!/usr/bin/env Rscript
args = commandArgs(trailingOnly=TRUE)
tmdir<-args[1];

data<-read.table(paste0(tmdir,"group_selected.txt"),header=T,row.names=1);
cellnumdata<-read.table(paste0(tmdir,"cellnumber_group_selected.txt"),header=T);


rowfilesize = file.info(paste0(tmdir,"remove_rownames.txt"))$size
excluded_rows<-numeric(0);
if(rowfilesize)
{
  toDroprows <- read.table(paste0(tmdir,"remove_rownames.txt"));
  toDroprows<-unname(unlist(toDroprows))
  excluded_rows<-data[ !(rownames(data) %in% toDroprows), ]
} else 
{
    excluded_rows<-data;
}

write.table(excluded_rows,file=paste0(tmdir,"AAgroup_selected.txt"),sep="\t")

#filter columns
#retaining only the name values you want to keep

colfilesize = file.info(paste0(tmdir,"remove_colnames.txt"))$size
print(colfilesize);
if(colfilesize)
{
   toDropcols <- read.table(paste0(tmdir,"remove_colnames.txt"));
   toDropcols<-unname(unlist(toDropcols))
   print(toDropcols)
   excluded_cols<-excluded_rows[, !(colnames(excluded_rows) %in% toDropcols) ]
   excludedcellnum_cols<-cellnumdata[, !(colnames(cellnumdata) %in% toDropcols) ]
   print(colnames(excluded_cols));
   write.table(excluded_cols,file=paste0(tmdir,"group_selected.txt"),sep="\t")
   write.table(excludedcellnum_cols,file=paste0(tmdir,"cellnumber_group_selected.txt"),sep="\t",row.names = FALSE)
} else
{
   write.table(excluded_rows,file=paste0(tmdir,"group_selected.txt"),sep="\t")   
}

#excluded_rows_column<- subset(excluded_rows, select = names(excluded_rows) %in% c(KEEP))

