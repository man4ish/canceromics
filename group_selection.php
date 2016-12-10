<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
        <script><script>
function metabolitedisable() {
    document.getElementById("MetaboliteSelect").disabled=true;
}
function metabolitedisable() {
    document.getElementById("MetaboliteSelect").disabled=false;
}
function cellenable() {
    document.getElementById("CellSelect").disabled=true;
}
function cellenable() {
    document.getElementById("CellSelect").disabled=false;
}
</script></script>
    
      <script>
function goBack() {
    window.history.back();
}
</script>
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
       ?>
         
        
       
<!--<button onclick="goBack()">Go Back</button>-->


<?php
 
$tmpdir=$_SESSION["id"];
$excellfile=$_SESSION["file"];
$excellsheet=$_POST['sheetname'];


echo $excellsheet;

if($excellsheet)
{
  $file = fopen($tmpdir."selected_sheet.txt","w");
  fwrite($file,$excellsheet);
  fclose($file);
} else {
    $myfile = fopen($tmpdir."selected_sheet.txt", "r") or die("Unable to open file!");
    $excellsheet= fgets($myfile);
    fclose($myfile);
    //$excellsheet=readfile($tmpdir."selected_sheet.txt");
}

//echo "<script type='text/javascript'>alert('".$excellsheet."');</script>"; 
$excellsheet = str_replace("\r", '', $excellsheet);
$excellsheet=rtrim($excellsheet); 
$_SESSION["sheet"]=$excellsheet;
//exec('Rscript get_groupname.R '.$excellfile.' "'.$excellsheet.'" '. $tmpdir);

exec('Rscript get_groupname_v2.R '.$excellfile.' "'.$excellsheet.'" '. $tmpdir);
echo('Rscript get_groupname_v2.R '.$excellfile.' "'.$excellsheet.'" '. $tmpdir);
//echo "<script type='text/javascript'>alert('".$excellfile."');</script>"; 
 
//$var='sudo Rscript get_groupname.R '.$excellfile.' "'.$excellsheet.'"'. $tmpdir;
//echo "<script type='text/javascript'>alert('$var');</script>";
//exec('sudo Rscript get_groupname.R '.$excellfile.' "'.$excellsheet.'"'. $tmpdir);
//echo('<br>');
//echo('sudo Rscript get_groupname.R '.$excellfile.' "'.$excellsheet.'"');
//echo('<br>');
//echo "<table style='font-family: Arial;font-size:	12px;'><tr><td width=100><b>Excell File</b></td><td>".$excellfile."</td></tr><tr><td width=100><b>Excell Sheet</b></td><td>".$excellsheet."</td></tr></table>";
?>       



        
        

        <br>
<form action="datamatrix.php?df=1" method="post">
 
<br><br>
<b>Group Selection:</b>
<br>   
 <table style='font-family: Arial, Verdana, sans-serif;font-size:	12px;'> 
     <tr>
<?php 
$file = fopen($tmpdir."group.txt", "r");
$i=0;
while(!feof($file)){
    $line = fgets($file);
    if($line){
       echo "<td width=200><input type='checkbox' name='formDoor[]' value='$line' />".$line."</td>";
    }
    $i++;
    if($i%3==0){echo "</tr><tr>";};
}
fclose($file);
?>    
         </tr></table>
<br><br>

<script>
  function metabolitedisable1(ele) {
    document.getElementById("CellSelect").disabled = false;
  }
 function metabolitedisable2(ele) {
    document.getElementById("CellSelect").disabled = true;
  }
</script>
<!--<b>Normalization:</b>
<br>
  <input type="radio" name="normalize" value="nonorm" onchange="metabolitedisable2(this)"> No Normalization<br>
  <input type="radio" name="normalize" value="qqnorm" onchange="metabolitedisable2(this)"> QQ Normalization<br>
  <input type="radio" name="normalize" value="cell" onchange="metabolitedisable2(this)"> Cell Number Normalization<br>
  <input type="radio" name="normalize" value="metabolite" onchange="metabolitedisable1(this)"> Metabolite Normalization<br>
  	<select id="CellSelect" name="selection" disabled> -->
            <?php
            /*$file = fopen("metabolite.txt", "r");
            $i=0;
            while(!feof($file)){
                  $line = fgets($file);
                  if($line){
                     echo "<option value='$line'>".$line."</option>";
                  }
                  $i++;
                  if($i%3==0){echo "</tr><tr>";};
            }
            fclose($file);*/
            ?>    
  <!--      </select> 
  
<br>
<br>
<br>
<br>-->
<input type="submit" name="formSubmit" value="Select Groups" />
</form>
<?php
$_SESSION["gflag"]=0;
frontendPageContentEnd();
frontendPageFooter("May 12, 2016");
frontendHTMLFooter();
?>
    </body>
</html>



        


     
