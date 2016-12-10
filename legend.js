$(function() {

  $('#container').highcharts({

    chart: {
      type: 'heatmap',
      height: 100,
      plotBorderWidth: 1,
      marginLeft: 0,
      marginRight: 0
    },
    legend: {
      enabled: false
    },
    title: {
      text: ''
    },

    xAxis: {
      visible: false
    },

    yAxis: {
      visible: false
    },


    series: [{
      borderWidth: 1,
      keys: ['x', 'y', 'value', 'color'],
      data: [
        [0, 0, 1, 'red'],
        [1, 0, 1, 'blue'],
        [2, 0, 1, 'green'],
        [3, 0, 1, 'yellow'],
        [4, 0, 1, 'red'],
        [5, 0, 1, 'blue'],
        [6, 0, 1, 'green'],
        [7, 0, 1, 'yellow'],
        [8, 0, 1, 'red'],
        [9, 0, 1, 'blue'],
        [10, 0, 1, 'green'],
        [11, 0, 1, 'yellow'],
      ],
    }]

  });
});

