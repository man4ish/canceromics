#!/usr/bin/env Rscript
args = commandArgs(trailingOnly=TRUE)
library(xlsx)
wb<-loadWorkbook(args[1])
sheets <- getSheets(wb)
cat(ls(sheets),file="test.txt",sep="\n")
