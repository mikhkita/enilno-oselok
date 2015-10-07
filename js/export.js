$(document).ready(function(){
    
    // Бинд на открытие окна редактирования или создания шаблона для экспорта
    customHandlers["exportBeforeShow"] = function($form){
        $("#export-good-type-id").change(function(){
            $("#sortable1,#sortable2").html("");
            $("#sortable1").load($form.attr("data-getfieldsurl")+"?goodTypeId="+$(this).val(),function(){
                customHandlers["sortList"]();
            });
        });
    }

    $(".b-export-preview").parents("form").submit(function(){
    	var str = [];
		$(this).find("tr.selected").each(function(){
			str.push($(this).attr("data-id"));
		});
		$("#ids").val(str.join(","));
    });

   	$(".b-dynamic-values li").click(function(){
   		$(this).toggleClass("selected");
   	});

   	$(".b-dynamic-values").parents("form").submit(function(){
   		var ret = true;
   		$(this).find(".b-dynamic").each(function(){
   			if(!$(this).find(".b-dynamic-values li.selected").length){
   				$(this).find(".b-error").show();
   				ret = false;
   			}else{
   				$(this).find(".b-error").hide();

   				var str = [];
   				$(this).find(".b-dynamic-values li.selected").each(function(){
   					str.push($(this).attr("data-id"));
   				});
   				$(this).find("input").val(str.join(","));
   			}
   		});
   		return ret;
   	});

});