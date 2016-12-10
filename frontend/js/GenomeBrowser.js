var UNDEFINED;
		var options = {
		    chart: {
		        renderTo: 'GBrowser',
                        type: 'line',
                        zoomType: 'x',
                        borderWidth:1,
		    },
                    title: {
                     text: 'Genome Browser',
                    },
                    credits: {
                     enabled: false
                    },
                    xAxis: {
                      min:0,
                      max:245000000,
                      lineColor: 'black',
                      lineWidth: 1
                    },
                    yAxis: {
                        lineColor: 'black',
                        lineWidth: 1
                    },
                    tooltip: {
					            headerFormat: '<b>Gene</b><br/>',
					            pointFormat: 'start {point.x}: stop {point.y}'
                    },
                    legend: {
                     enabled:false,
                    },
                    loading:{
						showDuration: 100
					},
                     plotOptions: {
					            series: {
					                marker: {
					                    enabled: false
					                }
					            }
        },
		    series: []
		};

		$.get('samplefile.csv', function(data) {

	    	var lines = data.split('\n'),
	    		items, itemValues;
            demo_data=[[]];
			for(i=0;i<lines.length;i++)
			{
			    options.series[i] = {data: []}
			    line=lines[i];
		        itemsDates = line.split('\t');
		        demo_data[0].push([parseFloat(itemsDates[0]),2]);
		        demo_data[0].push([parseFloat(itemsDates[1]),2]);
			    options.series[i].data.push([parseFloat(itemsDates[0]),2]);
			    options.series[i].turboThreshold=0;
			    options.series[i].lineWidth=5;
				options.series[i].color="red";
				options.series[i].data.push([parseFloat(itemsDates[1]),2]);
	        }
		    var chart = new Highcharts.Chart(options);
		});
