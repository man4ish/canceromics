<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html>
    <head>
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

        <script src="sorttable.js"></script>

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
  width: 20px;
}
th.rotate > div > span {
  border-bottom: 1px solid #ccc;
  padding: 5px 10px;
}



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

        </head>
  </body>
    <form action="group_selection.php" method="get"> <button style="float: right;"><img src="img/arrow.jpeg"  height="20" width="20" /></button></form><br>

  <form id="q" action="datamatrix.php" method="post">  
 <?php 
 function numtohex($number)
{
  $hexnum=dechex($number);
  if(strlen(dechex($number))==1)
  {
    $hexnum="0".$hexnum;   
  }

  return $hexnum;   
}

function heatmapcolor()
{
//$number=8;
//$num=sprintf("%02X", $number);
//print($num."\n");
$colarray=array();

$max=255;

for ($i=0; $i<250;$i++)
{
     $k=255-($i);
     array_push($colarray,"##".numtohex($k).numtohex(0).numtohex(255));
     #print ("rgb(".$i*1.25.",0,255)\n");
}

for ($i=0; $i<250;$i++)
{    
    array_push($colarray,"##".numtohex(0).numtohex($i).numtohex(255-$i));
}

for ($i=0; $i<250;$i++)
{    
    array_push($colarray,"##".numtohex($i).numtohex(255).numtohex(0));
}

for ($i=0; $i<250;$i++)
{
    $k=255-($i);
    #print ("rgb(".$k.","."255".",0)\n");
    array_push($colarray,"##".numtohex(255).numtohex($k).numtohex(0)); 
}

 return $colarray;
}


$color=heatmapcolor();

//print_r($color);
//$color=array('rgb(103,0,31)','rgb(178,24,43)','rgb(214,96,77)','rgb(244,165,130)','rgb(253,219,199)','rgb(224,224,224)','rgb(186,186,186)','rgb(135,135,135)','rgb(77,77,77)','rgb(26,26,26)');
//$color=array("#000000","#0000FF","00FFFF","#00FF00","#FFFF00","#7FFFD4","#FF0000","#FF9900","#FFFFFF","#00BFFF");
echo "<br><b>Color Codes:<b><br><br>";
echo '<table style="line-height: .9;" border="0" cellpadding="0" cellspacing="0"><tr>';
for($i=0;$i<= 1000; $i++)
{
     if($i%100==0)
     {
         $k=$i/100;
         echo "<td height='2px' width='5px' ><font size='1'><b>$k</b></font></td>";
     }   
     else {
         echo "<td height='2px' width='5px' ></td>";
     }
}
 echo  '</tr><tr>';
for($i=0;$i<= 1000; $i++)
{
     if($i%100==0)
     {
         $k=$i/100;
         echo "<td style='padding: 2px;' height='2px' width='10px' ><font size='1'>|</font></td>";
     }   
     else {
         echo "<td height='2px' width='10px' ></td>";
     }
} 
 
 echo  '</tr></table>';
echo '<table style="line-height: 1;" border="1" cellpadding="0" cellspacing="0"><tr>';
        
     for($i=0;$i< 100; $i++)
     {
        $colval=$i*10;
        echo "<td height='10px' width='10px' bgcolor='".$color[$colval]."'></td>";
        //echo "bgcolor='".$color[$colval]."'<br>";
        //echo "<td height='15px' width='15px' bgcolor='".$color[$colval]."'>".$color[$colval]."</td>";
     }
 echo  '</tr>';
 echo  '</table><br>';   

?>
  
<div id="tex" style="overflow-y: scroll; height:800px;overflow-x: scroll; width:940px;">  
<table class="sortable" border="1" cellpadding="0" cellspacing="0">
<?php




$tmpdir="";
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
                      echo "<thead><tr><th width='1%' rowspan='2'></th>";
                      //echo "<th  width='80%' colspan='".($ncolcount)."'>Experiments</th><th  width='19%' colspan='".($nsumcolcount+2)."'>Group Error Rate</th></tr>";
                      //echo "<tr class='vert'>";
                   
                      
                      for ($k=0;$k<sizeof($header);$k++)
                      {
                          echo "<th class='rotate'><div><span>".trim($header[$k], '"')."</span></div></th>";
                      }
                     
                     
                      for ($k=0;$k<sizeof($sumheader);$k++)
                      {
                          if($k==1){
                                echo "<th class='rotate'><div><span>".trim($sumheader[$k], '"')."</span></div></th>";
                          } else {
                                echo "<th class='rotate'><div><span>".trim($sumheader[$k], '"')."</span></div></th>";
                          }
                          
                      }
                      echo "<th class='rotate'><div><span>Filter</span></div></th></tr></thead><tbody>";
                      
                  }else {

                      
                      $header=explode("\t",$line);
                      
                      //echo "</tr><td>".trim($header[0],'"')."</td>";
                      if($header[0])
                      {
                           echo"<tr><td width='1%'>"."<div class='tooltip'>".$i."<span class='tooltiptext'>".trim($header[0],'"')."</span></div></td>";
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
                          //$num=intval($header[$k]);
                           $num=intval($header[$k]*100);
                          //$col=$color[$num];
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
                      echo "</tr>";
                  }
                  $i++;
                 
            }
            fclose($file);
              #print($title);
              $summary=explode("\t",$title);
              
              if(sizeof($summary)> 0){
                  //print_r($summary);
              echo "</tbody><tfoot>";
              echo "<td>Filter</td>";
              for ($m=0;$m<sizeof($summary);$m++)
              {
                  echo "<td><input type='checkbox' name='cols[]'  class ='checkbox'  value='".$summary[$m]."'></td>";
              } 
              echo"<td colspan='".($nsumcolcount+1)."'><input type='submit' value='Exclude' style='height:30px; width:70px;float: right;'></td>";
              echo "</tr></tfoot>";
              }
            ?>   
        </table>
    </div>
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
<input type="submit" name="submit" value="Impute" style="float: right;" />
    
</form>
    </body>
</html>

