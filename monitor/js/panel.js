/***
 * Author: Charles
 * Date  : 2018-12-16
 * E-mail:pu17rui@sina.com
 * Others:a comfortable afternoon...
***/
// jQuery.noConflict();
jQuery(window).load(function(){
});
jQuery(document).ready(function(){						
	GetAllData();
	SetControlVal();
});
/***********************************************************************************************
*******************************************我添加的部分！****************************************
***********************************************************************************************/
function GetAllData() {
	var sta_id = jQuery("div.header ul.headermenu li.current").attr("id");
	jQuery.ajax({
		type:"get",
		url:"./index.php?pos=monitor&sta_id=" + sta_id + "&obj=panel&act=GetAllData",

		success:function(msg,status){
			// alert(msg);
			msg = eval('(' + msg + ')'); //decode json
			//blade
			UpdateAmplitude(chart.series[0], chart, msg.BladePosi);
			//sys state
			jQuery("#contentwrapper #sys-state #sys-para #blade-am span").html(parseFloat(msg.SysState.bl_am).toFixed(2));
			jQuery("#contentwrapper #sys-state #sys-para #blade-eff-cnt span").html(msg.SysState.bl_eff_cnt);
			jQuery("#contentwrapper #sys-state #sys-para #cylinder-am span").html(parseFloat(msg.SysState.cy_am).toFixed(2));
			jQuery("#contentwrapper #sys-state #sys-para #cylinder-eff-cnt span").html(msg.SysState.cy_eff_cnt);
			jQuery("#contentwrapper #sys-state #sys-alarm #alarm_no span").html(msg.SysState.alm_no);
			jQuery("#contentwrapper #sys-state #sys-alarm #alarm_ctx").html(msg.SysState.alm_ctx);
			//cylinder
			for (var i = 0; i < 4; i++) {
				var posi = msg.CyPosi[i].cy_posi;
				var dis = -posi / 8 + 50;
				jQuery(".contentwrapper #cylinder #cy-"+(i+1)+" .arrow").css("background-position","100% "+dis+'%');
			}

			setTimeout("GetAllData()",1000);
		},
		error:function(msg,status){
			// alert(msg);
			setTimeout("GetAllData()",1000);
		}
	});
}
function SetControlVal() {
	var sta_id=jQuery("div.header ul.headermenu li.current").attr("id");
	jQuery("#contentwrapper #sys-param-ctrl button").click(function(){
		var blade_am_set = jQuery("#contentwrapper #sys-param-ctrl #blade-am-set input").val();
		var blade_cnt_set = jQuery("#contentwrapper #sys-param-ctrl #blade-cnt-set input").val();
		var cylinder_am_set = jQuery("#contentwrapper #sys-param-ctrl #cylinder-am-set input").val();
		var cylinder_t_set = jQuery("#contentwrapper #sys-param-ctrl #cylinder-t-set input").val();
		if (blade_am_set == "" || blade_cnt_set == "" || cylinder_am_set == "" || cylinder_t_set == "") {
			alert("请将数据输入完整！");
		}else{
			jQuery.ajax({
				type:"post",
				url:"./index.php?pos=monitor&sta_id=" + sta_id + "&obj=panel&act=SetSysParams",
				data:{
					blade_am_set:blade_am_set,
					blade_cnt_set:blade_cnt_set,
					cylinder_am_set:cylinder_am_set,
					cylinder_t_set:cylinder_t_set,
				},
				success:function(msg, status){
					// alert(msg);
					if(msg == -1) alert("您并没有控制权限，请联系网站管理员！");
					else alert("指令下达成功！");
				},
				error:function(msg,status){ alert("指令下达失败!"); }
			});
		}	
	});
	jQuery("#contentwrapper #cylinder .cy button.clk").click(function(){
		var act = jQuery(this).attr("class").split(" ")[0];
		var cy_id = jQuery(this).parents("div.cy").attr("id").split("-")[1];
		jQuery.ajax({
			type:"post",
			url:"./index.php?pos=monitor&sta_id=" + sta_id + "&obj=panel&act=SetCyOnetimeClk",
			data:{
				cy_id:cy_id,
				act:act,
			},
			success:function(msg, status){
				// alert(msg);
				if(msg == -1) alert("您并没有控制权限，请联系网站管理员！");
				else alert("指令下达成功！");
			},
			error:function(msg,status){ alert("指令下达失败!"); }
		});
	});
	jQuery("#contentwrapper #cylinder .cy button.run").click(function(){
		var cy_id = jQuery(this).parents("div.cy").attr("id").split("-")[1];
		var single_cy_am = jQuery(this).siblings(".single-cy-am").children("input").val();
		var single_cy_t = jQuery(this).siblings(".single-cy-t").children("input").val();
		if (single_cy_am == "" || single_cy_t == "") {
			alert("请将数据输入完整！");
		}else {
			jQuery.ajax({
				type:"post",
				url:"./index.php?pos=monitor&sta_id=" + sta_id + "&obj=panel&act=SetSingleCyParams",
				data:{
					cy_id:cy_id,
					single_cy_am:single_cy_am,
					single_cy_t:single_cy_t,
				},
				success:function(msg, status){
					// alert(msg);
					if(msg == -1) alert("您并没有控制权限，请联系网站管理员！");
					else alert("指令下达成功！");
				},
				error:function(msg,status){ alert("指令下达失败!"); }
			});	
		}		
	});
}

//get the real length of an array!!! instead of .length
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

/****************************************** highcharts****************************************/
function UpdateAmplitude(series, chart, val) {
	var x = (new Date()).getTime(); // 当前时间
	// var	y = Math.random();          // 随机值
	var y = parseFloat(val);
	/* draw~ */
	var len = series.points.length;
	if (len < 20) series.addPoint([x, y], true, false);
		else series.addPoint([x, y], true, true);
	activeLastPointTooltip(chart);
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
		// text: '动态模拟实时数据'
		text:null
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
		name: '叶片实时位置',
		data: [],
	}]
});
