
$(document).ready(function(){
    var progress = new KitProgress("#D26A44",2);
    progress.endDuration = 0.3,
    errors = 0;
        var log = $(".b-log"),
            count,
            ready = 0,
            arr;

    $("#link").submit(function(){    
            var str=$("textarea[name=link]").val();
            // str = str.substr(5);
            // str = decodeURIComponent(str);
            arr = str.split('\n'); 
            startImport();
            return false;
        });

    function startImport(){
        count = arr.length;
        showImport();
        sendNext();
    }
    function endImport(){
        $(".progress").addClass("ready");
        setLog("Копирование завершено. Ошибок: "+errors);
    }
    function showImport(){
        $(".b-import").show();
        $("#link").hide();
    }
    function sendNext(){

        if(arr[ready] && arr[ready].indexOf("http")+1) {
            $.ajax({
                type: 'POST',
                url: "/admin/link/",
                data: {link: arr[ready]},
                success: function(msg){
                    if(msg=="1") {
                        setLog("Изображения по ссылке "+arr[ready]+" скопированы","success");
                    } else if(msg=="2"){
                        setLog("Изображения по ссылке "+arr[ready]+" были скопированы раннее","success");
                    } else {
                        setLog("Изображения по ссылке "+arr[ready]+" не скопированы","error");
                    }
                },
                error: function(){
                    setLog("Ошибка в работе php-скрипта, изображения по ссылке "+arr[ready]+" не скопированы","error");
                },
                complete: function() {
                    ready++; 
                    updateProgressBar();                 
                    sendNext();                  
                } 
            });
        } else if(typeof arr[ready] != 'undefined') {
                ready++;
                updateProgressBar();
                sendNext();
            }       
        
    }
    function setLog(string,result){
        if( result == "error" ) errors++;
        var li = $("<li>"+string+"</li>");
        log.prepend(li);
    }
    function updateProgressBar(){
        var percent = Math.ceil(ready/count*100)+"%";
        $(".progress-bar").css("width",percent).html(percent);
        if( count == ready){
            setTimeout(endImport,200);
        }
    }
});
