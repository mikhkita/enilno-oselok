$(document).ready(function(){   

    var fancyTemplates = {
        wrap: '<div class="fancybox-wrap fancybox-wrap-1" tabIndex="-1"><div class="fancybox-skin"><div class="fancybox-outer"><div class="fancybox-inner"></div></div></div></div>'
    };

    $(".b-cabinet").fancybox({
        padding : 0,
        content : $("#b-popup-1"),
        tpl : fancyTemplates,
        margin: 35 ,
        beforeShow: function(){
            
        }
    });

});