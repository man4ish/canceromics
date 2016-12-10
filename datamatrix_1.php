
<html>
<head>  
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
<script src="stupidtable.js?dev"></script>
 <script>
  $(function(){
      // Helper function to convert a string of the form "Mar 15, 1987" into
      // a Date object.
      var date_from_string = function(str){
        var months = ["jan","feb","mar","apr","may","jun","jul",
                      "aug","sep","oct","nov","dec"];
        var pattern = "^([a-zA-Z]{3})\\s*(\\d{2}),\\s*(\\d{4})$";
        var re = new RegExp(pattern);
        var DateParts = re.exec(str).slice(1);

        var Year = DateParts[2];
        var Month = $.inArray(DateParts[0].toLowerCase(), months);
        var Day = DateParts[1];
        return new Date(Year, Month, Day);
      }

      var moveBlanks = function(a, b) {
        if ( a < b ){
          if (a == "")
            return 1;
          else
            return -1;
        }
        if ( a > b ){
          if (b == "")
            return -1;
          else
            return 1;
        }
        return 0;
      };
      var moveBlanksDesc = function(a, b) {
        // Blanks are by definition the smallest value, so we don't have to
        // worry about them here
        if ( a < b )
          return 1;
        if ( a > b )
          return -1;
        return 0;
      };

      var table = $("table").stupidtable({
        "date":function(a,b){
          // Get these into date objects for comparison.

          aDate = date_from_string(a);
          bDate = date_from_string(b);

          return aDate - bDate;
        },
        "moveBlanks": moveBlanks,
        "moveBlanksDesc": moveBlanksDesc,
      });

      table.on("beforetablesort", function (event, data) {
        // data.column - the index of the column sorted after a click
        // data.direction - the sorting direction (either asc or desc)
        $("#msg").text("Sorting index " + data.column)
      });

      table.on("aftertablesort", function (event, data) {
        var th = $(this).find("th");
        th.find(".arrow").remove();
        var dir = $.fn.stupidtable.dir;

        var arrow = data.direction === dir.ASC ? "&uarr;" : "&darr;";
        th.eq(data.column).append('<span class="arrow">' + arrow +'</span>');
      });

      $("tr").slice(1).click(function(){
        $(".awesome").removeClass("awesome");
        $(this).addClass("awesome");
      });

    });
 
 </script>   
    
    
    

        <meta charset="UTF-8">
        <title></title>
 
      <meta charset="utf-8">
      <title>jQuery UI Tabs - Default functionality</title>
      <link rel="stylesheet" href="https://code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
      <script src="https://code.jquery.com/jquery-1.10.2.js"></script>
      <script src="https://code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
      <link rel="stylesheet" href="/resources/demos/style.css">
      <style>
      #tabs{
  
}

#vartabs .ui-widget-header {
  background-color: white;
  background-image: none;
  border-top: none;
  border-right: none;
  border-left: none;
}

#vartabs .ui-widget-content {
  background-color: white;
  background-image: none;

}

#vartabs .ui-corner-top,#tabs .ui-corner-bottom,#tabs .ui-corner-all{
  border-top-left-radius: 0px;
  border-top-right-radius: 0px;
  border-bottom-left-radius: 0px;
  border-bottom-right-radius: 0px;
}



#vartabs .ui-state-default,
#vartabs .ui-state-default a {
  background-color: white;
  text-align: center;
}

#vartabs .ui-state-default a {
  width: herepx;
}


#vartabs .ui-tabs-active,
#vartabs .ui-tabs-active a {
  background-color: darkgray;
  text-align: center;
  
}

 </style>
 
 
 <style>
     th.rotate {
  /* Something you can count on */
  height: 140px;
  white-space: nowrap;
}

th.rotate > div {
  transform: 
    /* Magic Numbers */
    translate(0px, 51px)
    /* 45 is really 360 - 45 */
    rotate(270deg);
    width: 8px;
}
th.rotate > div > span {
  border-bottom: 1px solid #ccc;
  padding: 5px 10px;
}


table tbody 
{
   overflow: auto;
   height: 10px;
}



.scroll {
    max-height: 700px;
    overflow: auto;
}
th
{
    width: 72px;
}


 </style>
<script src="sorttable.js"></script>
 <script>
      $(function() {
        $( "#vartabs" ).tabs();
      });
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
<style type="text/css">
.tftable {font-size:px;color:#333333;width:1%;border-width: 0px;border-color: #ebab3a;border-collapse: collapse;}
.tftable th {font-size:px;border-width:.1px;padding-left:0px;padding: 0px;border-style: solid;border-color: #ebab3a;text-align:center;}
.tftable tr { line-height: px;padding-left:0px;padding-right:0px;}
.tftable td {font-size:px;border-width: .1px;padding-top:0px;padding-bottom:0px;border-style: solid;border-color: #ebab3a;height: px; font-size: 50%;}
.tftable tr:hover {background-color:#ffffff;border:none;}
th.rotate {
    white-space: nowrap;
    -webkit-transform-origin: 65px 60px;
    -moz-transform-origin: 65px 60px;
    -o-transform-origin: 65px 60px;
    -ms-transform-origin: 65px 60px;
    transform-origin: 65px 60px;
    
	-webkit-transform: rotate(270deg);
	-moz-transform: rotate(270deg);
	-ms-transform: rotate(270deg);
	-o-transform: rotate(270deg);
	transform: rotate(270deg);
}
span.intact {
	display:inline-block;
	width:15px;
	height:150px;
        margin-left: 0cm;
        margin-right: 0cm;
        font-size: 50%;
}

</style>

<style>
.tooltip {
    position: relative;
    display: inline-block;
    border-bottom: 1px dotted black;
}

.tooltip .tooltiptext {
    visibility: hidden;
    width: 120px;
    background-color: white;
    color: black;
    text-align: left;
    
    /* Position the tooltip */
    position: absolute;
    z-index: 1;
    top: -5px;
    left: 105%;
}

.tooltip:hover .tooltiptext {
    visibility: visible;
}

input.checkbox  {
	width : 0.5em;
	height :0.5em;
	padding: 0px;
	margin: 0px;
	}
        
</style>
<script>
    function writeToFile(d1, d2){
    var fso = new ActiveXObject("Scripting.FileSystemObject");
    var fh = fso.OpenTextFile("data.txt", 8, false, 0);
    fh.WriteLine(d1 + ',' + d2);
    fh.Close();
    }

    
    $( "#q" ).submit(function( event ) {  
    $('input[type="checkbox"]').each(function(index) {
        if(!this.checked) {
          
           alert(this.value);
        }
    });
    event.preventDefault();
    });
</script>

<script>
    $('th').click(function(){
    var table = $(this).parents('table').eq(0)
    var rows = table.find('tr:gt(0)').toArray().sort(comparer($(this).index()))
    this.asc = !this.asc
    if (!this.asc){rows = rows.reverse()}
    for (var i = 0; i < rows.length; i++){table.append(rows[i])}
})
function comparer(index) {
    return function(a, b) {
        var valA = getCellValue(a, index), valB = getCellValue(b, index)
        return $.isNumeric(valA) && $.isNumeric(valB) ? valA - valB : valA.localeCompare(valB)
    }
}
function getCellValue(row, index){ return $(row).children('td').eq(index).html() }
    </script>

<form id="q" action="datamatrix.php" method="post">   
    <!--update after exclusion -->
    
    
<?php



$tmpdir=$_SESSION["id"];
$filtrows=$_POST['products'];
$filtcols=$_POST['cols'];
//print_r($_POST['products']);
//file_put_contents($tmpdir.'remove_colnames.txt', print_r($_POST['products'], true));

$rowfilename = $tmpdir.'remove_rownames.txt';
$rowtext = "";
foreach($filtrows as $value)
{
    $rowtext .= $value."\n";  
}
$frowh = fopen($rowfilename, "w") or die("Could not open log file.");
fwrite($frowh, $rowtext);
fclose($frowh);

$colfilename = $tmpdir.'remove_colnames.txt';
$coltext = "";
foreach($filtcols as $value)
{
    $coltext .= $value."\n";  
}
$fcolh = fopen($colfilename, "w") or die("Could not open log file.");
fwrite($fcolh, $coltext);
fclose($fcolh);



//$color=array('rgb(103,0,31)','rgb(178,24,43)','rgb(214,96,77)','rgb(244,165,130)','rgb(253,219,199)','rgb(224,224,224)','rgb(186,186,186)','rgb(135,135,135)','rgb(77,77,77)','rgb(26,26,26)');
$color=array("#000000","#0000FF","00FFFF","#00FF00","#FFFF00","#7FFFD4","#FF0000","#FF9900","#FFFFFF","#00BFFF");
echo "<br>Color Codes:<br><br>";
echo '<table style="line-height: 1;" border="1" cellpadding="0" cellspacing="0"><tr>';
        
     for($i=0;$i< sizeof($color); $i++)
     {
        $colval=$i/10;
        echo "<td height='5px' width='10' bgcolor='".$color[$i]."'>".number_format($colval,1)."</td>";
     }
 echo  '</tr>';
 echo  '</table><br>';   
?>
    
     
    
    
<div id="tex" style="overflow-y: scroll; height:800px;overflow-x: scroll; width:940px;">
<table class="tftable" border="" cellspacing="0" cellpadding="0">



<?php
$arrText =  $_POST['formDoor'];
$excellfile=$_GET['excellfile'];
$excellsheet=$_GET['excellsheet'];
//echo("<b>Excell Sheet</b>:".$excellfile);
//print("<br>");
//print("<b>Excell File</b>:".$excellsheet);
//print("<br>");
//print("<br>");

exec('Rscript exclude.R '.$tmpdir);
exec('Rscript get_error_summary.R '.$tmpdir);

//echo('sudo Rscript get_error_summary.R '.$tmpdir);
//print("<br>");
//print("<br>");
//echo('sudo Rscript exclude.R '.$tmpdir);
//print("<br>");
//print("<br>");

if($_SESSION["gflag"]==0)
{
   $selected_group=$tmpdir.'selected_group.txt';
   //echo "<b>Group Selection:</b><br>";
   $myfile = fopen($selected_group, 'w');
   for  ($i=0;$i<sizeof($arrText);$i++)
   {
      fwrite($myfile,$arrText[$i]);
      //echo $arrText[$i]."<br>";
   }
   fclose($myfile);
}

$_SESSION["gflag"]=1;

//echo('sudo Rscript group_selection.R '.$excellfile.' "'.$excellsheet.'" '.$selected_group.' '.$tmpdir);
//exec('sudo Rscript group_selection.R '.$excellfile.' '.$excellsheet.' '.$selected_group.' '.$tmpdir);
//exec('sudo Rscript get_error_summary.R '.$tmpdir);
exec('Rscript group_selection.R '.$excellfile.' "'.$excellsheet.'" '.$selected_group.' '.$tmpdir);
exec('Rscript get_error_summary.R '.$tmpdir);
            
            $lines = file($tmpdir.'summary.txt');
            $file = fopen($tmpdir."group_selected.txt", "r");
            $nsumcolcount=0;
            $ncolcount=0;
            $title="";
            $i=0;
            $headers="";
            while(!feof($file)){
                  $line = fgets($file);
                  
                  if($line){
                     $headers=$line;
                     
                  }
                  
                  if($i==0){
                      print("\n");
                      #print($line);
                      $title=$line;
                      $header=explode("\t",$line);
                      $ncolcount=sizeof($header);
                      $sumheader=explode("\t",$lines[$i]);
                      $nsumcolcount=sizeof($sumheader);
                      echo "<thead><th width='20%' rowspan='2'></th>";
                      echo "<th colspan='".($ncolcount-1)."'>Experiments</th><th colspan='".($nsumcolcount+2)."'>Group Error Rate</th></tr>";
                      
                      for ($k=0;$k<sizeof($header);$k++)
                      {
                          echo "<th class='rotate'><span class='intact'>".trim($header[$k], '"')."</span></th>";
                      }
                     
                     
                      for ($k=0;$k<sizeof($sumheader);$k++)
                      {
                         
                      echo "<th data-sort='int' class='rotate'><span class='intact'>".trim($sumheader[$k], '"')."</span></th>";
                      }
                      echo "<th class='rotate'><span class='intact'>Filter</span></th></thead>";
                      echo "</tr>";
                  }else {

                      
                      $header=explode("\t",$line);
                      
                      //echo "</tr><td>".trim($header[0],'"')."</td>";
                      if($header[0])
                      {
                           echo"</tr><td>"."<div class='tooltip'>".$i."<span class='tooltiptext'>".trim($header[0],'"')."</span></div></td>";
                          //echo"</tr><td>".trim($header[0],'"')."</td>";
                      }
                      for ($k=1;$k<sizeof($header);$k++)
                      {
                          $num=rand(0, 6);
                          //$num=int($header[$k]);
                          //$col=$color[$num];
                          $val=0;
                          
                          if(!is_int($header[$k])){
                          $val=intval($header[$k]);}
                          $num=intval($header[$k]);
                          $col=$color[$num];
                          echo "<td bgcolor='".$col."'></td>";
                      }
                      
                      $summary=explode("\t",$lines[$i]);
                      if(sizeof($summary)>1){
                          
                           for ($m=1;$m<sizeof($summary);$m++)
                           {
                            
                               echo "<td align='center'>".trim($summary[$m],'"')."</td>";
                           }
                  
                           echo "<td><input type='checkbox' name='products[]'  class ='checkbox'  value='".$summary[0]."'></td>";
                      }
                      echo "<tr>";
                  }
                  $i++;
                 
            }
            fclose($file);
              #print($title);
              $summary=explode("\t",$title);
              
              if(sizeof($summary)> 0){
                  //print_r($summary);
              echo "<tr>";
              echo "<td>Filter</td>";
              for ($m=0;$m<sizeof($summary);$m++)
              {
                  echo "<td><input type='checkbox' name='cols[]'  class ='checkbox'  value='".$summary[$m]."'></td>";
              }
                
              echo"<td colspan='".($nsumcolcount+1)."'><input type='submit' value='Exclude' style='height:30px; width:70px;float: right;'></td>";

              echo "</tr>";
              }
            ?>   
        </table>
      
      </form>
<br><br>
<form action="normalize.php" method="post">
    <b>Impute:</b>
    <br>  
    <input type="radio" name="imputationtype" value="noimp">No Imputation<br>
<input type="radio" name="imputationtype" value="gmedian">Imputation by Median(group)<br>
<input type="radio" name="imputationtype" value="gmean">Imputaion by Mean(group)<br>
<input type="radio" name="imputationtype" value="omean">Imputation by Mean (overall)<br> 
<input type="radio" name="imputationtype" value="omedian">Imputation by Median (overall)<br>
<br>
<input type="submit" name="submit" value="Impute" />
    
</form>

<?php
frontendPageContentEnd();
frontendPageFooter("May 12, 2016");
frontendHTMLFooter();
?>
        
</body>
</html>
