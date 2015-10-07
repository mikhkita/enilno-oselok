$(document).ready(function(){	

    function whenResize(){
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
        $(".b-content").css("min-height",myHeight-253);
    }
    $(window).resize(whenResize);
    whenResize();

    // var progress = new KitProgress("#FFF",2);
    var price_min_def = $( "#price_min" ).val()*1,
    price_max_def = $( "#price_max" ).val()*1,
    price_max = price_max_def,price_min=price_min_def,type,filter=0;
    if(location.search!='') {
        var price = decodeURIComponent(location.search.substr(1)).split('&');
        $.each( price, function( key, value ) {
            if(value.indexOf('price-min') + 1) price_min = value.split('=').pop()*1;
            if(value.indexOf('price-max') + 1) price_max = value.split('=').pop()*1;
            if(value.indexOf('type') + 1) type = value.split('=').pop()*1;
        });
        
    }
	$( "#slider-range" ).slider({
		range: true,
		min: price_min_def,
		max: price_max_def,
		values: [ price_min, price_max ],
		slide: function( event, ui ) {
			$( "#amount-l" ).text( ui.values[ 0 ] );
			$( "#amount-r" ).text( ui.values[ 1 ] );
            $( "#price-min" ).val( ui.values[ 0 ] );
            $( "#price-max" ).val( ui.values[ 1 ] );
            $("#filter-search").remove();
            setTo($(this));
		},
    change: function( event, ui ) {
        filter++;
         setTimeout(function() {
           filter--;
        }, 900);
        setTimeout(function() {
            showCount(filter);
        }, 1000);
    }
	});
	$( "#amount-l" ).text( $( "#slider-range" ).slider( "values", 0 ) );
	$( "#amount-r" ).text( $( "#slider-range" ).slider( "values", 1 ) );
    $( "#price-min" ).val( $( "#slider-range" ).slider( "values", 0 ) );
    $( "#price-max" ).val( $( "#slider-range" ).slider( "values", 1 ) );

	$(".fancy-img").fancybox({
        padding : 0,
        nextEffect : ( device.mobile() || device.tablet() )?"fade":"elastic",
        prevEffect : ( device.mobile() || device.tablet() )?"fade":"elastic"
    });
    $("body").on("click",".fancy-img-thumb", function(){
        $("#bg-img").css("background-image",$(this).parents("li").css("background-image"));
        $("#bg-img a").attr("href",$(this).attr("href"));
        return false;
    });

    $(".fancy-img-big").click(function(){
        // alert($(".fancy-img[href='"+$(this).attr("href")+"']").attr("href"));
        $(".fancy-img[href='"+$(this).attr("href")+"']").click();
        return false;
    });

    $("#filter label").click(function(){
        filter++;
        $("#filter-search").remove();
        setTo($(this));
        setTimeout(function() {
           filter--;
        }, 900);
        setTimeout(function() {
            showCount(filter);
        }, 1000);
        
    });

    function setTo(el){
        el.closest(".filter-cont").append('<div id="filter-search" class="filter-search"><input type="submit" value="Показать"><img src="/i/294.GIF"></div>');  
    }

    $("#go-back").click(function(){
        if(document.referrer) {
           window.history.back();
        } else if($(this).text().indexOf('Шины') + 1){
            window.location.assign("/shop");
        } else if ($(this).text().indexOf('Диски') + 1) {
            window.location.assign("/shop?type=2");
        }
    });
    function showCount() {
        if(filter==0) {
            $.ajax({
                type: 'GET',
                url: "/shop/index?countGood=true",
                data: $("#filter").serialize(),
                success: function(msg){ 
                    $("#filter-search span").remove();
                    $("#filter-search").append("<span>Товаров: "+msg+"</span>");
                    $("#filter-search img").hide();
                }
            }); 
        }
    }
    
    if( $("#yw0 .selected a").text()*1>3 && $("#yw0 li.page").eq(0).find("a").text()*1>1 ) {
        $("#yw0 .first").show();
    }
    if( ($("#yw0 li.page").eq(0).find("a").text()*1)>2 ) {
        $("#yw0 .first").show();
        $("<li class='first-points'>...</li>").insertAfter("#yw0 .first");
    }
    if( ($("#yw0 .last a").text()*1-$("#yw0 li.page").last().find("a").text()*1)==1 ) {
        $("#yw0 .last").show();
    }
    if( ($("#yw0 .last a").text()*1-$("#yw0 li.page").last().find("a").text()*1)>1 ) {
        $("#yw0 .last").show();
        $("<li class='last-points'>...</li>").insertBefore("#yw0 .last");
    }

    $(".b-items-sort li").click(function(){
        $(this).find("input").prop("checked",true);
        if($(this).hasClass("active")) {
            if($(this).hasClass("up")) $("input[name='sort[type]']").val("DESC"); else $("input[name='sort[type]']").val("ASC");
        } else $("input[name='sort[type]']").val("DESC");
        $("#filter").submit();
    });
});