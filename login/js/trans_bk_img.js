jQuery(function($){

    $.supersized({

        // Functionality
        slide_interval     : 6000,    // Length between transitions
        transition         : 1,    // 0-None, 1-Fade, 2-Slide Top, 3-Slide Right, 4-Slide Bottom, 5-Slide Left, 6-Carousel Right, 7-Carousel Left
        transition_speed   : 1500,    // Speed of transition
        performance        : 1,    // 0-Normal, 1-Hybrid speed/quality, 2-Optimizes image quality, 3-Optimizes transition speed // (Only works for Firefox/IE, not Webkit)

        // Size & Position
        min_width          : 0,    // Min width allowed (in pixels)
        min_height         : 0,    // Min height allowed (in pixels)
        vertical_center    : 1,    // Vertically center background
        horizontal_center  : 1,    // Horizontally center background
        fit_always         : 0,    // Image will never exceed browser width or height (Ignores min. dimensions)
        fit_portrait       : 1,    // Portrait images will not exceed browser height
        fit_landscape      : 0,    // Landscape images will not exceed browser width

        // Components
        slide_links        : 'blank',    // Individual links for each slide (Options: false, 'num', 'name', 'blank')
        slides             : [    // Slideshow Images
                                    //以当前载入html的index.php为根目录!!!!!!
                                 {image : './login/img/backgrounds/1.jpg'},
                                 {image : './login/img/backgrounds/2.jpg'},
                                 {image : './login/img/backgrounds/3.jpg'},
                             ]

    });

});

function ShowTime() {
    var date=new Date();
    var y = date.getFullYear();  
    var m = date.getMonth() + 1;  
    m = m < 10 ? '0' + m : m;  
    var d = date.getDate();  
    d = d < 10 ? ('0' + d) : d; 
    var h = date.getHours();  
    h=h < 10 ? ('0' + h) : h;  
    var weeks=["Sun","Mon","Tues","Wed","Thur","Fri","Sat"];
    var w =weeks[date.getDay()];
    var minute = date.getMinutes();  
    minute = minute < 10 ? ('0' + minute) : minute;  
    var second=date.getSeconds();  
    second=second < 10 ? ('0' + second) : second;  
    var time = y + '-' + m + '-' +d+' '+ w +' '+h+':'+minute+':'+second;  
    document.getElementById("tim").innerHTML=time;
    setTimeout("ShowTime()",101);
}
