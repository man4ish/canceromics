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
        <form action="datamatrix.php" method="get">  <button style="float: right;"><img src="img/arrow.jpeg"  height="20" width="20" /></button></form><br>    
<form action="result.php" method="post">
    <script>
  function metabolitedisable1(ele) {
    document.getElementById("CellSelect").disabled = false;
  }
 function metabolitedisable2(ele) {
    document.getElementById("CellSelect").disabled = true;
  }
</script>
<script>
function goBack() {
    window.history.back();
}
</script>


<?php
if (isset($_POST['submit'])) {
               if(isset($_POST['imputationtype']))
               {
                   $type=$_POST['imputationtype'];
                   echo "You have selected :".$_POST['imputationtype'];  //  Displaying Selected Value
               }
            }
$tmpdir=$_SESSION["id"];
            //echo('sudo Rscript imputation.R '.$tmpdir.'group_selected.txt '.$type.' '.$tmpdir);
$cellfile = fopen($tmpdir."cell_flag.txt", "r") or die("Unable to open file!");
$cellflag=fgets($cellfile);
fclose($cellfile);

?>
<br>


<br>
<b>Normalization:</b>
<br>
  <input type="radio" name="normalize" value="nonorm" onchange="metabolitedisable2(this)"> No Normalization<br>
  <input type="radio" name="normalize" value="qqnorm" onchange="metabolitedisable2(this)"> QQ Normalization<br>
  <?php if($cellflag != "0"){ ?>
  <input type="radio" name="normalize" value="cell" onchange="metabolitedisable2(this)"> Cell Number Normalization<br>
  <?php } ?>
  <input type="radio" name="normalize" value="metabolite" onchange="metabolitedisable1(this)"> Metabolite Normalization<br>
  	<select id="CellSelect" name="selection" disabled>
            <?php
            
            
            //$imputationtype=$_POST['type'];
            
            



            //print($imputationtype);
            //$type="gmedian";
            
            exec('Rscript imputation.R '.$tmpdir.'group_selected.txt '.$type.' '.$tmpdir);
                  
            $file = fopen($tmpdir."metabolite.txt", "r");
            $i=0;
            while(!feof($file)){
                  $line = fgets($file);
                  if($line){
                      $line=str_replace('"', "", $line);
                     echo "<option value='$line'>".$line."</option>";
                  }
                  $i++;
                  if($i%3==0){echo "</tr><tr>";};
            }
            fclose($file);
            ?>    
        </select> 
<br>
<br>
<br>
Generate plot by:
<br><br>
<input type="radio" name="boxplot" value="metabolite">By Experiment<br>
<input type="radio" name="boxplot" value="experiment">By Metabolite<br>

<br>
<input type="submit" name="formSubmit" value="Normalize" />
</form>
<?php
frontendPageContentEnd();
frontendPageFooter("May 12, 2016");
frontendHTMLFooter();
?>
    
</body>
</html>
