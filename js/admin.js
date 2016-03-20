var customHandlers = [];
$(document).ready(function(){  

    var myWidth,
        myHeight,
        title = window.location.href,
        titleVar = ( title.split("localhost").length > 1 )?4:3,
        progress = new KitProgress("#FFF",3),
        shift = false;

    progress.endDuration = 0.3;

    title = title.split(/[\/#?]+/);
    title = title[titleVar];

    $(".yiiPager .page.selected").click(function(){return false;});

    $(".modules li[data-name='"+title+"'],.modules li[data-nameAlt='"+title+"']").addClass("active");    

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
        $("body,html").css("height",myHeight);
        $(".main").css("height",myHeight-50);
        sectionNavResize();
    }
    $(window).resize(whenResize);
    whenResize();

    if( $.cookie('textarea-rows') ) $("#b-textarea-rows").val($.cookie('textarea-rows'));

    $("body").on("change","#b-textarea-rows",changeText);

    changeText();

    function changeText(){
        $("table textarea").attr("rows",$("#b-textarea-rows").val());
        $.cookie('textarea-rows',$("#b-textarea-rows").val(), { expires: 7, path: '/' });
    }

    function bindFancy(){
        $(".fancy-img").fancybox({
            padding : 0
        });

        $(".fancy").each(function(){
            var $popup = $($(this).attr("data-block")),
                $this = $(this);
            $this.fancybox({
                padding : 0,
                content : $popup,
                helpers: {
                    overlay: {
                        locked: true 
                    }
                },
                beforeShow: function(){
                    $popup.find(".custom-field").remove();
                    if( $this.attr("data-value") ){
                        var name = getNextField($popup.find("form"));
                        $popup.find("form").append("<input type='hidden' class='custom-field' name='"+name+"' value='"+$this.attr("data-value")+"'/><input type='hidden' class='custom-field' name='"+name+"-name' value='"+$this.attr("data-name")+"'/>");
                    }
                    if( $this.attr("data-beforeShow") && customHandlers[$this.attr("data-beforeShow")] ){
                        customHandlers[$this.attr("data-beforeShow")]($this);
                    }
                },
                afterShow: function(){
                    if( $this.attr("data-afterShow") && customHandlers[$this.attr("data-afterShow")] ){
                        customHandlers[$this.attr("data-afterShow")]($this);
                    }
                },
                beforeClose: function(){
                    if( $this.attr("data-beforeClose") && customHandlers[$this.attr("data-beforeClose")] ){
                        customHandlers[$this.attr("data-beforeClose")]($this);
                    }
                },
                afterClose: function(){
                    if( $this.attr("data-afterClose") && customHandlers[$this.attr("data-afterClose")] ){
                        customHandlers[$this.attr("data-afterClose")]($this);
                    }
                }
            });
        });
    }

    $(".ajax-update,.ajax-create").fancybox({
        type: "ajax",
        helpers: {
            overlay: {
                locked: true 
            },
            title : null
        },
        ajax : {
            complete: function(el,type) {
                if( type == "error" )
                    $(".fancybox-inner").html("<div class='b-popup' style='width: 600px;'><h2>Ошибка</h2>"+el.responseText+"</div>");
            }
        },
        padding: 0,
        margin: 30,
        beforeShow: function(){
            var $form = $(".fancybox-inner form");
            bindTableForm();
            bindForm($form);
            bindImageUploader();
            bindTinymce();
            bindSelectDynamic();
            bindAutocomplete();
            bindTooltip();
            bindDoubleList();
            bindVisual();
            if( $form.attr("data-beforeShow") && customHandlers[$form.attr("data-beforeShow")] ){
                customHandlers[$form.attr("data-beforeShow")]($form);
            }
        },
        afterClose:function(){
            unbindTinymce();
        },
        afterShow: function(){
            var $form = $(".fancybox-inner form");
            bindVariants();
            $(".fancybox-inner").find("input").eq(($form.attr("data-input"))?($form.attr("data-input")*1):0).focus();
        }
    });

    $(document).on("click",".ajax-refresh, .ajax-archive",function(){
        blockTr($(this).parents("tr"));
        $(".qtip").remove();
        progress.setColor("#D26A44");
        progress.start(3);

        $.ajax({
            url: $(this).attr("href"),
            success: function(msg){
                progress.end(function(){
                    $(".qtip").remove();
                    setResult(msg);
                });
            }
        });

        return false;
    });

    $(document).on("click",".b-delete-selected,.b-nav-delete",function(){
        progress.setColor("#D26A44");
        progress.start(3);
        var ids = [],
        obj  = $(this);
        if(obj.hasClass("b-delete-selected")) {
            for (var i = 0; i < $(".yahoo-list li.selected").length; i++) {
                ids.push($("li.selected").eq(i).attr("data-id"));
            }
        } else {
            ids.push(obj.closest("li").attr("data-id"));
        }
        $("#b-filter-form").append("<input type='hidden' name='delete' value='"+JSON.stringify(ids)+"'>");
        yahooBeforeAjax();
        $.ajax({
            method: "POST",
            url: document.location.href.replace("#","")+( (document.location.href.indexOf('?') == -1)?"?":"&" )+"partial=1",// Сделать ? или &
            data: $("#b-filter-form").serialize(),
            beforeSend: function() {
                $('.b-delete-selected').hide();
            },
            success: function(msg){
                $(".qtip").remove();
                progress.end(function(){
                    setResult(msg);
                    $(".qtip").remove();
                });
            }
        });

        return false;
    });
    
    function blockTr(el){
        el.addClass("b-refresh");
        el.click(function(){
            return false;
        });
    }

    $(document).on("click",".ajax-delete", function(){
        $(".qtip").remove();
        var warning = ($(this).attr("data-warning"))?$(this).attr("data-warning"):"Вы действительно хотите удалить</br>запись?";
        warning += ( $(this).parents(".b-table").attr("data-warning") )?"<br><b>"+$(this).parents(".b-table").attr("data-warning")+"</b>":"";
        $.fancybox.open({
            padding: 0,
            content: '<div class="b-popup b-popup-delete"><h1>'+warning+'</h1><div class="row buttons"><input type="button" class="b-delete-yes" value="Да"><input type="button" onclick="$.fancybox.close();" value="Нет"></div></div>'
        });
        bindDelete($(this).attr("href"),$(this).hasClass("not-ajax-delete"),(!$(this).hasClass("not-result")));
        return false;
    });

    function setResult(html){
        $(".b-main-center").html(html);
        $(".qtip").remove();

        setTimeout(function(){
            bindFilter();
            bindTooltip();
            bindAutocomplete();
            bindYahoo();
            bindFancy();
            yahooAfterAjax();

            $(".b-refresh").removeClass("b-refresh").addClass("b-refresh-out");
        },100);
    }

    function bindDelete(url,filter,isSetResult){
        $(document).unbind("keydown");
        $(document).bind("keydown",function( event ) {
            if ( event.which == 13 ) {
                $(".b-delete-yes").trigger("click");
                $(".qtip").remove();
                $(document).unbind("keydown");
                return false;
            }
        });

        $(".fancybox-inner .b-delete-yes").click(function(){
            progress.setColor("#FFF");
            progress.start(3);

            url = ( $(".b-main-center form").length && !filter ) ? (url+"&"+$(".b-main-center form").serialize()) : url;

            var data = null,
                method = "GET";

            if( filter ){
                data = $("#b-filter-form").serialize();
                method = "POST";
            }

            $.ajax({
                method: method,
                data: data,
                url: url,
                success: function(msg){
                    progress.end(function(){
                        if( msg != "" && isValidJSON(msg) ){
                            jsonHandler(msg);
                        }else{
                            if( isSetResult )
                                setResult(msg);
                        }
                    });
                    $.fancybox.close();
                }
            });    
        });
    }

    function isValidJSON(src) {
        var filtered = src;
        filtered = filtered.replace(/\\["\\\/bfnrtu]/g, '@');
        filtered = filtered.replace(/"[^"\\\n\r]*"|true|false|null|-?\d+(?:\.\d*)?(?:[eE][+\-]?\d+)?/g, ']');
        filtered = filtered.replace(/(?:^|:|,)(?:\s*\[)+/g, '');

        return (/^[\],:{}\s]*$/.test(filtered));
    }

    function bindFilter(){
        if( $(".b-main-center .b-filter").length ){
            $(".b-main-center form select, .b-main-center form input").bind("change",function(){
                var $form = $(this).parents("form");

                progress.setColor("#D26A44");
                progress.start(3);

                $.ajax({
                    url: "?partial=true&"+$form.serialize(),
                    success: function(msg){
                        progress.end(function(){
                            setResult(msg);
                            history.pushState(null, null, '?'+$form.serialize());
                        });
                    }
                });    
            });
            $(".b-main-center form").submit(function(){
                return false;
            });
            $(".b-clear-filter").click(function(){
                $(".b-main-center form select,.b-main-center form input").val("");
                $(".b-main-center form select,.b-main-center form input").eq(0).trigger("change");
                return false;
            });
            
        }
        $(".select2-filter").select2({
            placeholder: "",
            allowClear: true
        });
    }

    function bindForm($form){

        $(".select2").select2({
            placeholder: "",
            allowClear: true
        });

        $(".select2-all").click(function() {
            var element = $(this).siblings("select.select2");
            var selected = [];
            element.find("option").each(function(i,e){
                if( $(e).attr("value") != "-" )
                    selected[selected.length]=$(e).attr("value");
            });
            element.select2("val", selected);
        });
        $(".select2-none").click(function() {
            var element = $(this).siblings("select.select2");
            element.select2("val", "");
        });

        $(".select-city-group").click(function(){
            var element = $(this).siblings("select.select2");
            var selected = [];
            var ids = $(this).attr("data-ids").split(',');
            $(ids).each(function(i,val){
                selected[selected.length] = val;
            });
            element.find("option:selected").each(function(i,e){
                selected[selected.length]=$(e).attr("value");
            });
            element.select2("val", selected);
        });

        $form.validate({
            ignore: ""
        });

        $form.find("input.phone").mask('+7 (999) 999-99-99',{placeholder:"_"});
        $form.find("#datepicker").mask('99.99.9999',{placeholder:"_"});

        $( "#datepicker" ).datepicker( $.datepicker.regional[ "ru" ] );
        $( "#datepicker" ).datepicker();

        if($(".autocomplete-input").length) {
            var cities = [];
            $("#cities p").each(function() {
                cities.push($(this).text());
            }); 

            $( ".autocomplete-input" ).autocomplete({
                source: cities
            });  
        }


        $("#Customer_phone").keyup(function() {
            n = $(this).val().match( /\d/g );
            n = n ? n = n.length : 0;

            if( n == 11 ){
                $.ajax({
                    type: "GET",
                    url: $("#Customer-form").attr("data-url"),
                    data: {phone: $("#Customer_phone").val()},
                    success: function(msg){
                        $("#Customer-form").html(msg);
                    }
                });
            }
        });

        $("#Customer_phone").keyup();

        $(".numeric").numericInput({ allowFloat: true, allowNegative: true });

        $form.submit(function(e,a){
            if( $(this).attr("id") == "good-edit-form" ){
                $("#good-edit-form").find("input[type='number']").each(function(){
                    if( $(this).val() == "" ) $(this).val(0);
                });
            }
            tinymce.triggerSave();
            if( $(this).valid() && !$(this).find("input[type=submit]").hasClass("blocked") && !$(this).hasClass("blocked") ){
                var $form = $(this),
                    url = $form.attr("action"),
                    data;

                $(this).find("input[type=submit]").addClass("blocked");

                if( $form.attr("data-beforeAjax") && customHandlers[$form.attr("data-beforeAjax")] ){
                    customHandlers[$form.attr("data-beforeAjax")]($form);
                }

                data = $form.serialize();

                if( a == false ){
                    $form.find("input[type='text'],input[type='number'],textarea").val("");
                    $form.find("input").eq(0).focus();
                }

                progress.setColor("#FFF");
                progress.start(3);


                if( $(".b-main-center form").length ){
                    if( $(".b-main-center form").attr("id") != "b-matrix-form" ){
                        url = url+( (url.split("?").length>1)?"&":"?" )+$(".b-main-center form").serialize();
                    }
                }

                yahooBeforeAjax();

                $.ajax({
                    type: $form.attr("method"),
                    url: url,
                    data: data,
                    success: function(msg){
                        progress.end(function(){
                            $form.find("input[type=submit]").removeClass("blocked");
                            if(isValidJSON(msg)){
                                jsonHandler(msg);
                            }else{
                                if( msg != "none" ) setResult(msg);
                            }
                        });
                        if( a != false ){
                            $.fancybox.close();
                        }
                    }
                });
            }else{
                if( !$(this).attr("data-not-scroll") )
                    $(".fancybox-overlay").animate({
                        scrollTop : 0
                    },200);
                $("input.error,textarea.error").focus();
            }
            return false;
        });

        // $(".b-input-image").change(function(){
        //     if( $(this).val() != "" ){
        //         $(".b-input-image-add").addClass("hidden");
        //         $(".b-image-wrap").removeClass("hidden");
        //         $(".b-input-image-img").css("background-image","url('"+$(".b-input-image-img").attr("data-base")+"/"+$(this).val()+"')");
        //     }else{
        //         $(".b-input-image-add").removeClass("hidden");
        //         $(".b-image-wrap").addClass("hidden");
        //     }
        // });

        // // Удаление изображения
        // $(".b-image-delete").click(function(){
        //     $(".b-image-cancel").attr("data-url",$(".b-input-image").val())// Сохраняем предыдущее изображение для того, чтобы можно было восстановить
        //                         .show();// Показываем кнопку отмены удаления
        //     $(".b-input-image").val("").trigger("change");// Удаляем ссылку на фотку из поля
        // });

        // // Отмена удаления
        // $(".b-image-cancel").click(function(){
        //     $(".b-input-image").val($(".b-image-cancel").attr("data-url")).trigger("change")// Возвращаем сохраненную ссылку на изображение в поле
        //     $(".b-image-cancel").hide(); // Прячем кнопку отмены удаления                                 
        // });

    }

    function jsonHandler(msg){
        // alert(msg);
        var json = JSON.parse(msg);
        if( json.result == "success" ){
            switch (json.action) {
                case "delete":
                    $(json.selector).remove();
                    $(".b-sess-checkbox").eq(0).trigger("change");
                break;

                case "updateCronCount":
                    $(".b-place-state p").text(json.count);
                break;

                case "updateQueue":
                    liveUpdate();
                break;
            }
        }
    }

    function bindImageUploader(){
        $(".b-get-image").click(function(){
            $(".b-for-image-form").load($(this).attr("data-path"), {}, function(){
                $(".upload").addClass("upload-show");
                $(".b-upload-overlay").addClass("b-upload-overlay-show")
                $(".plupload_cancel,.b-upload-overlay,.plupload_save").click(function(){
                    $(".b-upload-overlay").removeClass("b-upload-overlay-show");
                    $(".upload").addClass("upload-hide");
                    setTimeout(function(){
                        $(".b-for-image-form").html("");
                    },400);
                    return false;
                });
                $(".plupload_save").click(function(){
                    $(".b-input-image").val($(".b-input-image-img").attr("data-path")+"/"+$("input[name='uploaderPj_0_tmpname']").val()).trigger("change");
                });
            });
        });
    }

    /* TinyMCE ------------------------------------- TinyMCE */
    function bindTinymce(){
        if( $("#tinymce").length ){
            tinymce.init({
                selector : "#tinymce",
                width: '700px',
                height: '500px',
                language: 'ru',
                plugins: 'image table autolink emoticons textcolor charmap directionality colorpicker media contextmenu link textcolor responsivefilemanager',
                skin: 'kit-mini',
                toolbar: 'undo redo bold italic forecolor alignleft aligncenter alignright alignjustify bullist numlist outdent indent link image',
                onchange_callback: function(editor) {
                    tinymce.triggerSave();
                    $("#" + editor.id).valid();
                },
                image_advtab: true ,
                external_filemanager_path:"/filemanager/",
                filemanager_title:"Файловый менеджер" ,
                external_plugins: { "filemanager" : "/filemanager/plugin.min.js"}
            });
        }
    }

    function unbindTinymce(){
        tinymce.remove();
    }
    /* TinyMCE ------------------------------------- TinyMCE */

    /* Preloader ----------------------------------- Preloader */
    function setPreloader(el){
        var str = '<div class="circle-cont">';
        for( var i = 1 ; i <= 3 ; i++ ) str += '<div class="c-el c-el-'+i+'"></div>';
        el.append(str+'</div>').addClass("blocked");
    }

    function removePreloader(el){
        el.removeClass("blocked").find(".circle-cont").remove();
    }
    /* Preloader ----------------------------------- Preloader */

    /* Hot keys ------------------------------------ Hot keys */
    
    var ctrldown = false,
        shiftdown = false;
    function down(e){
        // alert(e.keyCode);
        if( e.keyCode == 13 && ctrldown ){
            if( !$(".b-popup form").length ){
                $(".ajax-create").click();
            }else{
                $(".fancybox-wrap form").trigger("submit",false);
            }
        }
        if( e.keyCode == 16 ){
            shift = true;
        }
        if( e.keyCode == 13 ){
            enterVariantsHandler();
        }
        if( e.keyCode == 91 || e.keyCode == 17 ){
            ctrldown = true;
        }
        if( e.keyCode == 27 && $(".fancybox-wrap").length ) $.fancybox.close();
    }
    function up(e){
        if( e.keyCode == 91 || e.keyCode == 17 ){
            ctrldown = false;
        }
        if( e.keyCode == 16 ){
            shift = false;
        }
    }
    // if( $(".ajax-create").length ){
        $(document).keydown(down);
        $(document).keyup(up);
    // }
    /* Hot keys ------------------------------------ Hot keys */

    /* Редактирование таблиц ------------------------------------ Редактирование таблиц */
    var cmddown = false;
    function downTable(e,el){
        if( e.keyCode == 86 && ( cmddown || ctrldown ) && shiftdown ){
            el.val("");
            setTimeout(function(){ pasteHandler(el); },10);
        }
        if( e.keyCode == 91 ) cmddown = true;
        if( e.keyCode == 17 ) ctrldown = true;
        if( e.keyCode == 16 ) shiftdown = true;
    }
    function upTable(e){
        if( e.keyCode == 91 ) cmddown = false;
        if( e.keyCode == 17 ) ctrldown = false;
        if( e.keyCode == 16 ) shiftdown = false;
    }

    function pasteHandler(el){
        var splitted = el.val().split("\n");
        for( i in splitted ){
            splitted[i] = splitted[i].split("\t");
        }
        console.log(splitted);

        var table = el.parents("table"),
            colCount = el.parents("tr").find("td").length-1,
            rowCount = table.find("tr").length-1,
            colCur = el.parents("td").index()-1,
            rowCur = el.parents("tr").index(),
            rowTo = (splitted.length-1 <= rowCount-rowCur)?(splitted.length-1):(rowCount-rowCur),
            colTo = (splitted[0].length-1 <= colCount-colCur)?(splitted[0].length-1):(colCount-colCur);

        for (var i = 0; i <= rowTo ; i++) {
            var rowNum = rowCur + i;
            for (var j = 0; j <= colTo ; j++) {
                var colNum = colCur + j;
                table.find("tr").eq(rowNum).find("td").eq(colNum).find("textarea").val(splitted[i][j]);
            }
        }

        // alert([rowCur,colTo,rowNum,colNum]);
    }

    if( $(".b-table.b-data").length ){
        $(".b-table-td-editable textarea").keydown(function(e){
            downTable(e,$(this));
        });
        $(document).keyup(upTable);
    }

    $(".b-table-td-editable textarea").click(function(){
        if( cmddown || ctrldown ){
            var val = $(this).val(),
                found = val.match(/\[\+([\w]+)\=([\wА-я\_]+)\+\]/i);

            if( found != null && found.length == 3 ){
                switch (found[1]) {
                    case "VAR":
                        $("#b-update-button").attr("href",$("#b-update-button").attr("data-var")+found[2].trim()).trigger("click");
                        break
                    case "TABLE":
                        document.location.href = $("#b-update-button").attr("data-table")+found[2].trim();
                        break
                }
            }

            cmddown = ctrldown = false;
        }
    });
    $(".b-table-td-editable textarea").focus(function(){
        $(this).parents("tr").find("textarea").css("height", 400);
    }).blur(function(){
        $(this).parents("tr").find("textarea").css("height", "100%");
    });
    /* Редактирование таблиц ------------------------------------ Редактирование таблиц */

    /* Autocomplete -------------------------------- Autocomplete */
    function bindAutocomplete(){
        if( $(".autocomplete").length ){
            var i = 0;
            $(".autocomplete").each(function(){
                i++;
                $(this).wrap("<div class='autocomplete-cont'></div>");
                var $this = $(this),
                    data = JSON.parse($this.attr("data-values"));
                $this.removeAttr("data-values");

                var $cont = $this.parent("div"),
                    $clone = $this.clone(),
                    $label = $this.clone();

                $clone.removeAttr("required")
                      .attr("name","clone-"+i)
                      .attr("class","clone");
                $label.removeAttr("required")
                      .attr("name","label-"+i)
                      .attr("class","label")
                      .val($this.attr("data-label"))
                      .attr("readonly","readonly");
                $this.attr("type","hidden").removeClass("autocomplete");
                $cont.prepend($clone);
                $cont.prepend($label);

                if( $this.hasClass("categories") ){
                    $clone.catcomplete({
                        minLength: 0,
                        delay: 0,
                        source: data,
                        appendTo: $cont,
                        select: function( event, ui ) {
                            $clone.val(ui.item.label);
                            $label.show().val(ui.item.label);
                            $this.val(ui.item.val).trigger("change");
                            return false;
                        },
                        focus: function( event, ui ) {
                            // $(".ui-menu-item").each(function(){
                            //     alert($(this).attr("class"));
                            // });
                        }
                    });    
                }else{
                    $clone.autocomplete({
                        minLength: 0,
                        delay: 0,
                        source: data,
                        appendTo: $cont,
                        select: function( event, ui ) {
                            $clone.val(ui.item.label);
                            $label.show().val(ui.item.label);
                            $this.val(ui.item.val).trigger("change");
                            return false;
                        }
                    });
                }
                
                $clone.blur(function(){
                    $label.show();
                });

                $label.on("click focus",function(){
                    $label.hide();
                    $clone.val("").select();
                    if( $this.hasClass("categories") ){
                        $clone.catcomplete('search');
                    }else{
                        $clone.autocomplete('search');
                    }
                });
            });
        }
    }

    $.widget( "custom.catcomplete", $.ui.autocomplete, {
        _create: function() {
            this._super();
            this.widget().menu( "option", "items", "> :not(.ui-autocomplete-category)" );
        },
        _renderMenu: function( ul, items ) {
            var that = this,
                currentCategory = "";
            $.each( items, function( index, item ) {
                var li;
                if ( item.category != currentCategory ) {
                    ul.append( "<li class='ui-autocomplete-category'>" + item.category + "</li>" );
                    currentCategory = item.category;
                }
                li = that._renderItemData( ul, item );
                if ( item.category ) {
                    li.attr( "aria-label", item.category + " : " + item.label );
                }
            });
        }
    });
    /* Autocomplete -------------------------------- Autocomplete */

    /* Tooltip ------------------------------------- Tooltip */
    function bindTooltip(){
        bindTooltipSkin(".b-tooltip, .b-panel-icons-item a,.b-tool, .b-image-nav, .b-help, .b-title","qtip-light");
    }
    function bindTooltipSkin(selector,skin){
        $(selector).qtip('destroy', true);
        $(selector).qtip({
            position: {
                my: 'bottom center',
                at: 'top center'
            },
            style: {
                classes: skin+' qtip-shadow qtip-rounded'
            },
            show: {
                delay: 500
            }
        });
    }
    /* Tooltip ------------------------------------- Tooltip */

    /* Double-list --------------------------------- Double-list */
    function bindDoubleList(){
        if( $(".double-list").length ){
            $("#sortable1").sortable({
                connectWith: ".connectedSortable",
                update: function( event, ui ) {
                    customHandlers["sortList"]();
                }
            }).disableSelection();
            $("#sortable2").sortable({
                update: function( event, ui ) {
                    $("#sortable2 span").remove();
                    $("#sortable2 li").append("<span></span>");
                }
            }).disableSelection();
            customHandlers["sortList"]();
        }
    }
    $("body").on("click",".double-list li span",function(){
        $("#sortable1").prepend($(this).parents("li"));
        customHandlers["sortList"]();
    });

    customHandlers["attributesAjax"] = function($form){
        $("#sortable1 input").remove();
    }

    customHandlers["sortList"] = function(){
        var min = "&";
        $("#sortable1 li").each(function(){
            var max = "№";
            $("#sortable1 li").each(function(){
                var curId = $(this).attr("data-id");
                if(curId < max && curId > min){
                    max = curId;
                }
            });
            min = max;
            $("#sortable1").append($("#sortable1 li[data-id='"+min+"']"));
        });
    }
    /* Double-list --------------------------------- Double-list */

    /* Add-items ----------------------------------- Add-items */
    $("body").on("click","#add-inter-button",function(){
        if( $("#add-code").val() == "" ){
            $("#add-code").addClass("error").focus();
            return false;
        }
        var val = $("#add-code").val().trim();
        if( $(this).attr("data-case") ){
            if( $(this).attr("data-case") == "upper" )
                val = val.toUpperCase();
        }
        $("#add-code").removeClass("error");
        var li = $('<li><p><span></span><a href="#" class="b-add-remove">Удалить</a></p><input type="hidden" name="" value=""></li>')
        li.find("span").text(val+" ("+$("#add-inter").find("option:selected").text()+")");
        if( $("#b-add-table-desktop").length ){
            li.find("input").attr("name",$(this).attr("data-name")+"["+val+"]").val($("#add-inter").val().trim());
        }else{
            li.find("input").attr("name",$(this).attr("data-name")+"["+$("#add-inter").val().trim()+"]").val(val);
        }
        $(".b-add-items").append(li);
        $("#add-code").val("").focus();
    });
    $("body").on("click",".b-add-remove",function(){
        $(this).parents("li").remove();
    });
    /* Add-items ----------------------------------- Add-items */

    /* Variants ------------------------------------ Variants */
    $("body").on("click", ".b-sort-asc", function(){
        customHandlers["sortVariants"]();
        return false;
    });
    $("body").on("click", ".b-sort-desc", function(){
        customHandlers["sortVariants"](-1);
        return false;
    });

    customHandlers["sortVariants"] = function(side){
        var arr = [],
            isInt = $("#new-variant").attr("data-type") != "varchar",
            sort = 1,
            side = (side)?-1:1;

        $(".b-variants li").each(function(){
            arr.push($(this));
        });

        arr.sort(function (a,b){
            a = a.find("input").attr("data-name");
            b = b.find("input").attr("data-name");
            if( isInt ){
                a *= 1;
                b *= 1;
            }
            return (( a > b )?1:(( b == a )?0:-1))*side;
        });

        $(".b-variants").html();
        for(var i in arr){
            arr[i].find("input").val(sort);
            $(".b-variants").append(arr[i]);
            sort++;
        }
    }

    $("body").on("click","#add-variant",function(){
        $(".b-variant-cont .error").addClass("hidden");
        if( !$("#new-variant").hasClass("hidden") ){
            // Если вводили в инпут
            var val = $("#new-variant").val();
            if( !tryToAddVariant(val) ){
                $(".b-variant-cont .error-single").removeClass("hidden");
            }
        }else{
            // Если вводили в инпут textarea
            var val = $("#new-variant-list").val(),
                tmpArr = val.split("\n"),
                tmpError = new Array();
            for( var i in tmpArr ){
                if( !tryToAddVariant(tmpArr[i]) && tmpArr[i] != "" ){
                    tmpError.push(tmpArr[i]);
                }
            }
            if( tmpError.length ){
                $(".b-variant-cont .error-list").removeClass("hidden");
            }
            $("#new-variant-list").val(tmpError.join("\n"));
        }

        $((!$("#new-variant").hasClass("hidden"))?"#new-variant":"#new-variant-list").focus();
        updateVariantsSort();
        $.fancybox.update();
    });

    $("body").on("click","#b-variants li span",function(){
        if( confirm("Если удалить этот вариант, то во всех товарах, где был выбран именно этот вариант будет пустое значение атрибута. Подтвердить удаление?") ){
            $(this).parents("li").remove();
            updateVariantsSort();
            $.fancybox.update();
        }
    });

    $("body").on("click",".b-variant-cont .b-set-list",function(){
        $("#new-variant-list, .b-variant-cont .b-set-single").show();
        $("#new-variant, .b-variant-cont .b-set-list").hide().addClass("hidden");
        $("#new-variant-list").focus();
        $.fancybox.update();
    });

    $("body").on("click",".b-variant-cont .b-set-single",function(){
        $("#new-variant-list, .b-variant-cont .b-set-single").hide();
        $("#new-variant, .b-variant-cont .b-set-list").show().removeClass("hidden");
        $("#new-variant").focus();
        $.fancybox.update();
    });

    function tryToAddVariant(val){
        val = regexVariant(val);
        if( val != "" ){
            if( !$("input[data-name='"+val.toLowerCase()+"']").length ){
                $("#b-variants ul").append("<li><p>"+val+"</p><span></span><input data-name=\""+val.toLowerCase()+"\" type=\"hidden\" name=\"VariantsNew["+val+"]\" value=\"\"></li>");
                $("#new-variant").val("");
                return true;
            }
        }
        return false;
    }

    function regexVariant(val){
        var regArr;
        switch( $("#new-variant").attr("data-type") ) {
            case "float":
                regArr = /^[^\d-]*(-{0,1}\d*\.{0,1}\d+)[\D]*$/.exec(val);

                break;
            case "int":
                regArr = /^[^\d-]*(-{0,1}\d+)[\D]*$/.exec(val);

                break;
            default:
                regArr = ["",val];
                break;
        }
        return ( regArr != null )?regArr[1]:"";
    }

    function updateVariantsSort(){
        var i = 0;
        $("#b-variants ul li").each(function(){
            i+=1;
            $(this).find("input").val(i);
        });
    }
    function enterVariantsHandler(){
        if( !$(".b-variant-cont input[type='text']").hasClass("hidden") ){
            $("#add-variant").click();
        }
    }
    function bindVariants(){
        if( $("#b-variants").length ){
            $("#b-variants .sortable").sortable({
                update: function( event, ui ) {
                    updateVariantsSort();
                }
            }).disableSelection();

            switch( $("#new-variant").attr("data-type") ) {
                case "float":
                    $("#new-variant").numericInput({ allowFloat: true, allowNegative: true });

                    break;
                case "int":
                    $("#new-variant").numericInput({ allowFloat: false, allowNegative: true });

                    break;
            }
        }
    }
    /* Variants ------------------------------------ Variants */

    /* Dynamic ------------------------------------- Dynamic */
    function bindSelectDynamic(){
        if( $(".b-select-dynamic").length ){
            $(".b-select-dynamic").change(function(){
                var $form = $(this).parents("form");

                progress.setColor("#FFF");
                progress.start(3);

                $.ajax({
                    type: "POST",
                    url: $form.attr("action"),
                    data: $form.serialize(),
                    success: function(msg){
                        progress.end(function(){
                            $(".fancybox-inner").html(msg);
                            bindSelectDynamic();
                        });
                    }
                });
            });
        }
    }
    if( $(".b-dynamic select").length ){
        $(".b-dynamic .b-select-all").click(function(){
            $(this).parents(".b-dynamic").find("ul li").addClass("selected");
            return false;
        });

        $(".b-dynamic .b-select-none").click(function(){
            $(this).parents(".b-dynamic").find("ul li").removeClass("selected");
            return false;
        });
    }
    /* Dynamic ------------------------------------- Dynamic */

    /* Auction ------------------------------------- Auction */
    var refreshTimeout = 5000;
    if( $(".b-auction-table").length ){
        setTimeout(function run(){
            console.log(progress.isBlocked());
            if( !progress.isBlocked() ){
                $.ajax({
                    url: $(".b-auction-table").attr("data-url"),
                    success: function(msg){
                        setAuctionResults(msg);
                        console.log("refreshed");
                    }
                });
            }

            setTimeout(run,refreshTimeout);
        },refreshTimeout);

        $("body").mousemove(function(){
            if( $("td.b-refreshed").length )
                $("td.b-refreshed").removeClass("b-refreshed").addClass("b-refreshed-out");
        });
    }

    function setAuctionResults(msg){
        var json = JSON.parse(msg);

        for( var i in json ){
            var tr = $(".b-auction-table tr[data-id='"+json[i].id+"']");
            if( json[i].date != tr.find("td[data-field='date']").text() ) tr.find("td[data-field='date']").removeClass("b-refreshed-out").addClass("b-refreshed");
            tr.find("td[data-field='date']").html(json[i].date);
            if( json[i].current_price != tr.find("td[data-field='current_price']").text() ) tr.find("td[data-field='current_price']").removeClass("b-refreshed-out").addClass("b-refreshed");
            tr.find("td[data-field='current_price']").html(json[i].current_price);
            if( json[i].state != tr.find("td[data-field='state']").text() ) tr.find("td[data-field='state']").removeClass("b-refreshed-out").addClass("b-refreshed");
            tr.find("td[data-field='state']").html(json[i].state);
        }
    }
    /* Auction ------------------------------------- Auction */

    /* Yahoo ------------------------------------- Yahoo */
    var yahooTog = false,
        prevClick = null,
        yahooSelected = [];
    function checkDelete(){
        if( $(".yahoo-list li.selected").length ){
            $(".b-delete-selected").show();
        }else{
            $(".b-delete-selected").hide();
        }
        if( $(".yahoo-list li").length == $(".yahoo-list li.selected").length ){
            $(".b-select-all").text("Снять выделение");
        }else{
            $(".b-select-all").text("Выделить все");
        }
    }
    function bindYahoo(){
        if( $(".yahoo-list").length ){
            $(".b-delete-selected").hide();
            $(".b-select-all").click(function(){
                if( $(".yahoo-list li").length == $(".yahoo-list li.selected").length ){
                    $(".yahoo-list li").removeClass("selected");
                }else{
                    $(".yahoo-list li").addClass("selected");
                }
                checkDelete();
            });
            $(".yahoo-list li span, .yahoo-list li a").mousedown(function(){
                yahooTog = true;
            });
            $(".yahoo-list li").mouseup(function(){
                if( yahooTog ){
                    yahooTog = false;
                    return false;
                }
                yahooTog = false;
                if( shift && prevClick !== null ){
                    selectInterval(prevClick,$(this).index());
                }else{
                    $(this).toggleClass("selected");
                }
                prevClick = $(this).index();
                checkDelete();
            });
        }
    }

    function selectInterval(left, right){
        var from = (left < right)?left:right,
            to = (left > right)?left:right;

        for( var i = from; i <= to; i++ ){
            $(".yahoo-list li").eq(i).addClass("selected");
        }
    }

    function yahooBeforeAjax(){
        if( $(".yahoo-list").length ){
            $(".yahoo-list li.selected").each(function(){
                yahooSelected.push($(this).attr("data-id"));
                console.log(yahooSelected);
            });
        }
    }

    function yahooAfterAjax(){
        if( $(".yahoo-list").length ){
            for( var i = 0; i < yahooSelected.length; i++ ){
                $(".yahoo-list li[data-id='"+yahooSelected[i]+"']").addClass("selected");
            }
            yahooSelected = [];
        }
    }

    if( $(".yahoo-list").length ){
        bindYahoo();
    }
    /* Yahoo ------------------------------------- Yahoo */

    /* Filter Pagination ------------------------- Filter Pagination */
    if( $(".b-filter-pagination").length ){
        $("body").on("click",".b-filter-pagination .yiiPager a",function(){
            $("#b-filter-form").attr("action",$(this).attr("href")).submit();
            return false;
        });
        $("body").on("click",".b-clear-filter-form",function(){
            $("#b-filter-form input[class!='hidden'], #b-filter-form select[class!='hidden']").remove();
            $("#b-filter-form").submit();
            return false;
        });
        $("body").on("change","#b-sort-1",function(){
            $("#b-sort-2").val($(this).val());
            $("#b-filter-form").submit();
        });
        $("body").on("change","#b-order-1",function(){
            $("#b-order-2").val($(this).val());
            $("#b-filter-form").submit();
        });
    }
    /* Filter Pagination ------------------------- Filter Pagination */

    /* Live -------------------------------------- Live */
    var liveTimeOut;
    if( $("#filter-form").length ){
        var liveDelay = $("#filter-form").attr("data-delay")*1000;
        setTimeout(liveUpdate,liveDelay);
    }

    function liveUpdate(tog){
        clearTimeout(liveTimeOut);
        $.ajax({
            url: $("#filter-form").attr("action"),
            data: (tog)?$("#filter-form").serialize():null,
            method: $("#filter-form").attr("method"),
            success: function(msg){
                console.log("success");
                $(".ajax-content").html(msg);
            },
            error: function(){
                
            },
            complete: function(){
                liveTimeOut = setTimeout(liveUpdate,liveDelay);
            }
        });
    }

    $("#b-queue-filter").click(function(){
        liveUpdate(true);
        return false;
    });

    /* Live -------------------------------------- Live */

    /* Session Checkboxes ------------------------ Session Checkboxes */
    var prevCheck = {
        index : null,
        checked : null
    };

    if( $(".b-sess-checkbox").length ){
        var addUrl = $(".b-sess-checkbox-info").attr("data-add-url"),
            removeUrl = $(".b-sess-checkbox-info").attr("data-remove-url"),
            addManyUrl = $(".b-sess-checkbox-info").attr("data-add-many-url"),
            removeManyUrl = $(".b-sess-checkbox-info").attr("data-remove-many-url");

        $("body").on("click",".b-sess-allcheckbox",function(){
            var $this = $(this);
            progress.setColor("#D26A44");
            progress.start(1);

            $.ajax({
            url: $this.attr("href"),
                success: function(msg){
                    var json = JSON.parse(msg);
                    progress.end();
                    if( json.result == "success" ){
                        if(json.codes) $(".b-sess-checkbox").prop("checked",true); else $(".b-sess-checkbox").prop("checked",false);
                        $("#b-sess-checkbox-list").text(json.codes);
                    }else{
                        alert("Какая-то ошибка. Не удалось выяснить причину. Попробуй еще пару раз и звони Мише.");
                    }
                },
                error: function(){
                    progress.end();
                    alert("Какая-то ошибка. Не удалось выяснить причину. Попробуй еще пару раз и звони Мише.");
                }
            });
            return false;
        });

        $("body").on("change",".b-sess-checkbox", function(){
            progress.setColor("#D26A44");
            progress.start(1);

            if( shift && prevCheck.index !== null && $(this).parents("tr").index() !== prevCheck.index){
                manyCheckboxes($(this));
            }else{
                oneCheckbox($(this));
            }
        });
    }

    function manyCheckboxes($this){
        var ids = [],
            from = Math.min(prevCheck.index, $this.parents("tr").index()),
            to = Math.max(prevCheck.index, $this.parents("tr").index()),
            $table = $this.parents("table"),
            action = prevCheck.checked;

        console.log([from,to]);
        for (var i = from; i <= to; i++){
            var input = $table.find("tr").eq(i).find("input[type='checkbox']");
            ids.push(input.val());
            input.prop("checked", prevCheck.checked);
        }

        $.ajax({
            url: ( prevCheck.checked )?addManyUrl:removeManyUrl,
            data: "ids="+ids.join(","),
            success: function(msg){
                var json = JSON.parse(msg);
                progress.end();

                $($this.attr("data-block")).text(json.codes);
                if( json.ids ){
                    var items = json.ids.split(",");
                    for( var i in items )
                        $("#id-"+items[i]).find("input[type='checkbox']").prop("checked", action);
                }
            },
            error: function(){
                progress.end();
                alert("Какая-то ошибка. Не удалось выяснить причину. Попробуй еще пару раз и звони Мише.");
            }
        });
    }

    function oneCheckbox($this){
        prevCheck.index = $this.parents("tr").index();
        prevCheck.checked = $this.prop("checked");

        $.ajax({
            url: ( $this.prop("checked") )?addUrl:removeUrl,
            data: "id="+$this.val(),
            success: function(msg){
                var json = JSON.parse(msg);
                progress.end();
                if( json.result == "success" ){
                    if($this.hasClass("check-page")) {
                        if($this.prop("checked")) {
                            $(".b-sess-checkbox").prop("checked",true);
                        } else $(".b-sess-checkbox").prop("checked",false);
                    }
                    $($this.attr("data-block")).text(json.codes);
                }else{
                    alert("Какая-то ошибка. Не удалось выяснить причину. Попробуй еще пару раз и звони Мише.");
                }
            },
            error: function(){
                progress.end();
                alert("Какая-то ошибка. Не удалось выяснить причину. Попробуй еще пару раз и звони Мише.");
            }
        });
    }
    /* Session Checkboxes ------------------------ Session Checkboxes */

    /* Visual Interpreter ------------------------ Visual Interpreter */
    function bindVisual(){
        if( $(".visual-inter").length ){
            var vis_int;
            $(".visual-inter").focus(function(){
                var $this = $(this);
                vis_int = setInterval(function(){
                    updateVisual($this);
                },1000);
            }).blur(function(){
                clearInterval(vis_int);
            });
        }
    }

    function updateVisual($el){
        $.ajax({
            url: $el.attr("data-href"),
            data: $("<input type='text' name='inter_value'/>").val($el.val()).serialize(),
            success: function(msg){
                $($el.attr("data-block")).html(msg);
            },
            error: function(){
                
            }
        });
    }
    bindVisual();
    /* Visual Interpreter ------------------------ Visual Interpreter */

    /* Photo sortable ---------------------------- Photo sortable */
    function photo_init() {
        if( $("#photo-sortable").length ) {
            var el = document.getElementById('photo-sortable');
            var sortable = Sortable.create(el);
            var el2 = document.getElementById('photo-sortable-2');
            var sortable2 = Sortable.create(el2);
        }
    }

    $("body").on("click",".b-photo-delete",function(){
        var $li = $(this).parents("li");
        if( !$li.hasClass("deleted") ){
            $li.addClass("deleted");
            $li.find("input").attr("name", $li.find("input").attr("data-delete"));
            $li.find(".ion-close").removeClass("ion-close").addClass("ion-android-refresh");
        }else{
            $li.removeClass("deleted");
            $li.find("input").attr("name", $li.find("input").attr("data-name"));
            $li.find(".ion-android-refresh").removeClass("ion-android-refresh").addClass("ion-close");
        }
        return false;
    });

    customHandlers["add-to-photo-sortable"] = function(links){
        for( var i in links )
            $("#photo-sortable").append('<li style="background-image: url(\'/'+links[i]+'\');" data-small="/'+links[i]+'" data-src="/'+links[i]+'"><a href="#" class="b-photo-delete ion-icon ion-close"></a><input type="hidden" name="Images[]" data-name="Images[]" data-delete="Delete[]" value="/'+links[i]+'"></li>');
    }
    customHandlers["add-to-photo-sortable-2"] = function(links){
        for( var i in links )
            $("#photo-sortable-2").append('<li style="background-image: url(\'/'+links[i]+'\');" data-small="/'+links[i]+'" data-src="/'+links[i]+'"><a href="#" class="b-photo-delete ion-icon ion-close"></a><input type="hidden" name="Extra[]" data-name="Extra[]" data-delete="Delete[]" value="/'+links[i]+'"></li>');
    }

    var order1 = [],order2 = [],
        issetdeleted1 = false,issetdeleted2 = false;
    $("body").on("click","#b-update-photo",function(){
        $(".photo-sortable").addClass("disabled");
        progress.setColor("#D26A44");
        progress.start(1);
        order1 = backupImages($(".photo-sortable:eq(0) li"));
        order2 = backupImages($(".photo-sortable:eq(1) li"));
        issetdeleted1 = $(".photo-sortable:eq(0) li.deleted").length;
        issetdeleted2 = $(".photo-sortable:eq(1) li.deleted").length;
        $.ajax({
            url: $( "#photo-sortable" ).attr("data-href"),
            data: $( ".photo-sortable input" ).serialize(),
            method: "POST",
            success: function(msg){
                progress.end(function(){
                    $(".photo-sortable").removeClass("disabled");
                    $("#photo-cont").html(msg);

                    if( issetdeleted1 ){
                        reloadImages($(".photo-sortable:eq(0) li"));
                    }else{
                        restoreImages($(".photo-sortable:eq(0) li"),order1);
                    }
                    if( issetdeleted2 ){
                        reloadImages($(".photo-sortable:eq(1) li"));
                    }else{
                        restoreImages($(".photo-sortable:eq(1) li"),order2);
                    }
                    photo_init();
                });
            },
            error: function(){
                alert("Ошибка сохранения");
            }
        });
       
        return false;
    });

    function reloadImages($images){
        $images.each(function(){
            var $this = $(this);
            $this.css({
                "background-image" : "url('"+$this.attr('data-small')+'?'+Math.random()+"')",
                "opacity" : 0
            });
            var img = new Image();
            img.src = $(this).attr("data-small");
            img.onload = function(){
                $this.fadeTo(300,1);
            }
        });
    }

    function backupImages($images){
        var order = [];
        $images.each(function(){
            order.push( $(this).css("background-image") );
        });
        return order;
    }

    function restoreImages($images,arr){
        $images.each(function(){
            $(this).css("background-image", arr[$(this).index()]);
        });
    }
    /* Photo sortable ---------------------------- Photo sortable */

    /* Filter clear buttons ---------------------- Filter clear buttons */
    $("body").on("click",".b-filter-check-section",function(){
        $(this).parents(".b-filter-block").find("input[type='checkbox']").prop("checked", true);
        var selected = [];
        $(this).parents(".b-filter-block").find("select.select2-filter option").each(function(i,e){
            selected[selected.length]=$(e).attr("value");
        });
        $(this).parents(".b-filter-block").find("select.select2-filter").select2("val", selected);
           
        return false;
    });

    $("body").on("click",".b-filter-uncheck-section",function(){
        $(this).parents(".b-filter-block").find("input[type='checkbox']").prop("checked", false);
        $(this).parents(".b-filter-block").find("select.select2-filter").select2("val", "");
        return false;
    });

    $("body").on("click",".b-filter-clear-inputs",function(){
        $(this).parents(".b-filter-block").find("input[type='text'],input[type='number']").val("");
        return false;
    });

    $("body").on("click",".b-filter-clear-all",function(){
        $(".b-popup-filter input[type='checkbox']").prop("checked", false);
        $(".b-popup-filter select.select2-filter").select2("val", "");
        $(".b-popup-filter input[type='text'],.b-popup-filter input[type='number']").val("");
        return false;
    });
    /* Filter clear buttons ---------------------- Filter clear buttons */

    $("body").on("change","#good-edit-form input[name='Good_attr[3]']",function(){
        progress.setColor("#D26A44");
        progress.start(1);
        $.ajax({
            url: $( ".b-good-form" ).attr("data-href"),
            data: $( "input[name='Good_attr[3]']" ).serialize(),
            method: "GET",
            success: function(msg){
                var json = JSON.parse(msg);
                if( json.result == "success" ){
                    $( "input[name='Good_attr[3]']" ).parents(".row").find(".error").remove();
                    $( "input[name='Good_attr[3]']" ).parents(".row").append('<label class="error" style="color: #5FC147;">Код свободен</label>');
                }else{
                    alert(json.message);
                    $( "input[name='Good_attr[3]']" ).val("");
                }
                progress.end();
            },
            error: function(){
                alert("Ошибка проверки");
            }
        });
    });

    function clickHash(){
        var hash = window.location.hash.split("|");
        if( hash[0] == "#click" ){
            $("a[href='"+hash[1]+"']").trigger("click");
        }
    }
    clickHash();
    window.onhashchange = clickHash;

    if( $(".b-section-nav").length ){
        $(".main").scroll(function(){
            sectionNavResize();            
        });
    }

    function sectionNavResize(){
        if( $(".b-section-nav").length ){
            // $(".b-section-nav").css({
            //     "left" : $(".b-table").eq(0).offset().left,
            //     "width" : $(".b-table").width()
            // });
        }
    }

    $(".ajax-update-prices").click(function(){
        progress.setColor("#D26A44");
        progress.start(10);

        $.ajax({
            url: $(this).attr("href"),
            success: function(msg){
                progress.end(function(){
                    alert(msg);
                });
            },
            error: function(){
                alert("Ошибка");
            }
        });
        return false;
    });

    $("body").on("click",".ajax-photodoska,.ajax-request",function(){
        progress.setColor("#D26A44");
        progress.start(10);

        $.ajax({
            url: $(this).attr("href"),
            complete: function(){
                progress.end();
            },
            success: function(msg){
                if( msg.trim() != "" && isValidJSON(msg) ){
                    jsonHandler(msg);
                }
            },
            error: function(){
                alert("Ошибка");
            }
        });
        return false;
    });

    function transition(el,dur){
        el.css({
            "-webkit-transition":  "all "+dur+"s ease-in-out", "-moz-transition":  "all "+dur+"s ease-in-out", "-o-transition":  "all "+dur+"s ease-in-out", "transition":  "all "+dur+"s ease-in-out"
        });
    }

    bindFancy();
    bindFilter();
    bindAutocomplete();
    bindTooltip();
    bindImageUploader();

    $(".b-compare button").click(function(el,attr){
        $(".compare1,.compare2,.same").html("");
        var compare1 = $("#compare1").val().split('\n'),
            compare2 = $("#compare2").val().split('\n');
        var same = new Array(),diff1 = new Array(),diff2 = new Array(),contain;

        $(compare1).each(function(i,bg) {
            contain = false;
            $(compare2).each(function(j,sm) {
                if( bg == sm ) {
                    contain = true;
                }
            });
            if(!contain) {$(".compare1").append("<p>"+bg+"</p>"); $(".same").append("<p>"+bg+"</p>"); }
        });
        $(compare2).each(function(i,bg) {
            contain = false;
            $(compare1).each(function(j,sm) {
                if( bg == sm ) {
                    contain = true;
                }
            });
            if(!contain) { $(".compare2").append("<p>"+bg+"</p>"); $(".same").append("<p>"+bg+"</p>"); }
        });
        $(".compare-cont").show();

        

        if( attr != true ){
            progress.setColor("#D26A44");
            progress.start(2);
            $.ajax({
                url: $(".b-compare").attr("data-url"),
                data: $(".b-main-column").serialize(),
                success: function(msg){
                    progress.end();
                }
            });
        }
    });

    if( $(".b-payadvert").length ){
        $(".b-payadvert").click(function(){
            var offset = $(".offset").val(),
                interval = $(".interval").val();

            $("#adverts-form-hidden").html($(".b-upadvert-input-cont").html());
            $(".offset").val(offset);
            $(".interval").val(interval);            
            
            $("#adverts-form").attr("action",$(this).attr("href")).submit();
            return false;
        });
    }

    if( $(".b-compare button").length ) $(".b-compare button").trigger("click",true);

    if( $(".b-kit-switcher").length ){
        $("body").on("click",".b-kit-switcher",function(){
            toggleMode(!$(this).hasClass("checked"),$(this));
            return false;
        });
        function toggleMode(tog,el){
            if( tog ){
                $(".b-kit-switcher").addClass("checked");
                $(".b-kit-off").removeClass("b-kit-off").addClass("b-kit-on");
                if( $(".b-kit-switcher").attr("data-on") ) customHandlers[$(".b-kit-switcher").attr("data-on")](el);
            }else{
                $(".b-kit-switcher").removeClass("checked");
                $(".b-kit-on").removeClass("b-kit-on").addClass("b-kit-off");
                if( $(".b-kit-switcher").attr("data-off") ) customHandlers[$(".b-kit-switcher").attr("data-off")](el);
            }
        }
        customHandlers["setEditable"] = function(){
            $(".b-desktop").addClass("b-editable");
        }
        customHandlers["unsetEditable"] = function(){
            $(".b-desktop").removeClass("b-editable");
        }

        customHandlers["goTo"] = function($this){
            progress.setColor("#D26A44");
            progress.start(3);
            var href = $this.attr("data-"+(($this.hasClass("checked"))?"on":"off")+"-href");
            $.ajax({
                url: href,
                success: function(msg){
                    progress.end(function(){
                        setResult(msg);
                    });
                }
            });
        }

        customHandlers["updateQueue"] = function($this){
            progress.setColor("#D26A44");
            progress.start(3);
            var href = $this.attr("data-"+(($this.hasClass("checked"))?"on":"off")+"-href");
            $.ajax({
                url: href,
                success: function(msg){
                    progress.end(function(){
                        $(".b-place-state span[data-id='"+$this.attr("data-id")+"']").removeClass((($this.hasClass("checked"))?"b-red":"b-green")).addClass((($this.hasClass("checked"))?"b-green":"b-red")); 
                    });
                }
            });
        }
    }

    $("body").on("click",".b-group-popup .select-all",function(){
        $(".b-group-popup").find("input[type='checkbox']").prop("checked","checked");
    });

    $("body").on("click",".b-group-popup .select-none",function(){
        $(".b-group-popup").find("input[type='checkbox']").prop("checked",false);
    });

    $("body").on("click",".b-start-queue",function(){
        $(".b-place-state span[data-id='"+$(this).attr("data-id")+"']").removeClass("b-red").addClass("b-green");
    });

    $("body").on("click",".b-stop-queue",function(){
        $(".b-place-state span[data-id='"+$(this).attr("data-id")+"']").removeClass("b-green").addClass("b-red");
    });

    $("body").on("click",".b-good-clear-filter",function(){
        $(this).parents("form").append("<input type='hidden' name='clear' value='1'>");
        $(this).parents("form").submit();
        return false;
    });

    $("body").on("click",".b-filter-sort",function(){
        $("input[name='sort_type']").val($(this).attr("data-type"));
        $("input[name='sort_field']").val($(this).attr("data-field"));
        $("input[name='sort_type']").trigger("change");
        return false;
    });

    function bindTableForm(){
        if( $("#table-form").length ){
            $("#table-form").submit(function(){
                var tog = false;
                $(this).find("textarea,input[type='text'],input[type='number'],input[type='date'],select").each(function(){
                    if( $(this).val() != "" && $(this).val() != "0" ) tog = true;
                });
                if(!tog){
                    $(this).addClass("blocked");
                    return false;
                }else{
                    $(this).removeClass("blocked");
                }
            });
        }
    }

    $("#b-find-advert-button").click(function(){
        if( $(".b-find-advert").hasClass("opened") ){
            $(".b-find-advert").removeClass("opened");
        }else{
            $(".b-find-advert").addClass("opened");
            $("#b-find-advert").focus();
        }
    });

});