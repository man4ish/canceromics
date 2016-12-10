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
</script>
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
        
        <form action="normalize.php" method="get">  <button style="float: right;"><img src="img/arrow.jpeg"  height="20" width="20" /></button></form><br>
     
        <?php
$arrText =  $_POST['formDoor'];
$excellfile=$_GET['excellfile'];
$excellsheet=$_GET['excellsheet'];


print($excellfile);
print("<br>");
print($excellsheet);
print("<br>");
/*echo "<b>Group Selection:</b><br>";
$myfile = fopen('selected_group.txt', 'w');
for  ($i=0;$i<sizeof($arrText);$i++)
{
    fwrite($myfile,$arrText[$i]);
     echo $arrText[$i]."<br>";
}
fclose($myfile);
*/
$normtype = $_POST['normalize'];
if($normtype=="qqnorm")
{
    $filepath = "qqnorm.txt";
} else if($normtype=="cell")
{
    $filepath = "cellnumber.txt";
} else if($normtype=="metabolite")
{
    $filepath = "metabolite_matrix.txt";
} else
{
    $filepath = "nonorm.txt";
}

//echo $gender; 

echo "<h2>Normalization Summary:</h2>";
echo "<br><b>Normalization Type:</b>";
echo $normtype; 
$normflag='';
$selection='0';
if($normtype=="metabolite"){
    $selection = $_POST['selection'];
    echo "<br><b>Metabolite Name: </b>".$selection."<br>";
    $normflag='-mtb';
} else if($normtype=="cell"){
    $normflag='-cn';
} else if($normtype=="qqnorm"){
    $normflag='-qqnorm';
} else 
{
    $normflag='-nonorm';
}

echo('<hr><br>');
$tmpdir=$_SESSION["id"];
$excellsheet=$_SESSION["sheet"];
$excellfile=$_SESSION["fname"];
$colflag=1;
//exec('sudo Rscript normalize_data_colrow.R '.$excellfile.' "'.$excellsheet.'"  '.'-cn 42 '. $tmpdir);

exec('Rscript normalize_data_colrow.R '. $tmpdir.'imputted.txt '. $normflag.' "'.$selection.'" '. $colflag.' '.$tmpdir);
echo('Rscript normalize_data_colrow.R '. $tmpdir.'imputted.txt '. $normflag.' "'.$selection.'" '. $colflag.' '.$tmpdir);
//exec('sudo Rscript normalize_data_colrow.R '.$excellfile.' "'.$excellsheet.'"  '.'-cn 42 '.$colflag.' '. $tmpdir);
//echo('sudo Rscript normalize_data_colrow.R '. $tmpdir.'imputted.txt '. $normflag.' "'.$selection.'" '. $colflag.' '.$tmpdir);

$colflag=0;
exec('Rscript normalize_data_colrow.R '. $tmpdir.'imputted.txt '. $normflag.' "'.$selection.'" '. $colflag.' '.$tmpdir);
//echo('Rscript normalize_data_colrow.R '. $tmpdir.'imputted.txt '. $normflag.' "'.$selection.'" '. $colflag.' '.$tmpdir);
//exec('sudo Rscript normalize_data_colrow.R '.$excellfile.' "'.$excellsheet.'"  '.'-cn 42 '.$colflag.' '. $tmpdir);

//echo('sudo Rscript group_selection_normalization.R '. $excellfile.' "'.$excellsheet.'" '.'selected_group.txt '.$normflag.' "'.$selection.'"');
//exec('sudo Rscript group_selection_normalization.R '. $excellfile.' "'.$excellsheet.'" '.'selected_group.txt '.$normflag.' "'.$selection.'"');
?>
    
<!--<form action="result.php" method="post"> 
<h2>Generate Box Plot Distribution:</h2><br>

<input type="radio" name="boxplot" value="metabolite">By Experiment<br>
<input type="radio" name="boxplot" value="experiment">By Metabolite<br>

<input type="submit" value="Generate Plot" style="float:right" >   
      <br><br>
 --> 
      <?php   
       if($_POST['boxplot'] != 'metabolite')
       {
           include('boxplot_metabolite.html');
       } else  if($_POST['boxplot'] != 'experiment') {
           include('boxplot_experiment.html');
       }

      ?>
   <!--   </form> -->
        
        
<br><hr><br>
<h2>PCA Plot Distribution:</h2><br>

<?php   
       if($_POST['boxplot'] != 'metabolite')
       {
           include('pca_metabolite.html');
       } else  if($_POST['boxplot'] != 'experiment') {
           include('pca_experiment.html');
       }

?>
<!--<img src="pca_svd-unnamed-chunk-71.png" alt="Smiley face" height="800" width="800">-->

<br><hr><br>
      
<form action="stats.php" method="post">      
<h2>Apply Models:</h2><br>

<input type="radio" name="gender" value="lm" checked>Linear Model(lm)<br>
<input type="radio" name="gender" value="glm">General Linear Modlel(glm)<br>

<input type="submit" value="Apply" style="float:right" >        
</form>

        <?php
frontendPageContentEnd();
frontendPageFooter("May 12, 2016");
frontendHTMLFooter();
?>
        

 </body>
</html>
