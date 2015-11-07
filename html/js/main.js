$(document).ready(function(){	
    var myWidth,myHeight;
    function resize(){
       if( typeof( window.innerWidth ) == 'number' ) {
            myWidth = window.innerWidth;
            myHeight = window.innerHeight;
        } else if( document.documentElement && ( document.documentElement.clientWidth || 
        document.documentElement.clientHeight ) ) {
            myWidth = document.documentElement.clientWidth;
            myHeight = document.documentElement.clientHeight;
        } else if( document.body && ( document.body.clientWidth || document.body.clientHeight ) ) {
            myWidth = document.body.clientWidth;
            myHeight = document.body.clientHeight;
        }
        $(".b-content").css("min-height",myHeight-$(".b-header").height()-$(".b-footer").height());
    }
    $(window).resize(resize);
    resize();
    
    $(window).load(function(){
        $(".b-content").css("min-height",myHeight-$(".b-header").height()-$(".b-footer").height());
    });
    
    $.fn.placeholder = function() {
        if(typeof document.createElement("input").placeholder == 'undefined') {
            $('[placeholder]').focus(function() {
                var input = $(this);
                if (input.val() == input.attr('placeholder')) {
                    input.val('');
                    input.removeClass('placeholder');
                }
            }).blur(function() {
                var input = $(this);
                if (input.val() == '' || input.val() == input.attr('placeholder')) {
                    input.addClass('placeholder');
                    input.val(input.attr('placeholder'));
                }
            }).blur().parents('form').submit(function() {
                $(this).find('[placeholder]').each(function() {
                    var input = $(this);
                    if (input.val() == input.attr('placeholder')) {
                        input.val('');
                    }
                });
            });
        }
    }
    $.fn.placeholder();
    
	// var myPlace = new google.maps.LatLng(55.754407, 37.625151);
 //    var myOptions = {
 //        zoom: 16,
 //        center: myPlace,
 //        mapTypeId: google.maps.MapTypeId.ROADMAP,
 //        disableDefaultUI: true,
 //        scrollwheel: false,
 //        zoomControl: true
 //    }
 //    var map = new google.maps.Map(document.getElementById("map_canvas"), myOptions); 

 //    var marker = new google.maps.Marker({
	//     position: myPlace,
	//     map: map,
	//     title: "Ярмарка вакансий и стажировок"
	// });

    //  var options = {
    //     $AutoPlay: true,                                
    //     $SlideDuration: 500,                            

    //     $BulletNavigatorOptions: {                      
    //         $Class: $JssorBulletNavigator$,             
    //         $ChanceToShow: 2,                           
    //         $AutoCenter: 1,                            
    //         $Steps: 1,                                  
    //         $Lanes: 1,                                  
    //         $SpacingX: 10,                              
    //         $SpacingY: 10,                              
    //         $Orientation: 1                             
    //     }
    // };

    // var jssor_slider1 = new $JssorSlider$("slider1_container", options);

    $(".b-main-slider").slick({
        autoplay: true,
        dots: true,
        arrows: false,
        autoplaySpeed: 6000
        // fade: true
    });

    $(".filter-item").click(function(){
        $(this).find(".variants").css("display","table");
        $(this).find(".input").addClass("active");
        if( $(this).position().left > 480 ) {
            $(this).find(".variants").css("right","0");
        } else $(this).find(".variants").css("left","0");
    });

    $(".variants input").change(function(){
        var obj = $(this).closest(".filter-item").find("input:checked"),
        input = $(this).closest(".filter-item").find(".input"),
        text="";
        if(obj.length != 0) {
            obj.each(function(index, item){
                console.log($(item).siblings("span").text());
                text = text+$(item).siblings("span").text()+",";
            });
            text = text.substr(0,text.length-1);
            input.html(text+"<span></span>");
        } else {
            input.html("<span></span>");
        }        
    });
    $( ".main-tabs" ).tabs({
        active: 0
    });






   
});