$(document).ready(function(){
    $("#b-filter input").change(function(){
        var index = $(this).parents("td").index();
        
        filterThis(index);

        $("#target tr").removeClass("hidden");
        $("#target tr .hide").parents("tr").addClass("hidden");
        $(".b-show-count span").html($("#target tr").length-$("#target tr .hide").parents("tr").length);
    });

    function filterThis(index){
        var filterArr = {};

        $("#b-filter td").eq(index).find("input").each(function(){
            if( $(this).prop("checked") ) filterArr[$(this).val()] = true;
        });

        console.log(filterArr);

        $("#target tr td:nth-child("+(index+1)+")").each(function(){
            var td = $(this),
                tmpArr = td.find("p").text().trim().split("/");
            td.addClass("hide").removeClass("show");

            for( var i in tmpArr ){
                if( filterArr[tmpArr[i]] ){
                    td.removeClass("hide").addClass("show");
                    return true;
                }
            }
        });
        if( !Object.keys(filterArr).length ) $("#target tr td:nth-child("+(index+1)+")").removeClass("hide").addClass("show");
    }

    $("#target tr").click(function(){
        $(this).toggleClass("selected");
    });

    $(".b-select-all").click(function(){
        $("#target tr:not(#target tr.hidden)").addClass("selected");
        return false;
    });

    $(".b-select-none").click(function(){
        $("#target tr:not(#target tr.hidden)").removeClass("selected");
        return false;
    });
});