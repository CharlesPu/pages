/***
 * Author: Charles
 * Date  : 2018-10-18
 * E-mail: pu17rui@sina.com
***/
jQuery(document).ready(function() {										
	var co_nam = jQuery(".topheader .slogan").text();
	var sta_id = jQuery("div.header ul.headermenu li.current").attr("id");
	// alert(co_nam + sta_id);
	UpdateAmplitude(chart.series[0], chart, co_nam, sta_id);
});

function UpdateAmplitude(series, chart, co_nam, sta_id) {
	/*  ajax  */
	jQuery.ajax({
		type: "get",
		url : "./index.php?pos=monitor&co_name=" + co_nam + "&sta_id=" + sta_id + "&obj=panel&act=GetBladeAm",

		success:function(msg, status){
			// alert(msg);
			var x = (new Date()).getTime(); // 当前时间
			// var	y = Math.random();          // 随机值
			var y = parseFloat(msg);
			/* draw~ */
			var len = series.points.length;
			if (len < 20) series.addPoint([x, y], true, false);
				else series.addPoint([x, y], true, true);
			activeLastPointTooltip(chart);
			jQuery('div#blade-param p').text(series.points.length);
		},
		error:function(msg, status){
			
		}
	});
	
	setTimeout(function() {
		UpdateAmplitude(series, chart, co_nam, sta_id);
	}, 1000);
}
Highcharts.setOptions({
	global: {
		useUTC: false
	}
});
function activeLastPointTooltip(chart) {
	var points = chart.series[0].points;
	chart.tooltip.refresh(points[points.length -1]);
}
var chart = Highcharts.chart('blade-amplitude-chart', {
	chart: {
		type: 'spline',
		marginRight: 10,
		// events: {
		// 	load: function () {
		// 		var series = this.series[0],
		// 			chart  = this;
		// 		if enable this func below, tooltip will not find x and y, because data is empty
		// 		// activeLastPointTooltip(chart);
		// 		// UpdateAmplitude(series, chart);
		// 	}
		// }
	},
	title: {
		text: '动态模拟实时数据'
	},
	xAxis: {
		type: 'datetime',
		/*time(ms) between two x points*/
		tickInterval: 1000,
		// tickPixelInterval: 150,
	},
	yAxis: {
		title: {
			text: null
		}
	},
	tooltip: {
		// enabled: false,
		formatter: function () {
			return '<b>' + this.series.name + '</b><br/>' +
					Highcharts.dateFormat('%Y-%m-%d %H:%M:%S', this.x) + '<br/>' +
					Highcharts.numberFormat(this.y, 2);
		}
	},
	legend: {
		enabled: false
	},
	credits: {
		enabled: false,
	},
	series: [{
		name: '随机数据',
		data: [],
		// (function () {
		// 	// 生成随机值
		// 	var data = [],
		// 		time = (new Date()).getTime(),
		// 		i;
		// 	for (i = 0; i <= 0; i += 1) {
		// 		data.push({
		// 			x: time + i * 1000,
		// 			y: Math.random()
		// 		});
		// 	}
		// 	return data;
		// }())
	}]
});

function Count(obj){
    var t = typeof obj;
    if(t == 'string'){
        return obj.length;
    }else 
    if(t == 'object'){
        var n = 0;
        for(var i in obj){
                n++;
        }
        return n;
    }
    return false;
}
