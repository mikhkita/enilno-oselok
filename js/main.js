$(document).ready(function(){	

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
        $(".b-2").css({
            "min-height" : myHeight - 248
        });
    }
    $(window).resize(resize);
    resize();

});