
 $(document).ready(function(){
     $('.showModal2').click(function(){
          $('#popup2').dialog({width: 450,height: 450});
      });
    });

$(function () {
    $('#piechart2').highcharts({
        chart: {
            plotBackgroundColor: null,
            plotBorderWidth: 1,//null,
            plotShadow: false
        },
        title: {
            text: ''
        },
        tooltip: {
            pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
        },
        plotOptions: {
            pie: {
                allowPointSelect: true,
                cursor: 'pointer',
                showInLegend: true,
                dataLabels: {
                    enabled: false,
                    format: '<b>{point.name}</b>: {point.percentage:.1f} %',
                    style: {
                        color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'red'
                    },
                showInLegend: true
                }
            }
        },

        series: [{
            type: 'pie',
            name: 'SNPs',
            data: [
                ['Downrstream',   24.1095890],
                ['Intron',    46.3013699],
                ['Non Synonymous', 2.1917808],     
                ['Synonymous',   3.2876712],
                ['Upstream',    21.6438356],
                ['UTR-3', 1.9178082], 
                ['UTR-5', 0.5479452]                 
            ]
        }]
    });
});

