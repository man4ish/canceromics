# read in metabolon file
# by JK, 2016

#library(xlsx)
library(readxl)
library(stringr)
library(purrr)


args = commandArgs(trailingOnly=TRUE)
excel.file =args[1]; 
excel.sheet=args[2];
groupfile<-read.table(args[3],header=FALSE)
tmpdir<-args[4];
rexp<-paste(groupfile[,1], collapse = '|')
print(rexp)



parseMetabolonFile <- function(file, sheet, copynansheet='') {
  
  # using xlsx package:
  #raw = read.xlsx(file=file, sheetName=sheet, header=F, stringsAsFactors=F)
  
  # using readxl package:
  raw = read_excel(path=file, sheet=sheet, col_names = F)
   

  
  colstart=15;
  #rowstart=15;
  rowstart<-detect_index(raw[1:20,1], function(x) x != "") 
  
  infodata<-raw[1:rowstart,]
  rowinfostart<-detect_index(infodata[,1], function(x) x != "")   
  
  inforownames<-infodata[,rowinfostart-1];
  print(inforownames)
  cell_flag<-length(grep("CELL NUMBER",inforownames))

  #stop("Message");
  cat(cell_flag,file = paste0(tmpdir,"cell_flag.txt"),sep="\n");  

  write.table(infodata[,rowinfostart-1], file = paste0(tmpdir,"info.txt"), sep="\t"); 

  biochemical<-raw[(rowstart+1):nrow(raw),2]

  biochemicalllist<-unname(unlist(biochemical))
  print(biochemicalllist);
  ndata<-ncol(data);
  
  names<-unname((unlist(raw[rowstart,colstart:length(raw)])))
  print(names)
  raw<-raw[(rowstart+1):nrow(raw),colstart:length(raw)]
  #raw[is.na(raw)] <- 1
  colnames(raw) <- names
  
  #print(raw[grep(rexp, names)]);
  
  selected_data<-data.matrix(raw[grep(rexp, names)]);
  write.table(selected_data, file = paste0(tmpdir,"group_selected_old.txt"), row.names=biochemicalllist, sep="\t");
  selected_data<-log10(selected_data);
  
  write.table(selected_data, file = paste0(tmpdir,"group_selected.txt"), row.names=biochemicalllist, sep="\t");
  #print(selected_data);

  #stop("Message");                
  #print(length(selected_data));
        
  #print(nrow(selected_data));
 
  #stop("Message");
  #print(selected_data)
  #write.table(log10(selected_data), file = paste0(tmpdir,"group_selected.txt"), row.names=biochemicalllist, sep="\t"); 
  
  
  
  #if ("BIOCHEMICAL" %in% colnames(result$metinfo)) {
  #  colnames(result$data) = result$metinfo$BIOCHEMICAL
  #} else {
  #  colnames(result$data) = c()
  #}
  
  
  
  raw = read_excel(path=file, sheet=sheet, col_names = F)
  #print(raw)
  celldata<-unname(raw[7,15:length(raw)])
  ncell<-ncol(celldata)
  ndata<-ncol(raw);
  #for (i in 1:(ndata-ncell))
  {
  #   celldata<-cbind(celldata,1);
  }

  
  cellnumdata<-setNames(celldata, names)

  
  selected_celldata<-cellnumdata[grep(rexp, names(cellnumdata))]
  #print(selected_celldata) 
  write.table(selected_celldata, file = paste0(tmpdir,"cellnumber_group_selected.txt"),sep = "\t",col.names = TRUE)
  stop("Message");
  colstart=15;
  #rowstart=15;
  rowstart<-detect_index(raw[1:20,1], function(x) x != "") 
  #print(index)
  raw<-raw[rowstart:nrow(raw),colstart:length(raw)]

  print(raw)
  stop("Message");
  result=list()
  
  # find metabolite header row and last metabolite row
  imetheader = min(which(!is.na(raw[,1])))
  imetlast = max(which(apply(is.na(raw),1,sum)<dim(raw)[2]))
  # find sample header column and last sample row
  isampheader = min(which(!is.na(raw[1,])))
  isamplast = max(which(apply(is.na(raw),2,sum)<dim(raw)[1]))
  
  # fix overlapping cell
  overl=str_replace(gsub("\\s+", " ", str_trim(raw[imetheader,isampheader])), "B", "b")
  overl=strsplit(overl, " ")[[1]]
  overlmet = overl[2]
  overlsamp = overl[1]
  
  
  # extract metabolite information
  result$metinfo = raw[(imetheader+1):imetlast, 1:isampheader]
  colnames(result$metinfo) = raw[imetheader, 1:isampheader]
  rownames(result$metinfo) = c()
  # fix last one
  colnames(result$metinfo)[dim(result$metinfo)[2]] = overlmet
  # also add Metabolon ID in M00000 format
  result$metinfo$COMP_IDstr = sapply(sprintf('%05d',as.numeric(result$metinfo$COMP_ID)), function(x)paste0('M',x))
  
  # extract sample information
  result$sampleinfo = data.frame(t(raw[1:imetheader-1,(isampheader+1):isamplast]))
  colnames(result$sampleinfo) = as.list(raw[1:imetheader-1,isampheader])[[1]] # dirty hack, had something to do with the output format of read_excel
  rownames(result$sampleinfo) = c()
  
  
  # extract data
  d=t(raw[(imetheader+1):imetlast, (isampheader+1):isamplast])
  result$data = as.data.frame(apply(d,2, as.numeric))
  
  # as column names, use "BIOCHEMICAL", if available
  if ("BIOCHEMICAL" %in% colnames(result$metinfo)) {
    colnames(result$data) = result$metinfo$BIOCHEMICAL
  } else {
    colnames(result$data) = c()
  }
  
  # as row names, use "SAMPLE_NAME", if available 
  if ("SAMPLE_NAME" %in% colnames(result$sampleinfo)) {
    rownames(result$data) = result$sampleinfo$SAMPLE_NAME
  } else {
    rownames(result$data) = c()
  }
  
  # set info flags
  result$info$file = file
  result$info$sheet = sheet
  
  # copy NanN from another sheet?
  if (nchar(copynansheet)>0) {
  # recursive call
    nandf = parseMetabolonFile(file=file, sheet=copynansheet)
    # sanity checks
    if (!(all.equal(colnames(result$data), colnames(nandf$data)))==T)
      stop('some metabolites are different between data sheet and NaN sheet');
    if (!all.equal(rownames(result$data), rownames(nandf$data)))
      stop('some sample names are different between data sheet and NaN sheet');
    # copy over NaNs
    result$data[is.na(nandf$data)]=NA
    # set info flag
    result$info$copynansheet = copynansheet

  }
  # return
  result
}


# test code from jan
#if (F) { # never execute automatically
 # file = '/home/manish/Desktop/origdata_c.n..xlsx'
 # sheet = "OrigScale (media)"

 file =args[1];
 sheet=args[2];
  
  D = parseMetabolonFile(file,sheet)
#}

