$(window).load(function(){
});
$(document).ready(function(){     
    var ipc_dev_id = GetIPCInfo();
    // alert(ipc_dev_id);
    if (ipc_dev_id == "") {
        alert("this ipc in this location is illegal!");
        jQuery("div#rtmp-publisher").text("illegal ipc");
    }else{
/******************swfobject********************/                                 
        // var flashVars = {
        //     streamer: 'rtmp://47.100.183.68/blade_test',
        //     file:ipc_dev_id,
        //     play:"true",
        // };
        // var params = {};
        // params.allowfullscreen = "true";
        // params.play ="true";
        // var attributes = {};
        // swfobject.embedSWF("./monitor/swfobject/RtmpPlayer.swf", "rtmp-publisher", "640", "480", "9.0.0", null, flashVars, params, attributes,null);
/******************jwplayer********************/ 
        // alert("rtmp://47.100.183.68/blade_test?carg=1/"+ipc_dev_id+"?sarg=2"); 
        jwplayer("rtmp-publisher").setup({
        sources: [
            {
                file: "rtmp://47.100.183.68/blade_test?carg=1/"+ipc_dev_id+"?sarg=2"
            }
        ],
        // image: "bg.jpg",
        autostart: true,
        width: 640,
        height: 480,
        primary: "flash",
        // controls: false,
        // stretching:"exactfit",
        });
        HeartBeatPacket("start",ipc_dev_id);
    }

});

function GetIPCInfo(){
    var co_nam = jQuery(".topheader .slogan").text();
    var sta_id = jQuery("div.header ul.headermenu li.current").attr("id");
    var ret;
    jQuery.ajax({
        type:"get",
        url :"./index.php?pos=monitor&co_name=" + co_nam + "&sta_id=" + sta_id + "&obj=video&act=GetIPCInfo",
        /*Synchronize*/
        async:false,
        success:function(msg,status){
            ret = msg;
        },
        error:function(msg,status){
            ret = "";
        }
    });
    /*it's very strange that there exists a '\n' */
    ret = ret.replace("\n", "");
    return ret;
}
function HeartBeatPacket(action,dev_id){
    var co_nam = jQuery(".topheader .slogan").text();
    var sta_id  = jQuery("div.header ul.headermenu li.current").attr("id");
    jQuery.ajax({
        type:"get",
        url:"./index.php?pos=monitor&co_name=" + co_nam + "&sta_id=" + sta_id + "&obj=video&act=HeartBeat&para=" + action +"&dev=" + dev_id,

        success:function(msg,status){
            // alert(msg);
            setTimeout("HeartBeatPacket(\'"+action+"\',\'"+dev_id+"\')", 1000);
        },
        error:function(msg,status){
            setTimeout("HeartBeatPacket(\'"+action+"\',\'"+dev_id+"\')", 1000);
        }
    });
}
