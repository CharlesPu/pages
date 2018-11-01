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
        //     streamer: 'rtmp://47.100.183.68/myapp',
        //     file:ipc_dev_id,
        //     play:"true",
        // };
        // var params = {};
        // params.allowfullscreen = "true";
        // params.play ="true";
        // var attributes = {};
        // swfobject.embedSWF("./monitor/swfobject/RtmpPlayer.swf", "rtmp-publisher", "640", "480", "9.0.0", null, flashVars, params, attributes,null);
/******************jwplayer********************/ 
        // alert("rtmp://47.100.183.68/myapp?carg=1/"+ipc_dev_id+"?sarg=2"); 
        jwplayer("rtmp-publisher").setup({
        sources: [
            {
                file: "rtmp://47.100.183.68/myapp?carg=1/"+ipc_dev_id+"?sarg=2"
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

    // Preview("start");
    // PreClose();
});
// $(window).bind('beforeunload',function(){ 
//     Preview("end");
//     // return 0;
//     // return '您输入的内容尚未保存，确定离开此页面吗？'; 
// }); 
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

function Preview(action) {
    var co_nam=jQuery(".topheader .slogan").text();
    var sta_id=jQuery("div.header ul.headermenu li.current").attr("id");
    jQuery.ajax({
        type:"get",
        url:"./index.php?pos=monitor&co_name=" + co_nam + "&sta_id=" + sta_id + "&obj=video&act=Preview&para=" + action,

        success:function(msg,status){
            // alert(msg);
            setTimeout("Preview('" + action + "')", 1000);
        },
        error:function(msg,status){
            // alert("There is no video stream now!");
            setTimeout("Preview('" + action + "')", 1000);
        }
    });
}
/*special for logout action!*/
function PreClose(){
    jQuery("div.right a:contains('注销')").click(function(){
        // alert("haha");
        Preview('end');
    });
}








/***********************************************************************************/
// //每个iframe的大小在video.css 和 iframe.html中调整
// var g_aIframe = $("iframe");
// $("iframe").ready(function(){
//     setTimeout("iframeLoaded()" ,500);  
//     // iframeLoaded();
// });
// //关闭或者刷新页面时执行
// window.onunload = function(){
//     // myLogout();
//     // $.each(g_aIframe, function (i, oIframe) {
//     //     getWebVideoCtrl(oIframe).I_Stop();
//     // });
// }
// // 关闭浏览器
// $(window).unload(function () {
//     $.each(g_aIframe, function (i, oIframe) {
//         getWebVideoCtrl(oIframe).I_Stop();
//     });
// });
// var IPC = new Array();
// IPC[0] = {
//         iProtocol: 1,            // protocol 1：http, 2:https
//         szIP: "111.231.89.253",    // protocol ip
//         szPort: "900",            // protocol port
//         szUsername: "admin",     // device username
//         szPassword: "tj097103603", // device password
//         iStreamType: 2,          // stream 1：main stream  2：sub-stream  3：third stream  4：transcode stream
//         iChannelID: 1,           // channel no
//         bZeroChannel: false      // zero channel
// };
// IPC[1] =  {
//         iProtocol: 1,            // protocol 1：http, 2:https
//         szIP: "192.168.0.64",    // protocol ip
//         szPort: "80",            // protocol port
//         szUsername: "admin",     // device username
//         szPassword: "tj097103603", // device password
//         iStreamType: 2,          // stream 1：main stream  2：sub-stream  3：third stream  4：transcode stream
//         iChannelID: 1,           // channel no
//         bZeroChannel: false      // zero channel
// };


// function CheckPlugin(){
//     // 检查插件是否已经安装过
//     var iRet = WebVideoCtrl.I_CheckPluginInstall();
//     if (-1 == iRet) {
//         alert("您还未安装过插件，请点击 “确定” 下载安装插件！");
//         window.location.href="./monitor/js/WebComponentsKit.exe";
//         return;
//     }
// };
// // var iLoadedCount = 0;//窗口数
// function iframeLoaded() {
//     // iLoadedCount++;
//     // if (1 === iLoadedCount) 
//     {   
//         $.each(g_aIframe, function (i, oIframe) {
//             var oWebVideoCtrl = getWebVideoCtrl(oIframe);
//             // 登录设备
//             oWebVideoCtrl.I_Login(IPC[i].szIP, IPC[i].iProtocol, IPC[i].szPort, IPC[i].szUsername, IPC[i].szPassword, {
//                 success: function (xmlDoc) {
//                     // 开始预览
//                     var szDeviceIdentify = IPC[i].szIP + "_" + IPC[i].szPort;
//                     setTimeout(function () {
//                         oWebVideoCtrl.I_StartRealPlay(IPC[i].szIP, {
//                             iStreamType: IPC[i].iStreamType,
//                             iChannelID: IPC[i].iChannelID,
//                             bZeroChannel: IPC[i].bZeroChannel
//                         });
//                     }, 1000);
//                 },
//                 error:function(xmlDoc){
//                     alert("预览失败！");
//                 }
//             });
//         });
//     }
// }
// function getWebVideoCtrl(oIframe) {
//     return oIframe.contentWindow.WebVideoCtrl;
// }




