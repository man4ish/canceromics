$(function () {
  var chart;

 var options = {
        chart :{
                  type: 'polygon',
                  renderTo: 'container',
                  //zoomType:'x'
            
        },
        title: {
            text: ''
        },
        yAxis: {
            title: false,
            gridLineWidth:0,
            lineWidth:0,
            labels:{
                enabled: false
            }
        },
        
        xAxis: {
            title: false,
            gridLineWidth:0,
            lineWidth:0,
            labels:{
                enabled: false
            }
        },
         plotOptions: {
            series: {
                lineWidth: 1,
                lineColor:'black'
            }
        },  
        series: [{}]
    };
var i = 0;
        $("#plus").click(function(){
    i+= 10;
       $.getJSON('frontend/js/temp/data'+i+'.json', function(data) {
         options.series=data;  
         chart = new Highcharts.Chart(options);
       
    });
});
    $("#minus").click(function(){
    i-= 10;
    if(i <10) return false;
        $.getJSON('frontend/js/temp/data'+i+'.json', function(data) {
        options.series=data;
         chart = new Highcharts.Chart(options);
    });
    });
    
   
});

