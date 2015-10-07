$(document).ready(function(){   

    if( $("#accordion").length ){
        $(function() {
            $( "#accordion" ).accordion({
                heightStyle: "content",
                active: $("#accordion h3.b-active").attr("data-id")*1
            }).fadeIn(300);
        });
    }

    $("#accordion h3").click(function(){
        var $this = $(this);
        $(".b-highlight").removeClass("b-highlight");
        $this.addClass("b-highlight");
        $this.next("div").addClass("b-highlight");
    });

    var time;

    time = setInterval(function(){
        if( $(".ui-accordion-header-active").length ){
            $(".ui-accordion-header-active").addClass("b-highlight");
            $(".ui-accordion-header-active").next("div").addClass("b-highlight");
            clearInterval(time);
        }
    },30);

    function resize(){
        if( typeof( window.innerWidth ) == 'number' ) {
            myHeight = window.innerHeight;
        } else if( document.documentElement && ( document.documentElement.clientWidth || 
        document.documentElement.clientHeight ) ) {
            myHeight = document.documentElement.clientHeight;
        } else if( document.body && ( document.body.clientWidth || document.body.clientHeight ) ) {
            myHeight = document.body.clientHeight;
        }
        var myHeight1 = $(".b-blog-cont .b-text").height()+100;

        $(".b-blog-cont,.b-blog").css({
            "min-height" : ( (myHeight - 248) > myHeight1 )?(myHeight - 248):(myHeight1)
        });
    }
    $(window).resize(resize);
    setTimeout(resize,500);
    setTimeout(resize,50);
    setTimeout(resize,0);
    // resize();

    // $(".b-blog-cont").resize(function(){
    //     $(".b-blog-cont .right").css({
    //         "height" : $(".b-blog-cont").height()
    //     });
    // });

});