var customHandlers = [];
$(document).ready(function(){  

    var myWidth,
        myHeight,
        title = window.location.href,
        titleVar = ( title.split("localhost").length > 1 )?4:3,
        progress = new KitProgress("#FFF",3);

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
        padding: 0,
        margin: 30,
        beforeShow: function(){
            var $form = $(".fancybox-inner form");
            bindForm($form);
            bindImageUploader();
            bindTinymce();
            bindSelectDynamic();
            bindAutocomplete();
            bindTooltip();
            bindDoubleList();
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
        var warning = ($(this).attr("data-warning"))?$(this).attr("data-warning"):"Вы действительно хотите удалить</br>запись?";
        warning += ( $(this).parents(".b-table").attr("data-warning") )?"<br><b>"+$(this).parents(".b-table").attr("data-warning")+"</b>":"";
        $.fancybox.open({
            padding: 0,
            content: '<div class="b-popup b-popup-delete"><h1>'+warning+'</h1><div class="row buttons"><input type="button" class="b-delete-yes" value="Да"><input type="button" onclick="$.fancybox.close();" value="Нет"></div></div>'
        });
        bindDelete($(this).attr("href"),$(this).hasClass("not-ajax-delete"));
        return false;
    });

    function setResult(html){
        $(".b-main-center").html(html);

        setTimeout(function(){
            bindFilter();
            bindTooltip();
            bindAutocomplete();
            bindYahoo();
            bindFancy();

            $(".b-refresh").removeClass("b-refresh").addClass("b-refresh-out");
        },100);
    }

    function bindDelete(url,filter){
        $(document).unbind("keypress");
        $(document).bind("keypress",function( event ) {
            if ( event.which == 13 ) {
                $(".fancybox-inner .b-delete-yes").click();
            }
        });

        $(".fancybox-inner .b-delete-yes").click(function(){
            progress.setColor("#FFF");
            progress.start(3);

            url = ( $(".main form").length && !filter ) ? (url+"&"+$(".main form").serialize()) : url;

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
                        setResult(msg);
                    });
                    $.fancybox.close();
                }
            });    
        });
    }

    function bindFilter(){
        if( $(".main .b-filter").length ){
            $(".main form select, .main form input").bind("change",function(){
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
            $(".main form").submit(function(){
                return false;
            });
            $(".b-clear-filter").click(function(){
                $(".main form select,.main form input").val("");
                $(".main form select,.main form input").eq(0).trigger("change");
                return false;
            });
        }
    }

    function bindForm($form){

        $(".select2").select2({
          placeholder: "",
          allowClear: true
        });
        $form.validate({
            ignore: ""
        });

        $(".numeric").numericInput({ allowFloat: true, allowNegative: true });

        $form.submit(function(e,a){
            if( $(this).attr("id") == "good-edit-form" ){
                $("#good-edit-form").find("input[type='number']").each(function(){
                    if( $(this).val() == "" ) $(this).val(0);
                });
            }
            tinymce.triggerSave();
            if( $(this).valid() && !$(this).find("input[type=submit]").hasClass("blocked") ){
                var $form = $(this),
                    url = $form.attr("action");

                $(this).find("input[type=submit]").addClass("blocked");

                if( a == false ){
                    $form.find("input[type='text'],input[type='number'],textarea").val("");
                    $form.find("input").eq(0).focus();
                }

                progress.setColor("#FFF");
                progress.start(3);


                if( $(".main form").length ){
                    if( $(".main form").attr("id") != "b-matrix-form" ){
                        url = url+( (url.split("?").length>1)?"&":"?" )+$(".main form").serialize();
                    }
                }

                if( $form.attr("data-beforeAjax") && customHandlers[$form.attr("data-beforeAjax")] ){
                    customHandlers[$form.attr("data-beforeAjax")]($form);
                }

                $.ajax({
                    type: $form.attr("method"),
                    url: url,
                    data: $form.serialize(),
                    success: function(msg){
                        progress.end(function(){
                            $form.find("input[type=submit]").removeClass("blocked");
                            if( msg != "none" ) setResult(msg);
                        });
                        if( a != false ){
                            $.fancybox.close();
                        }
                    }
                });
            }else{
                $(".fancybox-overlay").animate({
                    scrollTop : 0
                },200);
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

    function bindImageUploader(){
        $(".b-get-image").click(function(){
            $(".b-for-image-form").load($(".b-get-image").attr("data-path"), {}, function(){
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
    
    var cmddown = false,
        ctrldown = false,
        shiftdown = false;
    function down(e){
        // alert(e.keyCode);
        if( e.keyCode == 13 && ( cmddown || ctrldown ) ){
            if( !$(".b-popup form").length ){
                $(".ajax-create").click();
            }else{
                $(".fancybox-wrap form").trigger("submit",[false]);
            }
        }
        if( e.keyCode == 13 ){
            enterVariantsHandler();
        }
        if( e.keyCode == 91 ) cmddown = true;
        if( e.keyCode == 17 ) ctrldown = true;
        if( e.keyCode == 27 && $(".fancybox-wrap").length ) $.fancybox.close();
    }
    function up(e){
        if( e.keyCode == 91 ) cmddown = false;
        if( e.keyCode == 17 ) ctrldown = false;
    }
    // if( $(".ajax-create").length ){
        $(document).keydown(down);
        $(document).keyup(up);
    // }
    /* Hot keys ------------------------------------ Hot keys */

    /* Редактирование таблиц ------------------------------------ Редактирование таблиц */
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

    /* Variants ------------------------------------ Variants */
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
    var yahooTog = false;
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
                $(this).toggleClass("selected");
                checkDelete();
            });
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

    $(".ajax-update-prices").click(function(){
        progress.setColor("#D26A44");
        progress.start(10);

        $.ajax({
            url: $(this).attr("href"),
            success: function(msg){
                progress.end(function(){
                    alert("Цены успешно обновлены");
                });
            },
            error: function(){
                alert("Ошибка обновления цен");
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


    $(".b-compare button").click(function(){
        $(".compare1,.compare2,.same").html("");
        var compare1 = $("textarea[name=compare1]").val().split('\n'),
        compare2 = $("textarea[name=compare2]").val().split('\n');
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
    });





});