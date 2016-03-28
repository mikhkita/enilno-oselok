$(document).ready(function(){   
    var myWidth,myHeight,
        big = 3,
        nowBig = 1,
        isMobile = device.mobile();

    // isMobile = true;
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

        if( isMobile )
            $(".variants ul").css("height",myHeight-280);
        $(".b-content").css("min-height",myHeight-$(".b-header").height()-$(".b-footer").height());
    }

    $(window).resize(resize);
    resize();

    var blocked = false,blocked_s = false;

    if( device.mobile() )
        new FastClick(document.body);

    if($('#goods li:eq(-1)').attr("data-last") == 0) {
        if($("#similar").length) $("#similar").show();    
    } 
    if($('#goods li:eq(-1)').attr("data-last") != 0) {
        $(".load").css("display","inline-block"); 
    }

    var nowBig = ( myWidth > 767 ) ? 5 : 2;
    var drag = ( myWidth > 767 ) ? false: true;

    $('#similar-slider').slick({
        slide: 'li',
        slidesToShow: nowBig,
        draggable: drag,
        slidesToScroll: 1,
        prevArrow: "<span class='b-sim-nav gradient-lightBlack b-sim-left'></span>",
        nextArrow: "<span class='b-sim-nav gradient-lightBlack b-sim-right'></span>"    
    });

    function whenScroll(){
        if( $(".b-fixed-top").length ){
            var scroll = ((document.documentElement && document.documentElement.scrollTop) || document.body.scrollTop);
            if( scroll > $(".b-relative-top").offset().top+$(".b-relative-top").height() ){
                $(".b-fixed-top").addClass("shown");
            }else{
                $(".b-fixed-top").removeClass("shown");
            }
        }
        if($(".b-category #goods").length) {
            if($("body").scrollTop() > ($("#goods").offset().top+$("#goods").height()-myHeight*3)) {
                if($('#goods li:eq(-1)').attr("data-last") != 0 && !blocked) {
                    blocked = true;
                    $.ajax({
                        type: "GET",
                        url: window.location.href,
                        data:  { partial: true, last: $('#goods li:eq(-1)').attr("data-last")},
                        success: function(msg){
                            $("#goods").append(msg);
                            fancyInit();
                            if($('#goods li:eq(-1)').attr("data-last") == 0) {
                                $(".load").hide();
                                if($("#similar").length) $("#similar").show();    
                            } 
                            blocked = false;
                        }
                    });
                }  
            }
        }
    }
    $(window).scroll(whenScroll);
    $('body').bind('touchmove', whenScroll);
    whenScroll();

    $( document ).mouseleave(function() {
        if(!$.cookie('exitPopup')) {
            $("span.exit").click();
            $.cookie('exitPopup',1,{ expires: 30});  
        }
    });
    
    $(window).load(function() {
        var i = 0;
        $(".after-load-back").each(function(){
            var $this = $(this);
            setTimeout(function(){
                $this.attr("style",$this.attr("data-style"));
            },10*i);
            i++;
        });
        $(".after-load").fadeIn(300);
        // $(".after-load").removeClass("after-load");

        $(".b-content").css("min-height",myHeight-$(".b-header").height()-$(".b-footer").height());
    });

    $( ".city-tabs" ).tabs({
        active: false,
        collapsible :true
    });
    $(".city-tabs>ul.ui-corner-all li").click(function(){
        if ($(this).hasClass("ui-state-active")) {
            $(".city-top h4 span").show();
        } else $(".city-top h4 span").hide();
    });
    
    $(".city-select").select2({
        language: "ru",
        placeholder: "Или укажите в поле...",
        allowClear: true
    });

    $(".city-select").change(function(){
        $(".b-city-link").attr("href",$(this).val());
    });

    if($(".city-popup-show").length) {
        $("a[data-block='#b-popup-city']").click();
    }

    // $("#city-form input[name='url']").val(window.location.href);
    // $(".popup-cities li a").click(function() {    
    //     $("select[name='city']").val($(this).text());
    //     $("#city-form").submit();
    //     return false;
    // });
    
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
    $( "#shipping" ).tabs();    
        
    $( ".b-block.main-tabs" ).tabs({
        load: function( event, ui ) {
            $(".good-detail .b-block .load-tabs").show();
        },
        activate: function( event, ui ) {
            $( ".popular-good.main-tabs" ).tabs( "option", "active", $(".b-block.main-tabs").tabs('option', 'active') );
        }
    });

    $( ".popular-good.main-tabs" ).tabs({
        activate: function( event, ui ) {
            $( ".b-block.main-tabs" ).tabs( "option", "active", $(".popular-good.main-tabs").tabs('option', 'active') );
        }
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
            cur_max_val = $(this).attr("data-max-cur") ? $(this).attr("data-max-cur")*1 : max_val;
            obj.slider({
                range: true,
                min: min_val,
                max: max_val,
                values: [ cur_min_val, cur_max_val ],
                slide: function( event, ui ) {
                    (ui.values[ 0 ] == min_val) ? min_input.val('') : min_input.val( Math.round(ui.values[ 0 ]/100)*100 );
                    (ui.values[ 1 ] == max_val) ? max_input.val('') : max_input.val( Math.round(ui.values[ 1 ]/100)*100 );  
                    ui.values[ 0 ] = Math.round(ui.values[ 0 ]/100)*100;
                    ui.values[ 1 ] = Math.round(ui.values[ 1 ]/100)*100;
                    min_text.text( ui.values[ 0 ] );
                    obj.closest(".slide-type").find(".tt-min").text( ui.values[ 0 ] );
                    max_text.text( ui.values[ 1 ] );
                    obj.closest(".slide-type").find(".tt-max").text( ui.values[ 1 ] );

                },
                change: function( event, ui ) {  
                    (ui.values[ 0 ] == min_val) ? min_input.val('') : min_input.val( Math.round(ui.values[ 0 ]/100)*100 );
                    (ui.values[ 1 ] == max_val) ? max_input.val('') : max_input.val( Math.round(ui.values[ 1 ]/100)*100 ); 
                    ui.values[ 0 ] = Math.round(ui.values[ 0 ]/100)*100;
                    ui.values[ 1 ] = Math.round(ui.values[ 1 ]/100)*100;      
                    min_text.text( ui.values[ 0 ] );
                    obj.closest(".slide-type").find(".tt-min").text( ui.values[ 0 ] );
                    max_text.text( ui.values[ 1 ] );
                    obj.closest(".slide-type").find(".tt-max").text( ui.values[ 1 ] );

                }
            });
            (cur_min_val == min_val) ? min_input.val('') : min_input.val(Math.round(cur_min_val/100)*100);
            (cur_max_val == max_val) ? max_input.val('') : max_input.val(Math.round(cur_max_val/100)*100);
            cur_min_val = Math.round(cur_min_val/100)*100;
            cur_max_val = Math.round(cur_max_val/100)*100;
            min_text.text( cur_min_val );
            obj.closest(".slide-type").find(".tt-min").text( cur_min_val );
            max_text.text( cur_max_val );
            obj.closest(".slide-type").find(".tt-max").text( cur_max_val );

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
    
    $(".filter-item .input").click(function(){
        $this = $(this).parent();   
        if(!$this.hasClass("active")) {
            closeBubble();

            if( isMobile ){
                $this.find(".variants").addClass("active").fadeIn(300);
                TweenLite.to($this.find("h3,.variants ul,a.b-variants-close"), 0, { y : 150, opacity: 0, ease : Cubic.easeOut } );
                TweenLite.to($this.find("h3"), 0.4, { y : 0, opacity: 1, delay: 0.1, ease : Cubic.easeOut } );
                TweenLite.to($this.find(".variants ul"), 0.4, { y : 0, opacity: 1, delay: 0.2, ease : Cubic.easeOut } );
                TweenLite.to($this.find("a.b-variants-close"), 0.4, { y : 0, opacity: 1, delay: 0.3, ease : Cubic.easeOut } );
            }else{
                $this.find(".variants").addClass("active");
            }

            $this.addClass("active");
            if( $this.position().left > 480 ) {
                $this.find(".variants").css("right","0");
            } else $this.find(".variants").css("left","0");
            stroll.bind( $this.find(".variants ul") );
        } else closeBubble();
    });

    if( isMobile ){
        var touchstart = null,
            touchmove = 0;
        $(".variants ul li").on("touchstart",function(){
            touchstart = $(this).find("input").attr("id");
        }).on("touchend",function(){
            if( touchstart == $(this).find("input").attr("id") && touchmove < 3 ) $(this).find("input").click();
        });

        $(".filter-item .variants").on('touchmove', function(e){ 
            touchmove++;
            e.preventDefault(); 
        });

        $("body").on("touchend",function(){
            touchmove = 0;
        });
    }else{
        $(".variants ul li").on("click",function(){
            $(this).find("input").click();
        })
    }

    $(".variants input").change(function(){
        var obj = $(this).closest(".filter-item").find("input:checked"),
        input = $(this).closest(".filter-item").find(".input"),
        text=[];
        if(obj.length != 0) {
            obj.each(function(index, item){
                text.push($(item).siblings("span").text());
            });
            input.html(text.join(",&nbsp;")+"<span></span>");
        } else {
            input.html("<span></span>");
        }        
    });

    // $(".filter-item select").change(function(){
    //     var obj = $(this).closest(".filter-item").find("option:selected"),
    //     input = $(this).closest(".filter-item").find(".input"),
    //     text=[];
    //     if(obj.length != 0) {
    //         obj.each(function(index, item){
    //             text.push($(item).text());
    //         });
    //         input.html(text.join(",&nbsp;")+"<span></span>");
    //     } else {
    //         input.html("<span></span>");
    //     }        
    // });

    $(".variants input").change();
    // $(".filter-item select").change();

    var active,open;
    function closeBubble(active){
        if( typeof active == "undefined" ) active = $(".filter-item.active");
        active.removeClass('active');
        if( isMobile ){
            active.find('.variants').fadeOut(300).removeClass("active");
        }else{
            active.find('.variants').removeClass("active");
        }
    }

    $("body").on("mouseup",".variants *,.filter-item .input",function(){
        open = true;
    });

    $("body").on("mousedown",function() {
        open = false;
    }).bind("mouseup",function(){
        if( !open )
            closeBubble();
    });

    $(".b-variants-close").on("click touchstart",function(){
        closeBubble();
        return false;
    });

    $("#goods .gradient-grey .b-orange-butt").click(function(){
        return false;
    });

    $(".b-sort ul li").click(function(){
        $(this).find("input").prop("checked",true);
        if($(this).hasClass("active")) {
            if($(this).hasClass("up")) $("input[name='sort[type]']").val("ASC"); else $("input[name='sort[type]']").val("DESC");
        } else $("input[name='sort[type]']").val("ASC");
        $("#filter").submit();
    });

    $(".filter-cont .ui-slider-handle:eq(0)").prepend("<span class='price-tt tt-min'>"+(Math.round($(".slider-range:eq(0)").attr("data-min-cur")/100)*100)+"</span>");
    $(".filter-cont .ui-slider-handle:eq(2)").prepend("<span class='price-tt tt-min'>"+(Math.round($(".slider-range:eq(1)").attr("data-min-cur")/100)*100)+"</span>");
    $(".filter-cont .ui-slider-handle:eq(4)").prepend("<span class='price-tt tt-min'>"+(Math.round($(".slider-range:eq(2)").attr("data-min-cur")/100)*100)+"</span>");

    $(".filter-cont .ui-slider-handle:eq(1)").prepend("<span class='price-tt tt-max'>"+(Math.round($(".slider-range:eq(0)").attr("data-max-cur")/100)*100)+"</span>");
    $(".filter-cont .ui-slider-handle:eq(3)").prepend("<span class='price-tt tt-max'>"+(Math.round($(".slider-range:eq(1)").attr("data-max-cur")/100)*100)+"</span>");
    $(".filter-cont .ui-slider-handle:eq(5)").prepend("<span class='price-tt tt-max'>"+(Math.round($(".slider-range:eq(2)").attr("data-max-cur")/100)*100)+"</span>");
    
    customHandlers["category_buy"] = function(el){
        var popup = $(el.attr("data-block"));

        popup.find("input[name='subject']").val("Покупка "+$(el).parent().find("h4").text());

        $("#good").val($(el).parent().find("h4").text());
        $("#good-url").val(window.location.host+$(el).closest(".params-cont").find(".params-cont-a").attr("href"));
    }

    customHandlers["detail_buy"] = function(el){
        var popup = $(el.attr("data-block"));

        popup.find("input[name='subject']").val("Покупка "+$("#buy-title").text());

        $("#good").val($("#buy-title").text());
        $("#good-url").val(window.location.href);
    }

    $('.detail-slider-for').slick({
        slidesToShow: 1,
        slidesToScroll: 1,
        arrows: false,
        fade: (isMobile)?false:true,
    });

    $(".detail-thumb li").click(function(){
        $('.detail-slider-for').slick('slickGoTo',$(this).index(), false);
    });

    $(".b-burger").click(openMenu);
    $(".b-mobile-menu, .b-mobile-menu a").click(closeMenu).on('touchmove', function(e){ e.preventDefault(); });

    function openMenu(){
        $("body").addClass("mobile-menu-opened");
        TweenLite.to($(".b-mobile-menu li,.b-menu-call"), 0, { y : 150, opacity: 0, ease : Cubic.easeOut } );
        $(".b-mobile-menu").fadeIn(200);
        $(".b-mobile-menu li").each(function(){
            TweenLite.to($(this), 0.4, { y : 0, opacity: 1, delay: 0.08*$(this).index()+0.1, ease : Cubic.easeOut } );
        });
        TweenLite.to($(".b-menu-call"), 0.4, { y : 0, opacity: 1, delay: 0.08*($(".b-mobile-menu li").length+1)+0.1, ease : Cubic.easeOut } );
        return false;
    }

    var togLink = false;
    function closeMenu(){
        $("body").removeClass("mobile-menu-opened");
        $(".b-mobile-menu").fadeOut(300);
        if( !$(this).hasClass("b-mobile-menu-a") && !$(this).hasClass("b-menu-call") && togLink == false ){
            return false;
        }else{
            togLink = true;
        }
    }

    $(".tire-type input").change(function(){
        if($(this).prop("checked")) {
            $(this).parent().addClass("active");
        } else $(this).parent().removeClass("active");
    });
    $(".tire-type input").change();


    $(".detail-price h5").click(function() {
        $( ".main-tabs" ).tabs( "option", "active", 1 );
    });

    var focusIn = false,
        mouseIn = false;
    $("#search").keydown(function(e){
        if( [38, 40].indexOf(e.keyCode) !== -1 ){
            if( e.keyCode == 38 ){
                if( $(".b-search-results li a.focus ").length ){
                    var index = $(".b-search-results li a.focus").removeClass("focus").parents("li").index();
                    if( index != 0){
                        $(".b-search-results li").eq(index-1).find("a").addClass("focus");
                    }
                }else{
                    $(".b-search-results li:last a").addClass("focus");
                }
            }else{
                if( $(".b-search-results li a.focus").length ){
                    $(".b-search-results li").eq($(".b-search-results li a.focus").removeClass("focus").parents("li").index()+1).find("a").addClass("focus");
                }else{
                    $(".b-search-results li").eq(0).find("a").addClass("focus");
                }
            }
        }else if( e.keyCode == 13 ){
            if( $(".b-search-results li a.focus").length ){
                window.location.href = $(".b-search-results li a.focus").attr("href");
            }else if( $(".b-search-results li a").length ){
                window.location.href = $(".b-search-results li:first a").attr("href");
            }
            return false;
        }
    });

    $("body").on("mouseover", ".b-search-results", function(){
        $(".b-search-results li a.focus").removeClass("focus");
        mouseIn = true;
    }).on("mouseleave", ".b-search-results", function(){
        if( !focusIn ) $(".b-search-results").fadeOut(150);
        mouseIn = false;
    });

    $("body").on("mousedown", ".b-search-results li a", function(){
        
    })

    $(".b-search-form").submit(function(){
        $("#search").focus();
        return false;
    });

    $("#search").keyup(function(e){
        if( [36, 38, 39, 40, 13].indexOf(e.keyCode) == -1 ){
            $form = $(this).parents("form");

            if( $(this).val() == "" ){
                $(".b-search-results").fadeOut(150);
                return false;
            }

            if( $(".b-search-results li").length < 1 )
                $(".b-search-results").fadeIn(150).html("<li><span>Загрузка...</span></li>");
            $.ajax({
                type: "GET",
                url: $form.attr("action"),
                data: $form.serialize(),
                success: function(msg){
                    // console.log(msg);
                    $(".b-search-results").fadeIn(150).html(msg);
                }
            });
        }
    });

    $("#search").focus(function(){
        focusIn = true;
        if( $(".b-search-results li").length )
            $(".b-search-results").fadeIn(150);
    }).blur(function(){
        focusIn = false;
        if( !mouseIn ) $(".b-search-results").fadeOut(150);
    });

    $(".b-mobile-search-top-icon, .b-search-icon").click(openSearch);
    $("body").on("click", ".b-search-results a", closeSearch);
    $("body").on("click", ".b-search-close", function(){
        closeSearch();
        return false;
    });
    $(".b-search-form").on('touchmove', function(e){ e.preventDefault(); });

    function openSearch(){
        $("body").addClass("mobile-menu-opened");
        setTimeout(function(){
            $($("input#search")[0]).focus();
        },400);
        $(".b-search-form").fadeIn(200);
        $(".b-sub-menu.b-fixed-top").css({
            top: -90
        });
        return false;
    }

    var togLink = false;
    function closeSearch(){
        if( isMobile ){
            $(".b-search-form").fadeOut(300);
            setTimeout(function(){
                $(".b-sub-menu.b-fixed-top").css({
                    top: 0
                });
            },200);
            // return false;
        }else{
            $(".b-search-results").fadeOut(300);
        }
    }

    $(".b-clear-filter").click(function(){
        $("#filter input[type='checkbox']").prop("checked", false);
        $("#filter .min-val").val("").trigger("change");
        $("#filter .max-val").val("").trigger("change");
        $("label.active").removeClass("active");
        $("#filter .input").html("<span></span>");
        $(".b-clear-filter").hide();
        return false;
    });

    // $("#filter input").change(function(){
    //     if( issetFilterValues() ){

    //     }
    // });

    if( $("#filter").length && issetFilterValues() ){
        alert();
        $(".b-clear-filter").show();
    }

    function issetFilterValues(){
        $("#filter input").each(function(){
            if( $(this).val() != "" ) return true;
        });

        return false;
    }

});