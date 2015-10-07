$(document).ready(function(){	

    if( $(".b-house-item").length > 3 ){
        $(".b-house-slider").slick({
            dots: false,
            infinite: true,
            speed: 300,
            slidesToShow: 3,
            centerMode: true,
            variableWidth: true
        });

        $(".b-nav-left").click(function(){
            $(".slick-prev").click();
            return false;
        });
        $(".b-nav-right").click(function(){
            $(".slick-next").click();
            return false;
        });
    }else{
        $(".b-house-item").css("float","left");
        setTimeout(function(){
            var w = 0;
            $(".b-house-item").each(function(){
                w += ($(this).width()+20);
            });
            $(".b-house-slider").css({
                "display" : "inline-block",
                "left" : "50%",
                "margin-left" : -w/2
            });
        },50);
        $(".b-nav").hide();
    }

    function resize(){
        var myHeight;
        if( typeof( window.innerWidth ) == 'number' ) {
            myHeight = window.innerHeight;
        } else if( document.documentElement && ( document.documentElement.clientWidth || 
        document.documentElement.clientHeight ) ) {
            myHeight = document.documentElement.clientHeight;
        } else if( document.body && ( document.body.clientWidth || document.body.clientHeight ) ) {
            myHeight = document.body.clientHeight;
        }
        $(".b-house").css({
            "height" : myHeight - 248
        });
    }
    $(window).resize(resize);
    resize();

});