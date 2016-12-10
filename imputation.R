args = commandArgs(trailingOnly=TRUE)

datafile =args[1];    #group_selected.txt (after exclusion)

flag=args[2];
tmpdir<-args[3];
outfile<-paste0(tmpdir,"imputted.txt");
metabolitefile<-paste0(tmpdir,"metabolite.txt");
#print(flag);
 
if (file.exists(outfile)) file.remove(outfile)
if (file.exists(metabolitefile)) file.remove(metabolitefile)
data<-read.table(datafile,header=TRUE,row.names=1)
push <- function(x, values) (assign(as.character(substitute(x)), c(x, values), parent.frame()))
drop_vec<-numeric(0)
print(data[1,1])

getnacount<-function(selectedgroup)
{  
   	narray<-numeric(0);
   	#print(selectedgroup)
   	for (i in 1:nrow(selectedgroup))
   	{
     			#push(narray,sum(is.na(selectedgroup[i,])))
        }

        fn <- "selected_group.file50.txt"
	if (file.exists(fn)) file.remove(fn)
	fn.file50.df<-data.frame();

	cat(header,file=fn,append=T,"\n");
	for (i in 1:nrow(data))
	{
  		percentage_error = sum(is.na(data[i,]))*100/ncol(data);
  		if(percentage_error<=50)
  		{
      			cat(paste(row.names(data)[i],data[i,],sep="\t"),Append=T,file=fn,"\n");
  		}
	}
   		return(narray);
}

if(flag=="omean" ||flag=="omedian" )
{
   for (i in 1:nrow(data))
   {
     if(sum(is.na(unname((data[i,]))))>0)
     {
        total<-sum(is.na(unname((data[i,]))))
        percentage_error = sum(is.na(unname((data[i,]))))*100/ncol(data);
        #cat(total,percentage_error,"\n");
        #cat(unname(unlist(data[i,])),"\n");
        if(percentage_error > 50)
        {
           push(drop_vec,i);
           #cat(unname(unlist(data[i,])),"\n");
           #data<-data[-i, ] 
           #cat("**",unname(unlist(data[i,])),"\n");
        } else 
        { 
           x<-data[i,1:ncol(data)]
           cat(percentage_error,unlist(x),"\n");
         
           if(flag=="omean"){
                                 naval<-mean(unlist(x),  na.rm=TRUE);
           } else if(flag=="omedian"){
                                   naval<-median(unlist(x),  na.rm=TRUE);
           }
           print(naval) 
            
           x[is.na(x)] <- naval
           data[i,1:ncol(data)]<-x
           print( data[i,1:ncol(data)]);
           #cat("**",percentage_error,unlist(x),"\n");
           
        }
     }
   }
} else if(flag=="gmean" ||flag=="gmedian") {
	
	groups<-unname(unlist(read.table(paste0(tmpdir,"selected_group.txt"))));	
        
	print(groups)
       
	overall<-numeric(0);
	header<- names(data);

	for (c in 1:length(groups))
	{
          	regexp<-groups[c];
          	
                
          	selectedgroup<-data[grep(regexp, names(data))]

                
               
                #cat(unlist(selectedgroup),sep="\t");
               
          	for (i in 1:nrow(selectedgroup))
          	{
                        metabolitename<-rownames(selectedgroup[i,]);
                        #print(metabolitename);
             		#print(unname(unlist(selectedgroup[i,])));
                        if(i==6){print(selectedgroup[i,])}                        
             		if(sum(is.na(unname(unlist(selectedgroup[i,]))))>0)
             		{    
                           #print(sum(is.na(selectedgroup[i,])));
                           #print(ncol(selectedgroup));
                                               
                           percentage_error = sum(is.na(selectedgroup[i,]))*100/(ncol(selectedgroup));
                           
                           
                          
                           if(percentage_error >= 50)
                           {
                              
                              #print(percentage_error);
                              #print(is.na(unname(unlist(selectedgroup[i,]))));
                              
                              #print(data[i,]);
                              #data<-data[-i, ]
                              push(drop_vec,i);
                              
                           } else 
                           {
                              
                              if(flag=="gmean"){                             
                                 naval<-mean(unlist(unname(selectedgroup[i,])),  na.rm=TRUE);
                                 
                              } else if(flag=="gmedian")
                              {
                                   naval<-median(unlist(selectedgroup[i,]),  na.rm=TRUE);                          
                              }  
                              
                              #print(naval);
                              x<-selectedgroup[i,]
                              
                              #print(x)
                              x[is.na(x)] <- naval
                              #print(x)
                              

                              #print(paste("****",data[grep(regexp, names(data))][i,]));
                              #print(selectedgroup[i,]);
             		      #median(selectedgroup[i,], na.rm = FALSE);   #replace NA value with mean or median   #MAKE data fRAME
                              #data[grep(regexp, names(data))][i,]<-x
                              data[grep(regexp, names(data))][metabolitename,]<-x
                              #cat(c,i);
                              #print(data[grep(regexp, names(data))][metabolitename,]);                              
                           }
                          
            	        } else {
                            #cat("**",unname(unlist(selectedgroup[i,])),"\n")
                             }
             	            #push(narray,sum(is.na(selectedgroup[i,])))
                }
          #stop("Message");
          #selectedna<-getnacount(selectedgroup);
          #summary<-cbind(summary,regexp=selectedna)
	}
}

#print(nrow(data));
  
drop_vec<-sort(unique(drop_vec));
data.filt<-data[0,]

print(drop_vec)


for (rownum in 1:nrow(data))
{
    if((rownum %in% drop_vec)==FALSE)
    {
       #print(data[rownum,])
       #cat(unname(unlist(data[rownum,])),"\n")
       data.filt<-rbind(data.filt,data[rownum,]);
    }
}


write.table(data.filt,file=outfile,row.names = TRUE);
write.table(rownames(data.filt),file=metabolitefile,row.names=FALSE, col.names = FALSE,sep="\t");
