<!DOCTYPE html>
<html>
<head>
  <meta http-equiv="content-type" content="text/html; charset=UTF-8">
  <meta name="robots" content="noindex, nofollow">
  <meta name="googlebot" content="noindex, nofollow">
  <script type="text/javascript" src="https://code.jquery.com/jquery-1.9.1.js"></script>
  <link rel="stylesheet" type="text/css" href="/css/result-light.css">
  <style type="text/css">  
  </style>
  <title></title>
<script type='text/javascript'>//<![CDATA[



$(function() {
  var options = {
        chart: {
            renderTo: 'container2',
            type: 'boxplot',
            zoomType:'x',
            borderWidth: 1,
            plotBorderWidth: 1,
            marginLeft: 100,
            marginRight: 100
        },

        title: {
            text: 'Box Plot By Experiment'
        },

        legend: {
            enabled: false
        },

        xAxis: {
            categories: ['1', '2', '3', '4', '5'],
            title: {
                text: 'Experiments'
            }
        },

       yAxis: [{
    	height: '45%',
          plotLines: [{
                    value: 0,
                    color: 'green',
                    dashStyle: 'solid',
                    width: 2,
                    label: {
                        text: '----------------------------------------------------------------Before Normalization-----------------------------------------------------'
                    }
                }],
    
    },{offset:0,
    top: '55%',
    height: '45%',
     plotLines: [{
                    value: 0,
                    color: 'red',
                    dashStyle: 'solid',
                    width: 2,
                    label: {
                        text: '-----------------------------------------------------------------After Normalization-----------------------------------------------------'
                    }
                }]
    }],

        series: [{}]

    };
   
  jsonfile='web/tmpdata/'+'<?php echo $_SESSION["tmpid"]; ?>'+'/tmpdata_col.json';
  alert(jsonfile);
  $.getJSON(jsonfile, function(data) {
  //alert(data);
  options.series = data;
  var chart = new Highcharts.Chart(options);
  });

});
//]]> 

</script>

  
</head>

<body>

<script src="https://code.highcharts.com/highcharts.js"></script>
<script src="https://code.highcharts.com/highcharts-more.js"></script>
<script src="https://code.highcharts.com/modules/exporting.js"></script>
<div id="container2" style="height: 800px; width: 800px;margin: auto;"></div>  
</body>
</html>
