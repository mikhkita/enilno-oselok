$(document).ready(function(){   
var progress = new KitProgress("#FFF",2);
    progress.endDuration = 0.3,
    changed = false,
    unloader = new Unloader();
    
    if( $(".b-select-z").length ){
        $(".b-select-z").change(function(){
            $(".b-table").hide();
            $(".b-table[data-id='"+$(this).val()+"']").show();
        });
    }

    $(".b-table input").change(function(){
        changed = true;
    });

    $("#b-matrix-form").submit(function(){
        var formData = form2js("b-matrix-form", ".", true);

        var json = JSON.stringify(formData);

        progress.setColor("#D26A44");
        progress.start(3);

        $.ajax({
            type: $("#b-matrix-form").attr("method"),
            url: $("#b-matrix-form").attr("action"),
            data: "json="+json,
            success: function(msg){
                progress.end();
                if( msg == 1 ){
                    changed = false;
                }else{
                    alert("Ошибка сохранения");
                }

            },
            error: function(){
                alert("Ошибка сохранения");
            }
        });

        return false;
    });

    // Подтверждение закрытия страницы
    function Unloader(){
        var o = this;
        this.unload = function(evt){
            if( changed ){
                var message = "Изменения не сохранены";
                if (typeof evt == "undefined") {
                    evt = window.event;
                }
                if (evt) {
                    evt.returnValue = message;
                }
                return message;
            }
        }
     
        this.resetUnload = function()
        {
            $(window).off('beforeunload', o.unload);
        }
     
        this.init = function()
        {
            $(window).on('beforeunload', o.unload);
     
            $('a').on('click', o.unload);
            $(document).on('submit', 'form', o.resetUnload);
        }
        this.init();
    }
});