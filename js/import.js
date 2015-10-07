$(document).ready(function(){
    var progress = new KitProgress("#D26A44",2);
    progress.endDuration = 0.3,
    errors = 0;

    // Первый шаг --------------------------------------------------- Первый шаг
    if( $("#import-step1").length ){
        $("#import-step1").submit(function(){
            $("#GoodTypeId").val($(".b-choosable-values li.selected").attr("data-id"));
        });

        $("input[name='excel_name']").change(function(){
            if( $(this).val() != "" ){
                $(".b-import-butt").removeClass("hidden");
            }else{
                $(".b-import-butt").addClass("hidden");
            }
        });
    }

    $(".b-choosable-values li").click(function(){
        $(".b-choosable-values li").removeClass("selected");
        $(this).addClass("selected");
    });
    // Первый шаг --------------------------------------------------- Первый шаг

    // Второй шаг --------------------------------------------------- Второй шаг
    function data_set() {
        for (var i = 0; i < $("#attr-list li").length; i++) {
            $("#imp-sort li:eq("+i+") input").val($("#attr-list li").eq(i).attr("data-id"));
        }
    };
    $( "#imp-sort li" ).draggable({ revert: true, revertDuration:false, axis: "y", containment: "parent"});
    $( "#imp-sort li" ).droppable({
        accept: "#imp-sort li",
        create: data_set,
        drop: function( event, ui ) {
            var temp = ui.draggable.html();
            ui.draggable.html($(this).html());
            $(this).html(temp);
            data_set();
        }
    });
    // Второй шаг --------------------------------------------------- Второй шаг

    // Третий шаг --------------------------------------------------- Третий шаг
    if( $(".b-import-preview-table").length ){
        var log = $(".b-log"),
            count,
            ready = 0;
        $(".b-import-butt").click(function(){
            startImport();
            return false;
        });
    }
    function startImport(){
        count = $(".b-import-preview-table tr").length-1,
        showImport();
        sendNext();
    }
    function endImport(){
        $(".progress").addClass("ready");
        setLog("Импорт завершен. Ошибок: "+errors);
    }
    function showImport(){
        $(".b-import").show();
        $(".b-preview").hide();
    }
    function sendNext(){
        if( $(".b-import-preview-table tr").eq(1).length ){
            var $tr = $(".b-import-preview-table tr").eq(1),
                data = $('<form>').append( $tr.clone() ).serialize();

            $tr.remove();

            $.ajax({
                type: "POST",
                url: $(".b-preview").attr("data-url"),
                data: data,
                success: function(msg){
                    var json = JSON.parse(msg);
                    setLog(json.message,json.result);
                },
                error: function(){
                    setLog("Ошибка в работе php-скрипта","error");
                },
                complete: function(){
                    ready++;
                    updateProgressBar();
                    sendNext();
                }
            });
        }
        
    }
    function setLog(string,result){
        if( result == "error" ) errors++;
        var li = $("<li>"+string+"</li>");
        if( typeof result == "string" )
            li.addClass(result);

        log.prepend(li);
    }
    function updateProgressBar(){
        var percent = Math.ceil(ready/count*100)+"%";
        $(".progress-bar").css("width",percent).html(percent);
        if( count == ready ){
            setTimeout(endImport,200);
        }
    }
    // Третий шаг --------------------------------------------------- Третий шаг

});