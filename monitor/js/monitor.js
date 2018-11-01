/***
 * Author: Charles
 * Date  : 2017-12-28
 * E-mail:pu17rui@sina.com
***/
// jQuery.noConflict();
jQuery(window).load(function(){
	// CreateStations();

});

jQuery(document).ready(function(){										
	ToggleNotifications();	///// SHOW/HIDE NOTIFICATION /////		
	NotificationContent();	///// NOTIFICATION CONTENT /////		
	ToggleVerSubmenu();	///// SHOW/HIDE VERTICAL SUB MENU /////			
	ToggleVerSubmenu_Collapsed();///// SHOW/HIDE SUB MENU WHEN MENU COLLAPSED /////		
	CloseNotification();///// NOTIFICATION CLOSE BUTTON /////
	ToggleVermenu();///// COLLAPSED/EXPAND LEFT MENU /////
	ResponsiveLayout();///// RESPONSIVE /////
/***********************************************************/
    // SelectStation();
	// ShowTables();


});

/********************************************************************************************/
function ToggleNotifications(){
	jQuery('.notification a').click(function(){
		var t = jQuery(this);
		var url = t.attr('href');
		if(!jQuery('.noticontent').is(':visible')) {
			jQuery.post(url,function(data){
				t.parent().append('<div class="noticontent">'+data+'</div>');
			});
			//this will hide user info drop down when visible
			jQuery('.userinfo').removeClass('active');
			jQuery('.userinfodrop').hide();
		} else {
			t.parent().removeClass('active');
			jQuery('.noticontent').hide();
		}
		return false;
	});	
}

function NotificationContent(){
	jQuery('.notitab a').live('click', function(){
		var id = jQuery(this).attr('href');
		jQuery('.notitab li').removeClass('current'); //reset current 
		jQuery(this).parent().addClass('current');
		if(id == '#messages')
			jQuery('#activities').hide();
		else
			jQuery('#messages').hide();
			
		jQuery(id).show();
		return false;
	});
}

function ToggleVerSubmenu() {
	jQuery('.vernav > ul li a , .vernav2 > ul li a').each(function(){
		var url = jQuery(this).attr('href');
		jQuery(this).click(function(){
			if(jQuery(url).length > 0) {
				if(jQuery(url).is(':visible')) {
					if(!jQuery(this).parents('div').hasClass('menucoll') &&
					   !jQuery(this).parents('div').hasClass('menucoll2'))
							jQuery(url).slideUp();
				} else {
					jQuery('.vernav ul ul, .vernav2 ul ul').each(function(){
							jQuery(this).slideUp();
					});
					if(!jQuery(this).parents('div').hasClass('menucoll') &&
					   !jQuery(this).parents('div').hasClass('menucoll2'))
							jQuery(url).slideDown();
				}
				return false;	
			}
		});
	});
}

function ToggleVerSubmenu_Collapsed(argument) {
	jQuery('.menucoll > ul > li, .menucoll2 > ul > li').live('mouseenter mouseleave',function(e){
		if(e.type == 'mouseenter') {
			jQuery(this).addClass('hover');
			jQuery(this).find('ul').show();	
		} else {
			jQuery(this).removeClass('hover').find('ul').hide();	
		}
	});
}

function CloseNotification() {
	jQuery('.notibar .close').click(function(){
		jQuery(this).parent().fadeOut(function(){
			jQuery(this).remove();
		});
	});
}

function ToggleVermenu() {
	jQuery('.togglemenu').click(function(){
		if(!jQuery(this).hasClass('togglemenu_collapsed')) {
			if(jQuery('.vernav').length > 0) {
			//} else {
				jQuery('body').addClass('withmenucoll2');
				jQuery('.iconmenu').addClass('menucoll2');
			}
			
			jQuery(this).addClass('togglemenu_collapsed');
			
			jQuery('.iconmenu > ul > li > a').each(function(){
				var label = jQuery(this).text();
				jQuery('<li><span>'+label+'</span></li>')
					.insertBefore(jQuery(this).parent().find('ul li:first-child'));
			});
		} else {
			if(jQuery('.vernav').length > 0) {	
			//} else {
				jQuery('body').removeClass('withmenucoll2');
				jQuery('.iconmenu').removeClass('menucoll2');
			}
			jQuery(this).removeClass('togglemenu_collapsed');	
			
			jQuery('.iconmenu ul ul li:first-child').remove();
		}
	});
}

function ResponsiveLayout(argument) {
	if(jQuery(document).width() < 640) {
		jQuery('.togglemenu').addClass('togglemenu_collapsed');
		if(jQuery('.vernav').length > 0) {
			
			jQuery('.iconmenu').addClass('menucoll2');
			jQuery('body').addClass('withmenucoll2');
			jQuery('.centercontent').css({marginLeft: '36px'});
			
			jQuery('.iconmenu > ul > li > a').each(function(){
				var label = jQuery(this).text();
				jQuery('<li><span>'+label+'</span></li>')
					.insertBefore(jQuery(this).parent().find('ul li:first-child'));
			});		
		}
	}
}

/***********************************************************************************************
************************************************************************************************
*******************************************我添加的部分！****************************************
************************************************************************************************
***********************************************************************************************/

/**********************************创建工位**************************************/
// function SelectStation() {
// 	jQuery("div.header ul.headermenu li").click(function(){
// 		//先删除其他li的current类
// 		jQuery("div.header ul.headermenu li").removeClass("current");
// 		//再给当前li加current类
// 		jQuery(this).addClass("current");
// 		//先显示容器
// 		jQuery("div.vernav, div.centercontent").show();
// 		jQuery("body").addClass("withvernav");

// 		//给a标签生成对应的链接
// 		var loc_nam = jQuery("div.left span.slogan").text();
// 		var sta_id = jQuery(this).attr("id");
// 		var panel_href="?pos=monitor&loc_name="+loc_nam+"&sta_id="+sta_id+"&act=ShowPanel";
// 		jQuery("div.vernav li a").attr("href",panel_href);
// 	});
// }
// /**********************************每个工位的ajax请求**************************************/
// function ShowTables() {
// 	jQuery("div.header ul.headermenu li").click(function(){
// 		//先删除其他li的current类
// 		jQuery("div.header ul.headermenu li").removeClass("current");
// 		//再给当前li加current类
// 		jQuery(this).addClass("current");
// 		//在当前station下请求ajax
// 		var station=jQuery(this).attr("id");
// 		jQuery.ajax({
// 			type:"post",
// 			url:"./php/monitor.php",
// 			data:{station_id:station},
// 			dataType:"text",

// 			success:function(msg,status){
// 				//先显示容器
// 				jQuery("div.vernav, div.centercontent").show();
// 				jQuery("body").addClass("withvernav");
// 				//再显示表格
// 				jQuery("div#contentwrapper").html(msg);
// 			},
// 			error:function(msg,status){
// 				jQuery("div.vernav, div.centercontent").hide();
// 				jQuery("body").removeClass("withvernav");
// 				alert("msg:"+msg+"\n"+"status:"+status);
// 			}
// 		});
// 	});
// }
