$(function () {
    $('#barplot').highcharts({
        chart: {
            type: 'column'
        },
        title: {
            text: 'MAF Distribution'
        },
        xAxis: {
            categories: ['0.05', '0.1', '0.15', '0.2', '0.25','0.30', '0.35', '0.40', '0.45', '0.50']
        },
        credits: {
            enabled: false
        },
        series: [{
            name: 'Qatar Genomes',
            data: [.88, .3, .4, .7, .2,.88, .3, .4, .7, .2]
        }, {
            name: '1000 Genomes',
            data: [.48, .2, .3, 2, 1,.88, .3, .4, .7, .2]
        }, {
            name: 'Common',
            data: [.38, .4, .4, .2, .5,.88, .3, .4, .7, .2]
        }]
    });
});
