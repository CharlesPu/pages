/***
 * Author: Charles
 * Date  : 2018-05-20
 * E-mail:pu17rui@sina.com
***/
// jQuery.noConflict();
jQuery(window).load(function(){

});
jQuery(document).ready(function(){										
	// GetAllData();
	// SetMtControlVal();
	// Animate();
	// test();
});
/***********************************************************************************************
*******************************************我添加的部分！****************************************
***********************************************************************************************/
function GetAllData() {
	var loc_nam=jQuery(".topheader .slogan").text();
	var sta_id=jQuery("div.header ul.headermenu li.current").attr("id");
	jQuery.ajax({
		type:"get",
		url:"./index.php?pos=monitor&loc_name=" + loc_nam + "&sta_id=" + sta_id + "&obj=panel&act=GetAllData",

		success:function(msg,status){
			// alert(msg);
			msg = eval('(' + msg + ')'); //decode json
			//cylinders
			for (var i = 0; i < Count(msg.Cylinders); i++) {
				/***** plug animation*****/
				// var dis =  39.5- msg.Cylinders[i].plug_position * 0.098;
				// var cy_id = "cy" + msg.Cylinders[i].device_id; 	
				// jQuery(".contentwrapper .cylinders ."+cy_id+" .plug").animate({top:dis+'%'},'slow');
				/***** plug position*****/
				jQuery("#contentwrapper .cylinders .cy" + msg.Cylinders[i].device_id + 
					" .cylinder .distance span").html(parseFloat(msg.Cylinders[i].plug_position).toFixed(2));
				
				/******cylinders pressure*******/
				jQuery("#contentwrapper .cylinders .cy" + msg.Cylinders[i].device_id + 
					" .cylinder .pressure span").html(parseFloat(msg.Cylinders[i].pressure).toFixed(2));
			}
			//  motors
			jQuery("#contentwrapper .motors").find("button").css("background","rgba(0,72,152,0.8)");

			for (var i = 0; i < Count(msg.Motors); i++) {
				/******state (word)******/
				/******1--running   0--prepare******/
				jQuery("#contentwrapper .motors .motorid:contains('" + msg.Motors[i].device_id + 
					"')").siblings(".motorstate").find("span").text(msg.Motors[i].state>0?"运行中":"停止");
				/******state (pictrue)******/
				if(msg.Motors[i].state > 0){ // is running
					jQuery("#contentwrapper .motors .motorid:contains('" + msg.Motors[i].device_id + "')").parent().removeClass("prepare");
					jQuery("#contentwrapper .motors .motorid:contains('" + msg.Motors[i].device_id + "')").parent().addClass("running");
					jQuery("#contentwrapper .motors .motorid:contains('" + msg.Motors[i].device_id + "')")
							.siblings(".motor-state-ctrl").find("button").text("停止");
					jQuery("#contentwrapper .motors .motorid:contains('" + msg.Motors[i].device_id + "')")
							.siblings(".motor-state-ctrl").find("button").css("background","rgba(230,0,32,0.8)");
				}else{	// is preparing
					jQuery("#contentwrapper .motors .motorid:contains('" + msg.Motors[i].device_id + "')").parent().removeClass("running");
					jQuery("#contentwrapper .motors .motorid:contains('" + msg.Motors[i].device_id + "')").parent().addClass("prepare");
					jQuery("#contentwrapper .motors .motorid:contains('" + msg.Motors[i].device_id + "')")
							.siblings(".motor-state-ctrl").find("button").text("启动");
					jQuery("#contentwrapper .motors .motorid:contains('" + msg.Motors[i].device_id + "')")
							.siblings(".motor-state-ctrl").find("button").css("background","rgba(0,72,152,0.8)");		
				}
				/******    rpm   ******/
				if (msg.Motors[i].rpm != null) 
					jQuery("#contentwrapper .motors .motorid:contains('" + msg.Motors[i].device_id + "')")
							.siblings(".motorspeed").find("span").text(msg.Motors[i].rpm);				
			}
			//  angles
			jQuery("#contentwrapper #angles .x_axis span").html(parseFloat(msg.Angles[0].x_angle).toFixed(2));
			jQuery("#contentwrapper #angles .y_axis span").html(parseFloat(msg.Angles[0].y_angle).toFixed(2));		
			// alerts
			if (Count(msg.Alerts) > 0) {
				var alert_msg = "<ul>";
				for (var i = 0 ; i < Count(msg.Alerts); i++) {
					alert_msg += ("<li>" + msg.Alerts[i].pump_station_id+"号泵站 "+msg.Alerts[i].content +"</li>");
				}
				alert_msg += "</ul>";
				jQuery("#contentwrapper #alerts p").html("注意！有"+Count(msg.Alerts)+"条警告信息！");
				jQuery("#contentwrapper #alerts p").css("color","rgba(230,0,32,1)");
				jQuery("#contentwrapper #alerts .alert_msgs").html(alert_msg);
				// alert(msg.Alerts[0].content);
			}else{
				jQuery("#contentwrapper #alerts p").html("目前状态良好，没有警告消息");
				jQuery("#contentwrapper #alerts p").css("color","#666");
				jQuery("#contentwrapper #alerts .alert_msgs").html("");
			}
			GetAllData();
		},
		error:function(msg,status){
			GetAllData();
		}
	});
	// setTimeout("GetAllData()",200);
}
function SetMtControlVal(){
	var loc_nam=jQuery(".topheader .slogan").text();
	var sta_id=jQuery("div.header ul.headermenu li.current").attr("id");
	jQuery("#contentwrapper .motors button").click(function(){
		// if (jQuery(this).siblings("input").val() == "")
		// 	alert("请输入控制值！");
		// else
		{
			var dev_id=jQuery(jQuery(this).parent()).siblings("div.motorid").text();
			var arr = jQuery(this).parents("div").attr("class").split("-");
			var json_str = "[";
			for (var i = 1; i <= 8; i++) {
				if (jQuery("#contentwrapper .motors .mt" + i).children(".motorid").text() == dev_id) {
					json_str += '{"dev_name":"'+ arr[0] +'","dev_id":"' + dev_id + '","ctrl_param":"'+arr[1]+'","ctrl_value": "'
					+ (jQuery(jQuery(this).parent()).parent().hasClass("prepare")==true?1:0) +'"},';
				}else
				json_str += '{"dev_name":"'+ arr[0] +'","dev_id":"' + jQuery("#contentwrapper .motors .mt" + i).children(".motorid").text()
							+ '","ctrl_param":"'+arr[1]+ '","ctrl_value": "'
							+ (jQuery("#contentwrapper .motors .mt" + i).hasClass("prepare")==true?0:1)+'"},';
			}
			json_str += "]";
			// alert(json_str);
			json_str = eval('(' + json_str + ')'); //转化为json对象
			
			jQuery.ajax({
				type:"post",
				url:"./index.php?pos=monitor&loc_name=" + loc_nam + "&sta_id=" + sta_id + "&obj=panel&act=ControlDevices",
				data:{
					json_obj:json_str,
				},
				dataType:"text",
				success:function(msg, status){
					if(msg == 8) alert("指令已成功下达！");
					else alert("指令下达失败!");
					// alert(msg);
				},
				error:function(msg,status){

				}
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
function Animate(){
	for (var i = 1; i <= 4; i++) {
		var posi = jQuery("#contentwrapper .cylinders .cy" + i + " .cylinder .distance span").html();
		var dis = 39.5 - posi * 0.098;
		var cy_id = "cy" + i; 	
		jQuery(".contentwrapper .cylinders ."+cy_id+" .plug").animate({top:dis+'%'},10);
	}
	setTimeout("Animate()", 100);
}

function test() {
	jQuery("#contentwrapper .motors .motorid:contains('1#_A1')").siblings(".motorstate").find("span").text("hah");
}

