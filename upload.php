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
        <script>
               table {
      border-collapse: collapse;
    }
    th, td {
      padding: 5px 10px;
      border: 1px solid #999;
    }
    th {
      background-color: #eee;
    }
    th[data-sort]{
      cursor:pointer;
    }
    tr.awesome{
      color: red;
    }
    #msg {
      color: green;
    }
function goBack() {
    window.history.back();
}
</script>
 <script type="text/javascript" src="https://code.jquery.com/jquery-1.9.1.js"></script>
  <script src="https://code.highcharts.com/highcharts.js"></script>
  <script src="https://code.highcharts.com/modules/heatmap.js"></script>
  <script src='legend.js'></script>
 
    </head>
    <body>
       
               <?php
       session_start();
if (!isset($_SESSION['snpbin'])) { $_SESSION['snpbin'] = array(); }


	require_once("canceromics/frontend.php");
	require_once("canceromics/menuStructure.php");

	$task = $_GET['task'];
	
	if(!isset($task) || $task=="" || !(in_array($task,$task_whitelist))){
		$task = "home";
		$contentboxtext = "Canceromics - an interactive tool for analysing and visualizing metabolon data ";                //Title changed
	} 
       
	$addheader = "";
	if(($task == "proxy_search") || ($task == "pairwise_ld") ) {
		$addheader = '
		<script type="text/javascript" src="frontend/js/DataTables-1.9.4/media/js/jquery.dataTables.min.js"></script>
		<script type="text/javascript" src="frontend/js/DataTables-1.9.4/extras/Scroller/media/js/dataTables.scroller.min.js"></script>
		<script type="text/javascript" src="frontend/js/DataTables-1.9.4/extras/FixedColumns/js/dataTables.fixedColumns.min.js"></script>
		<script type="text/javascript" src="frontend/js/DataTables-1.9.4/extras/SortFormattedNumbers/js/dataTables.formattedNumbers.js"></script>
		<style type="text/css" title="currentStyle">
			@import "frontend/css/jquery.dataTables.css";
		</style>
		<style type="text/css" title="currentStyle">
			@import "frontend/js/DataTables-1.9.4/extras/FixedColumns/css/dataTables.fixedColumns.min.css";
		</style>
		<script type="text/javascript" src="frontend/js/proxy_search.js"></script>';
	}
	
	if($task == "snp_annotation") {
		$addheader = 
		'
		<script type="text/javascript" src="frontend/js/snp_annotation.js"></script>
		';
	}
    
	if($task == "block_annotation") {
		$addheader = 
		'
		<script type="text/javascript" src="frontend/js/block_annotation.js"></script>
		';
	}
      
	
	if($task == "regional_association_plot") {
		$addheader = 
		'
		<script type="text/javascript" src="frontend/js/regional_association_plot.js"></script>
		<script type="text/javascript" src="frontend/js/highcharts-3.0.9/highcharts.js"></script>
		<script type="text/javascript" src="frontend/js/highcharts-3.0.9/highcharts-more.js"></script>
		<script type="text/javascript" src="frontend/js/highcharts-3.0.9/exporting.src.js"></script>
		
		';
	}

	if($task == "ld_plot") {
		$addheader = 
		'
                <script src="http://code.highcharts.com/highcharts.js"></script>
                <script src="https://rawgit.com/highslide-software/highcharts.com/master/js/modules/boost.src.js"></script>
                <script src="http://code.highcharts.com/highcharts-more.js"></script>
                <script src="http://code.highcharts.com/modules/heatmap.js"></script>
                <script src="http://code.highcharts.com/modules/exporting.js"></script>
		<script type="text/javascript" src="frontend/js/ld_plot.js"></script>
		';
	} 
	
	if($task == "association_maps") {
		$addheader = 
		'
		<script type="text/javascript" src="frontend/js/association_maps.js"></script>
		<script type="text/javascript" src="frontend/js/highcharts-3.0.9/highcharts.js"></script>
		<script type="text/javascript" src="frontend/js/highcharts-3.0.9/highcharts-more.js"></script>
		';
	} 
	
	if($task == "variant_browser") {
		$addheader = 
		'
		<script type="text/javascript" src="frontend/js/variant_browser.js"></script>
		<script type="text/javascript" src="frontend/js/highcharts-3.0.9/highcharts.js"></script>
		<script type="text/javascript" src="frontend/js/highcharts-3.0.9/highcharts-more.js"></script>
		<script type="text/javascript" src="frontend/js/highcharts-3.0.9/exporting.src.js"></script>
		';
	} 
	
	if($task == "documentation") {
		$addheader = 
		'
		<link href="frontend/js/video-js/video-js.min.css" rel="stylesheet">
		<script src="frontend/js/video-js/video.js"></script>
		<script>
		  videojs.options.flash.swf = "frontend/js/video-js/video-js.swf"
		</script>
		<script type="text/javascript" src="frontend/js/documentation.js"></script>
		';
	} 
        if($task == "summary") {
                $addheader =
                '
                <script type="text/javascript" src="frontend/js/variant_browser.js"></script>
                <script type="text/javascript" src="frontend/js/highcharts-3.0.9/highcharts.js"></script>
                <script type="text/javascript" src="frontend/js/highcharts-3.0.9/highcharts-more.js"></script>
                <script type="text/javascript" src="frontend/js/highcharts-3.0.9/exporting.src.js"></script>
                ';
        }

	
        if($task == "ld_plot")
        {
	   frontendHTMLHeader("GenoQ - a single nucleotide polymorphisms annotator and browser",$addheader,"1");                    //** Title Changed
	} else {
           frontendHTMLHeader("GenoQ - a single nucleotide polymorphisms annotator and browser",$addheader,"0");
        }
        frontendPageHeader(".");
	frontendPageNavigationStart();
        frontendPageNavigationBox($menuSelection,"Select Data",$task);
	frontendPageNavigationBox($menuAnnotation,"Analysis",$task);
        frontendPageNavigationBox($menustaticalAnalysis,"Statistical Analysis",$task);
        frontendPageNavigationBox($menuPlot,"Data Visualization",$task);
	frontendPageNavigationBox($menuHelp,"Help",$task);

	include("frontend/frontendPageNavigationSNPPastebin.php");

	frontendPageNavigationEnd();


	frontendPageContentStart($contentboxtext);
	
        
        $target_dir = "/home/metabolomics/Canceromics/uploads/";
$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
$uploadOk = 1;
$imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
// Check if image file is a actual image or fake image


// Check file size
if ($_FILES["fileToUpload"]["size"] > 50000000) {
    echo "Sorry, your file is too large.";
    $uploadOk = 0;
}

// Check if $uploadOk is set to 0 by an error
if ($uploadOk == 0) {
    echo "Sorry, your file was not uploaded.";
// if everything is ok, try to upload file
} else {
    if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
        echo "The file ". basename( $_FILES["fileToUpload"]["name"]). " has been uploaded.";
    } else {
        echo "Sorry, there was an error uploading your file.";
    }
}

$_SESSION['file']=$target_file;
?>
        
        
        <div id="container" style="height: 200px; width: 600px; max-width: 800px; margin: 0 auto"></div>
        <form action="upload.php" method="post" enctype="multipart/form-data">
         Select file to upload:
        <input type="file" name="fileToUpload" id="fileToUpload" value="c:/passwords.txt">
        <input type="submit" value="Upload" name="submit">
        </form>
        <br> <br>
        <form action="group_selection.php" method="post" enctype="multipart/form-data">        
        <br><br>
        <?php
        $id = time()*100000+rand(0,99999);

$_SESSION["tmpid"] = $id;
$tmpdatadir = "/home/metabolomics/Canceromics/web/tmpdata/".$id."/";
//echo "<script type='text/javascript'>alert('$tmpdatadir');</script>";
$_SESSION["id"] = $tmpdatadir;
if (mkdir($tmpdatadir)) {
	$status = array();
	$status['stepnum'] = 1;
	$status['totalstepnum'] = 100;
	$status['message'] = "";
	$status['errmessage'] = "";
	$status['ok'] = "";
	
	$statfilefh = fopen($tmpdatadir."/status.txt.1",'w');
	fwrite($statfilefh, utf8_encode(json_encode($status)));
	fclose($statfilefh);
	copy($tmpdatadir."/status.txt.1",$_SESSION["id"]."/status.txt");
	//print($_SESSION["favcolor"]);
}
        //echo "<script type='text/javascript'>alert('$tmpdatadir');</script>";    
         //echo $target_file;
         exec('Rscript get_sheetname.R "'.$target_file.'" '.$tmpdatadir);   
         $var='sudo Rscript get_sheetname.R "'.$target_file.'" '.$tmpdatadir;
         //echo "<script type='text/javascript'>alert('$var');</script>";
         //exec('sudo Rscript get_groups.R '.$target_file.' ' data/origdata_c.n..xlsx MediaandCell');
        ?>        
        
     <select name="sheetname">
       <option selected="selected">select sheet</option>
         <?php $file = fopen($tmpdatadir."/sheetname.txt","r"); while(! feof($file)) { $val=fgets($file); if($val){?>
         <option value="<?php echo $val;?>"> <?php echo $val; ?>
       </option>
     <?php }} fclose($file);?>
     </select>
        
       <br><br>
       
       <input type="submit" value="Submit" name="submit">
       </form>
       <br> <br> <br> <br> <br> <br> <br> <br> <br> <br> <br> <br> <br> <br> <br> <br> <br> <br> <br> <br> <br>
       <?php
        //echo('Rscript get_colheader.R '.$target_file.' '.$tmpdatadir."sheetname.txt ". $tmpdatadir);
        //exec('Rscript get_colheader.R '.$target_file.' '.$tmpdatadir."sheetname.txt ".$tmpdatadir);
	frontendPageContentEnd();
	frontendPageFooter("July 27, 2016");
	frontendHTMLFooter();
       ?>
    </body>
</html>

