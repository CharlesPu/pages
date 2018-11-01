/***
 * Author: Charles
 * Date  : 2017-12-28
 * E-mail:pu17rui@sina.com
***/
$(window).load(function(){
    adjustHeightOfPage(1); // Adjust page height
    BrowserResized();/* Browser resized ------*/
/***********************************************************/
    $('body').addClass('loaded');       
});

jQuery(document).ready(function(){
    // SubmitSelectLocation();
});

/***********************************************************************************************/
function adjustHeightOfPage(pageNo) {

    var offset = 80;
    var pageContentHeight = 0;

    var pageType = $('div[data-page-no="' + pageNo + '"]').data("page-type");

    if( pageType != undefined && pageType == "gallery") {
        pageContentHeight = $(".cd-hero-slider li:nth-of-type(" + pageNo + ") .tm-img-gallery-container").height();
    }
    else {
        pageContentHeight = $(".cd-hero-slider li:nth-of-type(" + pageNo + ") .js-tm-page-content").height();
    }

    if($(window).width() >= 992) { offset = 120; }
    else if($(window).width() < 480) { offset = 40; }
   
    // Get the page height
    var totalPageHeight = 15 + $('.cd-slider-nav').height()
                            + pageContentHeight + offset
                            + $('.tm-footer').height();

    // Adjust layout based on page height and window height
    if(totalPageHeight > $(window).height()) 
    {
        $('.cd-hero-slider').addClass('small-screen');
        $('.cd-hero-slider li:nth-of-type(' + pageNo + ')').css("min-height", totalPageHeight + "px");
    }
    else 
    {
        $('.cd-hero-slider').removeClass('small-screen');
        $('.cd-hero-slider li:nth-of-type(' + pageNo + ')').css("min-height", "100%");
    }
}
function BrowserResized(){
    $( window ).resize(function() {
    var currentPageNo = $(".cd-hero-slider li.selected .js-tm-page-content").data("page-no");

    // wait 3 seconds
    setTimeout(function() {
        adjustHeightOfPage( currentPageNo );
    }, 1000);

    }); 
}
/***********************************************************************************************
************************************************************************************************
*******************************************我添加的部分！****************************************
************************************************************************************************
***********************************************************************************************/

// function CreateLocations(){ 
//     jQuery.get("./php/create_loc.php",function(data,status){
//         var obj=eval(data);
//         if (obj.length == 0) {
//             jQuery("h2.tm-gallery-title span.tm-white").text("您没有可监控的测试现场，请联系我们确认");
//         }else{
//             for (var i = 1; i < obj.length+1; i++) {
//                 jQuery("div#location"+i).show();
//                 jQuery("div#location"+i+" h2").text(obj[i-1]['location_name']);
//                 jQuery("div#location"+i+" p").text(obj[i-1]['location_desc']);
//             }
//             jQuery(".grid-item").css("width","100%");
//             jQuery(".container-fluid").css("width","auto");             
//         }
//     });
// }



