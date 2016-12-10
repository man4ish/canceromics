<!DOCTYPE html>
<html>
  <head>
   <meta name="viewport" content="width=device-width, initial-scale=1">
   <link rel="stylesheet" href="http://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.4.0/css/font-awesome.min.css"> 
    <meta http-equiv="cache-control" content="max-age=0" />
    <meta http-equiv="cache-control" content="no-cache" />
    <meta http-equiv="expires" content="0" />
    <meta http-equiv="expires" content="Tue, 01 Jan 1980 1:00:00 GMT" />
    <meta http-equiv="pragma" content="no-cache" />
    <META HTTP-EQUIV="CACHE-CONTROL" CONTENT="NO-CACHE">
    <META HTTP-EQUIV="EXPIRES" CONTENT="Mon, 22 Jul 2002 11:12:01 GMT">
    <link rel="stylesheet" href="style.css">
  
    <style>
     p.spinner {
       margin-top: 0cm;
     }    
    </style>

   <style>
   .button {
   border: 0px solid #0a3c59;
   background: #dae4eb;
   background: -webkit-gradient(linear, left top, left bottom, from(#e7ecf0), to(#dae4eb));
   background: -webkit-linear-gradient(top, #e7ecf0, #dae4eb);
   background: -moz-linear-gradient(top, #e7ecf0, #dae4eb);
   background: -ms-linear-gradient(top, #e7ecf0, #dae4eb);
   background: -o-linear-gradient(top, #e7ecf0, #dae4eb);
   background-image: -ms-linear-gradient(top, #e7ecf0 0%, #dae4eb 100%);
   padding: 2px 4px;
   -webkit-border-radius: 0px;
   -moz-border-radius: 0px;
   border-radius: 0px;
   -webkit-box-shadow: rgba(255,255,255,0.4) 0 1px 0, inset rgba(255,255,255,0.4) 0 1px 0;
   -moz-box-shadow: rgba(255,255,255,0.4) 0 1px 0, inset rgba(255,255,255,0.4) 0 1px 0;
   box-shadow: rgba(255,255,255,0.4) 0 1px 0, inset rgba(255,255,255,0.4) 0 1px 0;
   text-shadow: #ebf1f5 0 1px 0;
   color: #000000;
   font-size: 14px;
   font-family: helvetica, serif;
   text-decoration: none;
   vertical-align: middle;
   }

   .button:hover {
   border: 0px solid #0a3c59;
   text-shadow: #f5f5f5 0 1px 0;
   background: #e7ecf0;
   background: -webkit-gradient(linear, left top, left bottom, from(#e2e7eb), to(#e7ecf0));
   background: -webkit-linear-gradient(top, #e2e7eb, #e7ecf0);
   background: -moz-linear-gradient(top, #e2e7eb, #e7ecf0);
   background: -ms-linear-gradient(top, #e2e7eb, #e7ecf0);
   background: -o-linear-gradient(top, #e2e7eb, #e7ecf0);
   background-image: -ms-linear-gradient(top, #e2e7eb 0%, #e7ecf0 100%);
   color: #fff;
   }

   .button:active {
   text-shadow: #5197c2 0 1px 0;
   border: 0px solid #0a3c59;
   background: #65a9d7;
   background: -webkit-gradient(linear, left top, left bottom, from(#3e779d), to(#e7ecf0));
   background: -webkit-linear-gradient(top, #3e779d, #65a9d7);
   background: -moz-linear-gradient(top, #3e779d, #65a9d7);
   background: -ms-linear-gradient(top, #3e779d, #65a9d7);
   background: -o-linear-gradient(top, #3e779d, #65a9d7);
   background-image: -ms-linear-gradient(top, #3e779d 0%, #65a9d7 100%);
   color: #fff;
   }

   .alert-box {
   padding: 15px;
   margin-bottom: 20px;
   margin-left: 320px;
   margin-right:320px;
   border: 1px solid transparent;
   border-radius: 4px;  
   }

   .success {
   color: #D80000 ;
   background-color: #E8E8E8;
   border-color: #FFFFFF ;
   display: none;
   }

    .message {
   color: #D80000 ;
   background-color: #E8E8E8;
   border-color: #FFFFFF ;
   display: none;
   }

   .failure {
   color: #D80000 ;
   background-color: #E8E8E8;
   border-color: #FFFFFF ;
   display: none;
   }

  </style>    
  <script type='text/javascript'>
     $( "div.failure" ).fadeIn( 300 ).delay( 1500 ).fadeOut( 400 );    
      var range;    
      var chr;
      var genomerelease;
      var referenceset; 
      var population;
      var annotation;
      var start;
      var stop;
      var flag;
      var jobid;
      var zoomlevel=0;
      
     

     $(function() {
	var chart;
	var options = {
		chart : {
			type : 'polygon',
			renderTo : 'container',
		        zoomType:'',
                        marginLeft: 20,
                        marginRightt: 20,
                        plotBorderWidth: 1
		},
		title : {
			text : ''
		},
                credits: {
                        enabled: false
                }, 
		yAxis : {
			title : false,
			gridLineWidth : 0,
			lineWidth : 0,
			labels : {
				enabled : false
			}                
		},
                xAxis: {
            		title: true,
            		gridLineWidth:0,
            		lineWidth:1,
            		labels:{
                		enabled: true
            		},
             		plotLines: [{
                	value:'',
                	width: 10,
                	color: 'green'}]
            
        	}, 
	       plotOptions : {
			series : {
                                lineWidth : '0px',
				lineColor : 'black',
                                dashStyle: 'solid' 
			},  
                        areaspline: {
                             fillOpacity: 0.5
                        }
		},
               
	       series : [ {} ],
               tooltip: {
                   formatter :function (){ 
                         if(this.series.options.someText)
                              return  this.series.options.someText;
                         if(this.point.mousevertext)
                             return  this.point.mousevertext;
                   }
             }                
	};




       function myFunction() 
       {                      
                 $("#container").html('<div id="container" style="width: 850px; height: 850px; margin: 0px"><img src="frontend/js/tmp/polygon.png?c=' + new Date().getTime() + '" style="width: 850px; height: 850px; margin: 0px"></div>');
       } 

     var title_text="";
     var plotLineVal=0;
     $.getJSON('frontend/js/tmp/title.json', function(title_data) {
           plotLineVal = parseInt(title_data.value);
           title_text = title_data.text;
     });
 

      $("#generate_ldheat_map").click(function() {
      
             var par = document.getElementById("searchbox").value;
             if (par == null || par == "") {
                  alert("Chr/Rsid/Gene Name must be filled out");
                  return false;
              }
      
              if(par[1]==':' || par[2]==':') 
              {
                  //alert(par);
                  var res = par.split(":"); 
                  chr = res[0];
                  
                 var coordinates= (res[1]).split("-");
                 
                  start = coordinates[0];
                  stop = coordinates[1];
                  
                  range = (parseInt(stop)-parseInt(start));
               
                  if(range < 0)
                  { 
                     alert("Pls check the input range. Stop is less than Start!\n");
                     return false;
                  }
                  if(isNaN(chr)) {
					if (chr =='X' || chr == 'Y') {
					
			        } else {
						alert("Pls enter valid value of chromosome\n");
					    return false;
					}
			  } else if((chr < 1 || chr > 22) ) {
						alert("Pls enter valid value of chromosome\n");
						return false;
			  } 
			  
			  if(chr == 1 && stop > 249250621) {
				  alert("Max range value for chr1 is 249250621!\n");
                  return false;
			  } else if(chr == 2 && stop > 243199373) {
				  alert("Max range value for chr2 is 243199373!\n");
                  return false;
			  } else if(chr == 3 && stop > 198022430) {
				  alert("Max range value for chr3 is 198022430!\n");
                  return false;
			  } else if(chr == 4 && stop > 191154276) {
				  alert("Max range value for chr4 is 191154276!\n");
                  return false;
			  } else if(chr == 5 && stop > 180915260	) {
				  alert("Max range value for chr5 is 180915260!\n");
                  return false;
			  } else if(chr == 6 && stop > 171115067) {
				  alert("Max range value for chr6 is 171115067!\n");
                  return false;
			  } else if(chr == 7 && stop > 159138663) {
				  alert("Max range value for chr7 is 159138663!\n");
                  return false;
			  } else if(chr == 8 && stop > 146364022) {
				  alert("Max range value for chr8 is 146364022!\n");
                  return false;
			  } else if(chr == 9 && stop > 141213431	) {
				  alert("Max range value for chr9 is 141213431!\n");
                  return false;
			  } else if(chr == 10 && stop > 135534747) {
				  alert("Max range value for chr10 is 135534747!\n");
                  return false;
			  } else if(chr == 11 && stop > 135006516) {
				  alert("Max range value for chr11 is 135006516!\n");
                  return false;
			  } else if(chr == 12 && stop > 133851895) {
				  alert("Max range value for chr12 is 133851895!\n");
                  return false;
			  } else if(chr == 13 && stop > 115169878) {
				  alert("Max range value for chr13 is 115169878!\n");
                  return false;
			  } else if(chr == 14 && stop > 107349540) {
				  alert("Max range value for chr14 is 107349540!\n");
                  return false;
			  } else if(chr == 15 && stop > 102531392) {
				  alert("Max range value for chr15 is 102531392!\n");
                  return false;
			  } else if(chr == 16 && stop > 90354753) {
				  alert("Max range value for chr16 is 90354753!\n");
                  return false;
			  } else if(chr == 17 && stop > 81195210) {
				  alert("Max range value for chr17 is 81195210!\n");
                  return false;
			  } else if(chr == 18 && stop > 78077248) {
				  alert("Max range value for chr18 is 78077248!\n");
                  return false;
			  } else if(chr == 19 && stop > 59128983) {
				  alert("Max range value for chr19 is 59128983!\n");
                  return false;
			  } else if(chr == 20 && stop > 63025520) {
				  alert("Max range value for chr20 is 63025520!\n");
                  return false;
			  } else if(chr == 21 && stop > 48129895) {
				  alert("Max range value for chr21 is 48129895!\n");
                  return false;
			  } else if(chr == 22 && stop > 51304566) {
				  alert("Max range value for chr22 is 51304566!\n");
                  return false;
			  } else if(chr == "X" && stop > 155270560) {
				  alert("Max range value for chrX is 155270560!\n");
                  return false;
			  } else if(chr == "Y" && stop > 59373566) {
				  alert("Max range value for chrY is 59373566!\n");
                  return false;
			  } 
             
                  if (range > 10000){
                    if(range >50000){
                      stop=parseInt(start)+50000;
                      range=50000;
                    } else {
                      range = (parseInt(stop)-parseInt(start));
                    }
                    
                    $("#container").html("<p class='spinner'><center><i class='fa fa-circle-o-notch fa-spin' style='font-size:50px'></i></center></p>"); 
                                      
                    $.get( "backend/snipaNavigator2.php?recordID=0&chr="+chr+"&genomerelease="+genomerelease+"&annotation="+annotation+"&referenceset="+referenceset+"&population="+population+"&jobid="+jobid+"&start="+start+"&stop="+stop, function( data ) {})        
                    setTimeout(myFunction, 3000);
	            return false;
                  }

                 //alert(start+" "+stop);
                 $("#container").html("<p class='spinner'><center><i class='fa fa-circle-o-notch fa-spin' style='font-size:50px'></center></i></p>");
                 //alert("backend/snipaNavigator.php?recordID=0&chr="+chr+"&genomerelease="+genomerelease+"&annotation="+annotation+"&referenceset="+referenceset+"&population="+population+"&jobid="+jobid+"&start="+start+"&stop="+stop);

		$.get( "backend/snipaNavigator.php?recordID=0&chr="+chr+"&genomerelease="+genomerelease+"&annotation="+annotation+"&referenceset="+referenceset+"&population="+population+"&jobid="+jobid+"&start="+start+"&stop="+stop, function( data ) {
                        $.getJSON( 'frontend/js/tmp/data0.json' )
                        .done(function( data ) {
                            options.title.text=title_text;
                            options.series = data;
				chart = new Highcharts.Chart(options);
               			chart.renderer.rect(40, 65, 10, 10).attr({
                        fill: 'white',
                        stroke: 'black',
                        'stroke-width': 1
                        }).add();
                        chart.renderer.text("not in LD", 60, 75).add();
                        chart.renderer.rect(40, 85, 10, 10).attr({
                        fill: '#ffb3b3',
                        stroke: 'black',
                        'stroke-width': 1
                        }).add();
                        chart.renderer.text("LD r2>=0.05", 60, 95).add();
                        chart.renderer.rect(40, 105, 10, 10).attr({
                        fill: '#ff9999',
                        stroke: 'black',
                        'stroke-width': 1
                        }).add();
                        chart.renderer.text("LD r2 >=0.2", 60, 115).add(); 
                        chart.renderer.rect(40, 125, 10, 10).attr({
                        fill: '#ff8080',
                        stroke: 'black',
                       'stroke-width': 1
                       }).add();
                       chart.renderer.text("LD r2 >=0.5", 60, 135).add();  
                       chart.renderer.rect(40, 145, 10, 10).attr({
                        fill: '#ff3333',
                        stroke: 'black',
                       'stroke-width': 1
                       }).add();
                       chart.renderer.text("LD r2 >=0.8", 60, 155).add(); 
                        })
                        .fail(function( jqxhr, textStatus, error ) {
                            $("#container").html("<div style='style:margin:0 auto'><center><font size='5'>No LD data present</font></center></div>") ;
                            return false;
                        });
              		
	     	});
                             
                  return false;               
              }     
      
     });

    /* var title_text="";
     var plotLineVal=0;
     $.getJSON('frontend/js/tmp/title.json', function(title_data) { 
           plotLineVal = parseInt(title_data.value);
           title_text = title_data.text;                    
     });*/

    
    
      var range=0;  
      var x = "<?php echo $_GET['pid']; ?>";
      var jsonfilepath = "/home/metabolomics/snipa/web/tmpdata/"+x+"/data.json";
      var svgfilepath = "/home/metabolomics/snipa/web/tmpdata/"+x+"/polygon.png";
      var title="/home/metabolomics/snipa/web/tmpdata/"+x+"/title.json";
      var summaryfile = "tmpdata/"+x+"/summary.log";
      $.get(summaryfile, function(data) {  
               //alert(data);                            
      	       var res = data.split("\t");
  
               genomerelease = res[0];
               referenceset = res[1];
               population = res[2];
               annotation = res[3];
               
               jobid = res[5];
               //alert(x+"--"+jobid);
               start = res[6];
               stop = res[7];
               chr = res[8];   
               
               //alert(jsonfilepath);  
               //alert(svgfilepath);          	
	
      	       range = (parseInt(stop)-parseInt(start));
               zoomlevel=range/10;
               
               if (range > 10000){
                    //$( "div.success" ).fadeIn( 300 ).delay( 1500 ).fadeOut( 400 ); 
                    $("#alert-box success").html('<div class="alert-box success">Successful Alert !!!</div>');                    
                    $("#container").html('<div  id="container" style="width: 850px; height: 850px;margin: 0px" > <img src ="frontend/js/tmp/polygon.png" style="width: 850px; height: 850px; margin: 0px"></div>');
	            return false;
               }
             
        
               
               $("#container").html("<p class='spinner'><center><i class='fa fa-circle-o-notch fa-spin' style='font-size:50px'></center></i></p>"); 
               $.getJSON('frontend/js/tmp/data0.json', function(data) {
               
               options.series=data; 
               options.title.text=title_text;
               //alert("Yahoo "+title_text);
               options.xAxis.plotLines.push({"value":plotLineVal, width: 10,color: 'green'});
               var chart = new Highcharts.Chart(options);
               
                 
               chart.renderer.rect(40, 65, 10, 10).attr({
                        fill: 'white',
                        stroke: 'black',
                        'stroke-width': 1
                        }).add();
                        chart.renderer.text("not in LD", 60, 75).add();
                        chart.renderer.rect(40, 85, 10, 10).attr({
                        fill: '#ffb3b3',
                        stroke: 'black',
                        'stroke-width': 1
                        }).add();
                        chart.renderer.text("LD r2>=0.05", 60, 95).add();
                        chart.renderer.rect(40, 105, 10, 10).attr({
                        fill: '#ff9999',
                        stroke: 'black',
                        'stroke-width': 1
                        }).add();
                        chart.renderer.text("LD r2 >=0.2", 60, 115).add(); 
                        chart.renderer.rect(40, 125, 10, 10).attr({
                        fill: '#ff8080',
                        stroke: 'black',
                       'stroke-width': 1
                       }).add();
                       chart.renderer.text("LD r2 >=0.5", 60, 135).add();  
                       chart.renderer.rect(40, 145, 10, 10).attr({
                        fill: '#ff3333',
                        stroke: 'black',
                       'stroke-width': 1
                       }).add();
                       chart.renderer.text("LD r2 >=0.8", 60, 155).add();               
               }).fail(function() {
                     $("#container").html("<div style='style:margin:0 auto'><center><font size='5'>No LD data present</font></center></div>") ;
                     return false;
               });
      });
      

       $("#plus").click(function() {
                //alert(zoomlevel);
                //alert(start)
                start=parseInt(start)-zoomlevel;
                //alert(start)
                //alert(stop)
                stop=parseInt(stop)+zoomlevel; 
                //alert(stop)
		//start=parseInt(start)-2000;
                //stop=parseInt(stop)+2000;
                range = stop-start;
                //alert(start+"-"+stop+"-"+range);
                //if(range <= 10000 && ((range+4000)>10000))
                if ((range+4000) > 10000)
                {
                    

                    if (range > 10000)
                    {
                        if(range >50000){
                           stop=parseInt(start)+50000;
                           range=50000;
                        } else {
                           range = (parseInt(stop)-parseInt(start));
                        }
                    
                      $("#container").html("<p class='spinner'><center><i class='fa fa-circle-o-notch fa-spin' style='font-size:50px'></center></i></p>"); 
                      $.get( "backend/snipaNavigator2.php?recordID=0&chr="+chr+"&genomerelease="+genomerelease+"&annotation="+annotation+"&referenceset="+referenceset+"&population="+population+"&jobid="+jobid+"&start="+start+"&stop="+stop, function( data ) {})        
                      setTimeout(myFunction, 3000);
	              return false;
                    }
                    $("div.success").fadeIn( 300 ).delay( 1500 ).fadeOut( 400 );
                    $("#container").html('<div  id="container" style="width: 850px; height: 850px;margin: 0px" > <img src ="frontend/js/tmp/polygon.png" style="width: 850px; height: 850px; margin: 0px"></div>');
                       return false;
                }
                
                //start=parseInt(start)-2000;
                //stop=parseInt(stop)+2000;
                //range = stop-start;
              
		/*if (range > 10000){
                    $( "div.success" ).fadeIn( 300 ).delay( 1500 ).fadeOut( 400 );
                    $("#container").html('<div  id="container" style="width: 850px; height: 850px;margin: 0px" > <img src ="frontend/js/tmp/polygon.png" style="width: 850px; height: 850px; margin: 0px"></div>');;
	            return false;
                }*/
                $("#container").html("<p class='spinner'><center><i class='fa fa-circle-o-notch fa-spin' style='font-size:50px'></center></i></p>"); 
                //alert(start+"-"+stop+"-"+range);
               
                
                //alert("backend/snipaNavigator.php?recordID=-2&chr="+chr+"&genomerelease="+genomerelease+"&annotation="+annotation+"&referenceset="+referenceset+"&population="+population+"&jobid="+jobid+"&start="+start+"&stop="+stop);
		$.get( "backend/snipaNavigator.php?recordID=0&chr="+chr+"&genomerelease="+genomerelease+"&annotation="+annotation+"&referenceset="+referenceset+"&population="+population+"&jobid="+jobid+"&start="+start+"&stop="+stop, function( data ) {
               $.getJSON('frontend/js/tmp/data0.json', function(data) {
                        options.title.text=title_text;
			options.series = data;
			chart = new Highcharts.Chart(options);
                        chart.renderer.rect(40, 65, 10, 10).attr({
                        fill: 'white',
                        stroke: 'black',
                        'stroke-width': 1
                        }).add();
                        chart.renderer.text("not in LD", 60, 75).add();
                        chart.renderer.rect(40, 85, 10, 10).attr({
                        fill: '#ffb3b3',
                        stroke: 'black',
                        'stroke-width': 1
                        }).add();
                        chart.renderer.text("LD r2>=0.05", 60, 95).add();
                        chart.renderer.rect(40, 105, 10, 10).attr({
                        fill: '#ff9999',
                        stroke: 'black',
                        'stroke-width': 1
                        }).add();
                        chart.renderer.text("LD r2 >=0.2", 60, 115).add(); 
                        chart.renderer.rect(40, 125, 10, 10).attr({
                        fill: '#ff8080',
                        stroke: 'black',
                       'stroke-width': 1
                       }).add();
                       chart.renderer.text("LD r2 >=0.5", 60, 135).add();  
                       chart.renderer.rect(40, 145, 10, 10).attr({
                        fill: '#ff3333',
                        stroke: 'black',
                       'stroke-width': 1
                       }).add();
                       chart.renderer.text("LD r2 >=0.8", 60, 155).add();  
                       }).fail(function() {
                        $("#container").html("<div style='style:margin:0 auto'><center><font size='5'>No LD data present</font></center></div>") ;
                     return false;
               });
	   });

	});

	$("#minus").click(function() {
                //alert(zoomlevel);
                //alert(start+"-"+stop+"-"+range);
	        start=parseInt(start)+zoomlevel;
                stop=parseInt(stop)-zoomlevel;


                //start=parseInt(start)+2000;
                //stop=parseInt(stop)-2000;
                range=stop-start;
               
		if (range > 10000)
                {
                      //alert(range+" Image too large to be loaded\n"); 
                      //$( "div.success" ).fadeIn( 300 ).delay( 1500 ).fadeOut( 400 ); 
                      $("#container").html("<p class='spinner'><center><i class='fa fa-circle-o-notch fa-spin' style='font-size:50px'></i></center></p>");
                  
                      $.get("backend/snipaNavigator2.php?recordID=0&chr="+chr+"&genomerelease="+genomerelease+"&annotation="+annotation+"&referenceset="+referenceset+"&population="+population+"&jobid="+jobid+"&start="+start+"&stop="+stop, function(data){});        
                      setTimeout(myFunction, 3000); 
                      //$("#container").html('<div  id="container" style="width: 850px; height: 850px;margin: 0px" > <img src ="frontend/js/tmp/polygon.png" style="width: 850px; height: 850px; margin: 0px"></div>');
	              return false;
                }
             
                if(range <= 0 )
                {
                   start=parseInt(start)-zoomlevel;
                   stop=parseInt(stop)+zoomlevel;
                   range=stop-start;
                   $( "div.message" ).fadeIn( 300 ).delay( 1500 ).fadeOut( 400 );              
                   return false;
                } 

                $("#container").html("<p class='spinner'><center><i class='fa fa-circle-o-notch fa-spin' style='font-size:50px'></center></i></p>"); 
                //stop=parseInt(start)+parseInt(range);
                
                
                //alert("backend/snipaNavigator.php?recordID=-2&chr="+chr+"&genomerelease="+genomerelease+"&annotation="+annotation+"&referenceset="+referenceset+"&population="+population+"&jobid="+jobid+"&start="+start+"&stop="+stop);
		$.get( "backend/snipaNavigator.php?recordID=0&chr="+chr+"&genomerelease="+genomerelease+"&annotation="+annotation+"&referenceset="+referenceset+"&population="+population+"&jobid="+jobid+"&start="+start+"&stop="+stop, function( data ) {
                        
              var jqxhr = $.getJSON( "frontend/js/tmp/data0.json", function(data) {
                 options.title.text=title_text;
                 options.series = data;
		chart = new Highcharts.Chart(options);
              chart.renderer.rect(40, 65, 10, 10).attr({
                        fill: 'white',
                        stroke: 'black',
                        'stroke-width': 1
                        }).add();
                        chart.renderer.text("not in LD", 60, 75).add();
                        chart.renderer.rect(40, 85, 10, 10).attr({
                        fill: '#ffb3b3',
                        stroke: 'black',
                        'stroke-width': 1
                        }).add();
                        chart.renderer.text("LD r2>=0.05", 60, 95).add();
                        chart.renderer.rect(40, 105, 10, 10).attr({
                        fill: '#ff9999',
                        stroke: 'black',
                        'stroke-width': 1
                        }).add();
                        chart.renderer.text("LD r2 >=0.2", 60, 115).add(); 
                        chart.renderer.rect(40, 125, 10, 10).attr({
                        fill: '#ff8080',
                        stroke: 'black',
                       'stroke-width': 1
                       }).add();
                       chart.renderer.text("LD r2 >=0.5", 60, 135).add();  
                       chart.renderer.rect(40, 145, 10, 10).attr({
                        fill: '#ff3333',
                        stroke: 'black',
                       'stroke-width': 1
                       }).add();
                       chart.renderer.text("LD r2 >=0.8", 60, 155).add(); 
              })

              .fail(function() {
                 $("#container").html("<div style='style:margin:0 auto'><center><font size='5'>No LD data present</font></center></div>") ;
                 return false;
              })                       
	   });
           //alert(start+"-"+stop+"-"+range);
	});


       $("#back").click(function() {
          //alert(start+"-"+stop+"-"+range); 
          start=parseInt(start)-2000;
          stop=parseInt(stop)-2000;        
          //range -= 2000;
          if(start <= 0)
          {
                   alert(range+" It cannot be navigated further\n");
                   return false;
          }
 
          if (range > 10000)
          {                   
                    range = stop-start;

                    if (range > 10000)
                    {
                        if(range >50000){
                           stop=parseInt(start)+50000;
                           range=50000;
                        } else {
                           range = (parseInt(stop)-parseInt(start));
                        }
                    
                    $("#container").html("<p class='spinner'><center><i class='fa fa-circle-o-notch fa-spin' style='font-size:50px'></center></i></p>"); 
                    $.get( "backend/snipaNavigator2.php?recordID=0&chr="+chr+"&genomerelease="+genomerelease+"&annotation="+annotation+"&referenceset="+referenceset+"&population="+population+"&jobid="+jobid+"&start="+start+"&stop="+stop, function( data ) {})        
                        setTimeout(myFunction, 3000);
	                return false;
                    }
                    $( "div.success" ).fadeIn( 300 ).delay( 1500 ).fadeOut( 400 );
                    $("#container").html('<div  id="container" style="width: 850px; height: 850px;margin: 0px" > <img src ="frontend/js/tmp/polygon.png" style="width: 850px; height: 850px; margin: 0px"></div>');
                       return false;
          }
              
          //alert(start+"-"+stop+"-"+range);
          $("#container").html("<p class='spinner'><center><i class='fa fa-circle-o-notch fa-spin' style='font-size:50px'></center></i></p>");          
             
              	$.get( "backend/snipaNavigator.php?recordID=0&chr="+chr+"&genomerelease="+genomerelease+"&annotation="+annotation+"&referenceset="+referenceset+"&population="+population+"&jobid="+jobid+"&start="+start+"&stop="+stop, function( data ) {
               $.getJSON('frontend/js/tmp/data0.json', function(data) {
               	 options.series=data; 
                 options.title.text=title_text;
               	 var chart = new Highcharts.Chart(options);
                 chart.renderer.rect(40, 65, 10, 10).attr({
                        fill: 'white',
                        stroke: 'black',
                        'stroke-width': 1
                        }).add();
                        chart.renderer.text("not in LD", 60, 75).add();
                        chart.renderer.rect(40, 85, 10, 10).attr({
                        fill: '#ffb3b3',
                        stroke: 'black',
                        'stroke-width': 1
                        }).add();
                        chart.renderer.text("LD r2>=0.05", 60, 95).add();
                        chart.renderer.rect(40, 105, 10, 10).attr({
                        fill: '#ff9999',
                        stroke: 'black',
                        'stroke-width': 1
                        }).add();
                        chart.renderer.text("LD r2 >=0.2", 60, 115).add(); 
                        chart.renderer.rect(40, 125, 10, 10).attr({
                        fill: '#ff8080',
                        stroke: 'black',
                       'stroke-width': 1
                       }).add();
                       chart.renderer.text("LD r2 >=0.5", 60, 135).add();  
                       chart.renderer.rect(40, 145, 10, 10).attr({
                        fill: '#ff3333',
                        stroke: 'black',
                       'stroke-width': 1
                       }).add();
                       chart.renderer.text("LD r2 >=0.8", 60, 155).add();   
           	 }).fail(function() {
                     $("#container").html("<div style='style:margin:0 auto'><center><font size='5'>No LD data present</font></center></div>") ;
                     return false;
                  });
           });
        });

       $("#forward").click(function() {
          //range += 2000;
          
           if (range > 10000)
           {
                    start=parseInt(start)+2000;
                    stop=parseInt(stop)+2000;
                    range = stop-start;

                    if (range > 10000)
                    {
                        if(range >50000){
                           stop=parseInt(start)+50000;
                           range=50000;
                        } else {
                           range = (parseInt(stop)-parseInt(start));
                        }
                    
                      $("#container").html("<p class='spinner'><center><i class='fa fa-circle-o-notch fa-spin' style='font-size:50px'></center></i></p>"); 
                      $.get( "backend/snipaNavigator2.php?recordID=0&chr="+chr+"&genomerelease="+genomerelease+"&annotation="+annotation+"&referenceset="+referenceset+"&population="+population+"&jobid="+jobid+"&start="+start+"&stop="+stop, function( data ) {})        
                      setTimeout(myFunction, 3000);
	              return false;
                    }
                    $( "div.success" ).fadeIn( 300 ).delay( 1500 ).fadeOut( 400 );
                    $("#container").html('<div  id="container" style="width: 850px; height: 850px;margin: 0px" > <img src ="frontend/js/tmp/polygon.png" style="width: 850px; height: 850px; margin: 0px"></div>');
                       return false;
          }

          $("#container").html("<p class='spinner'><center><i class='fa fa-circle-o-notch fa-spin' style='font-size:50px'></center></i></p>");
          start=parseInt(start)+2000;
          stop=parseInt(stop)+2000;  
          //alert("backend/snipaNavigator.php?recordID=-2&chr="+chr+"&genomerelease="+genomerelease+"&annotation="+annotation+"&referenceset="+referenceset+"&population="+population+"&jobid="+jobid+"&start="+start+"&stop="+stop);
          //stop=parseInt(stop)+2000;
          $.get( "backend/snipaNavigator.php?recordID=0&chr="+chr+"&genomerelease="+genomerelease+"&annotation="+annotation+"&referenceset="+referenceset+"&population="+population+"&jobid="+jobid+"&start="+start+"&stop="+stop, function( data ) {
           $.getJSON('frontend/js/tmp/data0.json', function(data) {
               options.title.text=title_text;
               options.series=data;
               var chart = new Highcharts.Chart(options);
               chart.renderer.rect(40, 65, 10, 10).attr({
                        fill: 'white',
                        stroke: 'black',
                        'stroke-width': 1
                        }).add();
                        chart.renderer.text("not in LD", 60, 75).add();
                        chart.renderer.rect(40, 85, 10, 10).attr({
                        fill: '#ffb3b3',
                        stroke: 'black',
                        'stroke-width': 1
                        }).add();
                        chart.renderer.text("LD r2>=0.05", 60, 95).add();
                        chart.renderer.rect(40, 105, 10, 10).attr({
                        fill: '#ff9999',
                        stroke: 'black',
                        'stroke-width': 1
                        }).add();
                        chart.renderer.text("LD r2 >=0.2", 60, 115).add(); 
                        chart.renderer.rect(40, 125, 10, 10).attr({
                        fill: '#ff8080',
                        stroke: 'black',
                       'stroke-width': 1
                       }).add();
                       chart.renderer.text("LD r2 >=0.5", 60, 135).add();  
                       chart.renderer.rect(40, 145, 10, 10).attr({
                        fill: '#ff3333',
                        stroke: 'black',
                       'stroke-width': 1
                       }).add();
                       chart.renderer.text("LD r2 >=0.8", 60, 155).add();  
           }).fail(function() {
                     $("#container").html("<div style='style:margin:0 auto'><center><font size='5'>No LD data present</font></center></div>") ;
                     return false;
            });
          });
       }); 
    });
   </script>
   </head>
   <body>
   <?php 
   //echo $_GET["pid"];
   ?>
   <!-- <table width="100%" cellspacing="0" cellpadding="0">
     <tr><td width="45%"></td><td width="25%">Range Search (ex. 1:2000-3000000)</td>
    <td>                                                        <input type="text" size=14 id="searchbox" /><input id ="generate_ldheat_map" type="submit"></td></tr></table>-->
         <div class="alert-box success">Image too large to be loaded</div>
         <div class="alert-box message">Cannot be further zoomed</div>
         <div  id="container" style="width: 850px; height: 850px;margin: 0px" > <img src ="frontend/js/tmp/polygon.png" style="width: 850px; height: 850px; margin: 0px"></div>
   
   <table width="100%" cellpadding="0" cellspacing="0">
         <tr>   
             <td width="20%"><a href='#' class='button' id ="back" width="48" height="48">< 2 kb </a></td>
             <td width="20%"><a href='#' class='button' id ="plus">zoom out</a></td>
             <td width="20%"></td>
             <td width="20%" align="right"><a href='#' class='button' id ="minus" >zoom in</a></td>
             <td width="20%" align ="right"><a href='#' class='button' id ="forward"> 2 kb ></a></td>
        </tr>
   </table>
 </body>
</html>
