$(document).ready(function(){	
    var myWidth,myHeight,
        big = 3,
        nowBig = 1;
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

        nowBig = ( myWidth > 900 )?5:5;
        nowBig = ( myWidth > 767 )?nowBig:2;
        if( $("#similar-slider").length )
            if( nowBig != big ){
                big = nowBig;
                $("#similar-slider").slick("unslick");
                $('#similar-slider').slick({
                    slidesToShow: big,
                    slidesToScroll: 1,
                    prevArrow: "<span class='b-sim-nav gradient-lightBlack b-sim-left'></span>",
                    nextArrow: "<span class='b-sim-nav gradient-lightBlack b-sim-right'></span>"
                });
            }
    }

    function whenScroll(){
        if( $(".b-fixed-top").length ){
            var scroll = ((document.documentElement && document.documentElement.scrollTop) || document.body.scrollTop);
            if( scroll > $(".b-relative-top").offset().top+$(".b-relative-top").height() ){
                $(".b-fixed-top").addClass("shown");
            }else{
                $(".b-fixed-top").removeClass("shown");
            }
        }
    }
    $(window).scroll(whenScroll);
    $('body').bind('touchmove', whenScroll);
    whenScroll();

    $('#similar-slider').slick({
        slidesToShow: 5,
        slidesToScroll: 1,
        prevArrow: "<span class='b-sim-nav gradient-lightBlack b-sim-left'></span>",
        nextArrow: "<span class='b-sim-nav gradient-lightBlack b-sim-right'></span>"
    });
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
        
    $( ".main-tabs" ).tabs({
        active: 0
    });

    $('.min-val,.max-val').bind("change keyup input click", function() {
        if (this.value.match(/[^0-9]/g)) {
            this.value = this.value.replace(/[^0-9]/g, '');
        }
    });

    function range_init() {
        $.each($(".slider-range"),function(){
            var obj = $(this),
            min_input = $(this).closest(".slide-type").find(".min-val"),
            max_input = $(this).closest(".slide-type").find(".max-val"),
            min_text = $(this).closest(".slide-type").find(".min-text"),
            max_text = $(this).closest(".slide-type").find(".max-text"),
            min_val = $(this).attr("data-min")*1,
            max_val = $(this).attr("data-max")*1,
            cur_min_val = $(this).attr("data-min-cur") ? $(this).attr("data-min-cur")*1 : min_val,
            cur_max_val = $(this).attr("data-max-cur") ? $(this).attr("data-max-cur")*1 : max_val,
            data_step = $(this).attr("data-step") ? $(this).attr("data-step")*1 : 1;
            obj.slider({
                step: data_step,
                range: true,
                min: min_val,
                max: max_val,
                values: [ cur_min_val, cur_max_val ],
                slide: function( event, ui ) {
                    (ui.values[ 0 ] == min_val) ? min_input.val('') : min_input.val( ui.values[ 0 ] );
                    (ui.values[ 1 ] == max_val) ? max_input.val('') : max_input.val( ui.values[ 1 ] );  
                    min_text.text( ui.values[ 0 ] );
                    $(".tt-min").text( ui.values[ 0 ] );
                    max_text.text( ui.values[ 1 ] );
                    $(".tt-max").text( ui.values[ 1 ] );

                },
                change: function( event, ui ) {  
                    (ui.values[ 0 ] == min_val) ? min_input.val('') : min_input.val( ui.values[ 0 ] );
                    (ui.values[ 1 ] == max_val) ? max_input.val('') : max_input.val( ui.values[ 1 ] );       
                    min_text.text( ui.values[ 0 ] );
                    $(".tt-min").text( ui.values[ 0 ] );
                    max_text.text( ui.values[ 1 ] );
                    $(".tt-max").text( ui.values[ 1 ] );

                }
            });
            (cur_min_val == min_val) ? min_input.val('') : min_input.val( cur_min_val );
            (cur_max_val == max_val) ? max_input.val('') : max_input.val( cur_max_val );
            min_text.text( cur_min_val );
            $(".tt-min").text( cur_min_val );
            max_text.text( cur_max_val );
            $(".tt-max").text( cur_max_val );

            min_input.change(function() {
            if($(this).val()=='' || (($(this).val()*1) <= min_val) )  {
                $(this).val('');
                obj.slider( "values", 0, min_val );
                return true;
            }
            if(max_input.val()=="" && (($(this).val()*1) > max_val) ) {
                $(this).val(max_val);
                obj.slider( "values", 0, max_val );
                return true;
            }
            if(max_input.val()!="" && (($(this).val()*1) > max_input.val()*1) ) {
                $(this).val(max_input.val());       
            }
            obj.slider( "values", 0, $(this).val()*1 );
            
            });
            max_input.change(function() {
                if($(this).val()=='' || (($(this).val()*1) >= max_val) ) {
                    $(this).val('');
                    obj.slider( "values", 1, max_val );
                    return true;
                }
                if(min_input.val()=="" && (($(this).val()*1) < min_val) ) {
                    $(this).val(min_val);
                    obj.slider( "values", 1, min_val );
                    return true;
                }
                if(min_input.val()!="" && (($(this).val()*1) < min_input.val()*1) ) {
                    $(this).val(min_input.val());    
                }
                obj.slider( "values", 1, $(this).val()*1 );
            });
        });
        
    }
    if ($(".slider-range").length) range_init();
    
    $(".filter-item").click(function(){   
        if(!$(this).hasClass("active")) {
            closeBubble();
            $(this).find(".variants").css("display","table");
            $(this).addClass("active");
            if( $(this).position().left > 480 ) {
                $(this).find(".variants").css("right","0");
            } else $(this).find(".variants").css("left","0");
        } else closeBubble();
    });

    $(".variants input").change(function(){
        var obj = $(this).closest(".filter-item").find("input:checked"),
        input = $(this).closest(".filter-item").find(".input"),
        text=[];
        if(obj.length != 0) {
            obj.each(function(index, item){
                console.log($(item).siblings("span").text());
                text.push($(item).siblings("span").text());
            });
            input.html(text.join(",&nbsp;")+"<span></span>");
        } else {
            input.html("<span></span>");
        }        
    });

    var active,open;
    function closeBubble(active){
        if( typeof active == "undefined" ) active = $(".filter-item.active");
            active.removeClass('active');
            active.find('.variants').hide();
    }

    $("body").on("mouseup",".variants *,.filter-item *",function(){
        open = true;
    });

    $("body").on("mousedown",function() {
        open = false;
    }).bind("mouseup",function(){
        if( !open )
            closeBubble();
    });


    $(".goods .gradient-grey").click(function(){
        window.location.assign($(this).attr("data-href"));
    });
    $(".filter-cont .ui-slider-handle:eq(0)").prepend("<span class='price-tt tt-min'>"+$(".slider-range").attr("data-min")+"</span>");
    $(".filter-cont .ui-slider-handle:eq(1)").prepend("<span class='price-tt tt-max'>"+$(".slider-range").attr("data-max")+"</span>");
   
});