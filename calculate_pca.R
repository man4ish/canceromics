library(ggfortify)
df <- iris[c(1, 2, 3, 4)]
autoplot(prcomp(df))
