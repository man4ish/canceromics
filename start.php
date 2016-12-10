<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
    </head>
    <body>
       <form action="upload.php" method="post" enctype="multipart/form-data">
         Select file to upload:
        <input type="file" name="fileToUpload" id="fileToUpload" value="c:/passwords.txt">
        <input type="submit" value="Upload" name="submit">
        </form>
    </body>
</html>

