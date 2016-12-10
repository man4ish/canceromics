library(MASS)
library(lme4)
library(beeswarm)
library(limma)
createjsonfile<-function(data,filename,normalized,colwise)
{
     if(normalized)
     {
        cat(",{\"name\": \"Observations\",\"yAxis\": 0,\"data\":[",file=flname,append = TRUE,sep="\n");
     } else {
         cat("[{\"name\": \"Observations\",\"yAxis\": 1,\"data\":[",file=flname,append = TRUE,sep="\n");
     }   
     
     flname<-filename
     #data[is.na(data)] <- 0
     count<-numeric(0);
     if(colwise==0)
     {
       count<-nrow(data);
     } else {
       count<-ncol(data);
     }
    
     for (i in 1:(count-1))
     {
        min<-min(data[i,],na.rm = TRUE)
        max<-max(data[i,],na.rm = TRUE)
        
        boxparameter<-quantile(data[i,], c(.25, .5, .75),na.rm = TRUE);
        cat(paste0("[",min,",",boxparameter[1],",",boxparameter[2],",",boxparameter[3],",",max,"],"),file=flname,append = TRUE,sep="\n");
     }

     
     if(colwise==0)
     {
        min<-min(data[count,],na.rm = TRUE)
        max<-max(data[count,],na.rm = TRUE)
        val<-data[count,];
     } else { 
        min<-min(data[,count],na.rm = TRUE)
        max<-max(data[,count],na.rm = TRUE)
        val<-data[,count]
     }
     boxparameter<-quantile(val, c(.25, .5, .75),na.rm = TRUE);
     cat(paste0("[",min,",",boxparameter[1],",",boxparameter[2],",",boxparameter[3],",",max,"]"),file=flname,append = TRUE,sep="\n");
     if(colwise==0)
     {
       cat("],\"tooltip\": {\"headerFormat\": \"<em>Metabolite Name {point.key}</em><br/>\"}}",file=flname,append = TRUE,sep="\n");
     } else {
        cat("],\"tooltip\": {\"headerFormat\": \"<em>Experiment Name {point.key}</em><br/>\"}}",file=flname,append = TRUE,sep="\n");
     }
     if(normalized){cat("]",file=flname,append = TRUE,sep="\n"); }
     #stop("done");
}


trim <- function( x ) {
  gsub("(^[[:space:]]+|[[:space:]]+$)", "", x)
}

args = commandArgs(trailingOnly=TRUE)

 
infile<-args[1]
tmpdir=args[5];
data<-read.table(infile,header=TRUE, check.names = TRUE);

rownames<-rownames(data)

cat("[",file=paste0(tmpdir,"metabolite_name.txt"))
for (i in 1:(length(rownames)-1))
{
    cat(paste0("\"",rownames[i],"\","),file=paste0(tmpdir,"metabolite_name.txt"),append=TRUE)
}
cat(paste0("\"",rownames[length(rownames)],"\"]"),file=paste0(tmpdir,"metabolite_name.txt"),append=TRUE)
#write.table(rownames(data),file=paste0(tmpdir,"metabolite_name.txt"),row.names = FALSE);
#write.table(colnames(data),file=paste0(tmpdir,"experiment_name.txt"),row.names = FALSE);

colnames<-colnames(data)
cat("[",file=paste0(tmpdir,"experiment_name.txt"))
for (i in 1:(length(colnames)-1))
{
    cat(paste0("\"",colnames[i],"\","),file=paste0(tmpdir,"experiment_name.txt"),append=TRUE)
}
cat(paste0("\"",colnames[length(colnames)],"\"]"),file=paste0(tmpdir,"experiment_name.txt"),append=TRUE)


#stop("Err");
qqnorm='';
cellnumber='';
metabolite='';
metabolitename<-'';
if(args[2]=="-qqnorm")
{
   qqnorm = args[3];
} else if(args[2]=="-cn")
{
   cellnumber = args[3];
} else if(args[2]=="-mtb")
{
  #metabolite=as.numeric(args[4]);
  metabolitename=trim(args[3]);
}



colflag<-args[4];

#tmpdir=args[5];
print(colflag);
print(qqnorm);
print(cellnumber);
print(metabolitename);





ndata<-ncol(data);
data.m = as.matrix(log10(data))
flname<-numeric(0)

print(as.character(colflag));

if(as.character(colflag)=="0")
{
  flname<-paste0(tmpdir,"tmpdata_row.json")
} else {
  flname<-paste0(tmpdir,"tmpdata_col.json")
}




if (file.exists(flname)) file.remove(flname)


if(as.character(colflag)=="0")
{
  createjsonfile(data.m,flname,0,1);
} else {
  createjsonfile(data.m,flname,0,0);
}
print(flname)


normalizedmatrix<-data.m
if(cellnumber !='')
{
   cellnumdata<-read.table(paste0(tmpdir,"cellnumber_group_selected.txt"),header=TRUE)
   cellnumdata<-unname(unlist(cellnumdata))
   #write.matrix(cellnorm, file = "cellnumber.txt", sep = " ")
   #print(cellnumdata)
   cellnorm<-t(t(data.m)/as.numeric(cellnumdata));
   write.matrix(cellnorm, file = "cellnumber.txt", sep = " ")
   #stop("Message")
   normalizedmatrix<-as.matrix(cellnorm)     
}



if(metabolitename !='')
{
  metabodata<-log10(unlist(unname(data[metabolitename,])));
  print(metabodata);
  write.matrix(data.m, file = "datam.txt", sep = " ") 
  metanorm<-t(t(data.m)/metabodata);
  rows_to_remove <- which(row.names(metanorm) %in% metabolitename)
  print(metabolitename);
  #write.matrix(metanorm, file = "metabolite_matrix.txt", sep = " ")
  metanorm.f<-metanorm[-rows_to_remove,]
  write.matrix(metanorm.f, file = "metabolite_matrix.txt", sep = " ")
  normalizedmatrix<-as.matrix(metanorm.f)
  write.matrix(normalizedmatrix, file = "metabolite_matrix2.txt", sep = " ")
}


if(qqnorm!='')
{
    #print(data.m)
    write.matrix(data.m, file = paste0(tmpdir,"qqnormb.txt"), sep = " ")
    qq.data = (normalizeQuantiles(data.m))
    write.matrix(qq.data, file = paste0(tmpdir,"qqnorma.txt"), sep = " ")
    normalizedmatrix<-as.matrix(qq.data);    
}

if(as.character(colflag)=="0")
{
  createjsonfile(normalizedmatrix,flname,1,1);
} else {
  createjsonfile(normalizedmatrix,flname,1,0);
}


