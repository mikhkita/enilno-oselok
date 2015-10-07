var baseUrl = 'http://www.sfoauto.ru';
//var baseUrl = 'http://localhost/sfo';


var allowCVP = true;
//var allowPrmMove = true;
function post(el, data, confirmText, returnUrl)
{
    if (confirmText)
    {
        if (!confirm(confirmText))
        {
                return false;
        }
    }

    if (!returnUrl)
    {
        returnUrl = window.location.href;
    }

    var form =$('<form method="post" action="adminAction"></form>');
    $('body').append(form);

    form.append('<input type="hidden" name="return" value="' + returnUrl + '" />');

    if (data)
    {
        $.each(data,
            function (key, val)
            {
                form.append('<input type="hidden" name="' + key + '" value="' + val + '" />');
            }
        )
    }
    form.submit();
    return false;
}

function a_post(el)
{
    if (el.rel == 'del')
    {
        if (!confirm('Удалить?'))
        {
            return false;
        }
    }

    var href = el.href.split('?');
    var data = href[1].split('&');
    href = href[0];
    var form =$('<form method="post" action="' + href + '"></form>');
    returnUrl = window.location.href;

    $('body').append(form);

    form.append('<input type="hidden" name="return" value="' + returnUrl + '" />');

    $.each(data,
        function (num, elem)
        {
            elem = elem.split('=');
            form.append('<input type="hidden" name="' + elem[0] + '" value="' + elem[1] + '" />');
        }
    );

    form.submit();
    return false;
}
function getModel(mark_id,model_id)
{
    $.get(
        baseUrl + '/adv/getModel',
        {'mark_id':mark_id},
        function (data){
            loadModelList(data,model_id);
        },
        'json'
    );
}

function getModelAll(mark_id,model_id)
{
    $.get(
        baseUrl + '/adv/getModelAll',
        {'mark_id':mark_id},
        function (data){
            loadModelList(data,model_id);
        },
        'json'
    );
}
function loadModelList(data,model_id)
{
    if ($('#model').val()) getBody(model_id);
    $('#model option.unlock').remove();
    $('#model').append('<option class="unlock" value=""></option>');
    var topModels = true;
    $.each(data, function(i,el){
    	body = '';
    	if (el.body_name == 'sep') topModels = false;
        if ((i >= 1 && el.model_name == data[i-1].model_name) || (i <= data.length - 2 && el.model_name == data[i+1].model_name) || topModels)
        {
            body = ' (' + el.body_name + ')';
        }
        $('#model').append(
            $('<option class="unlock"></option>').val(el.model_id).html(el.model_name + body)
        );
    });
    html = $('#model option:first').html();
    $('#model').next().find('.at-i-o-data').html(html);
    if (model_id)
    {
        $('#model option[value='+ model_id +']').attr('selected','selected');
        html = $('#model').find('option[value="'+ model_id +'"]').html();
        $('#model').next().find('.at-i-o-data').html(html);
        $('#model').parent().css({'height': $('#model').next().outerHeight()});
    }
}
function getBody(model_id)
{
    if (model_id)
    {
        $.get(
            baseUrl + '/adv/getBody',
            {'model_id':model_id},
            function (data){
                $('select.adv_body').val(data);
                $('.adv_body').next().find('td.at-i-o-data').html($('select.adv_body').find('option[value="'+ data +'"]').html());
                $('select.adv_body').attr('disabled', 'disabled');
                $('.adv_body').next().find('td.at-i-o-data, td.at-i-o-img').css('background-color', '#EEEEEE');
                $('.adv_body').parent().css({'height': $('.adv_body').next().outerHeight()});
                setBodyIcon();
            },
            'html'
        );
    }
    else
    {
        $('select.adv_body').val('');
        $('.adv_body').next().find('td.at-i-o-data').html('');
        $('select.adv_body').removeAttr('disabled');
        $('.adv_body').next().find('td.at-i-o-data, td.at-i-o-img').css('background-color', '#FFFFFF');
        setBodyIcon();
    }
}
function setBodyIcon()
{
    id = $('select.adv_body').val();
    if (id) {
        $('#body-icon').attr('src', baseUrl + '/images/ico/body-icons/body' + id + '.png').css({'display':'block'});
    }
    else {
        $('#body-icon').css({'display':'none'});
    }
}
function getBrowserInfo()
{
    var t,v = undefined;
    if (window.opera) t = 'Opera';
    else if (document.all) {
     t = 'IE';
     var nv = navigator.appVersion;
     var s = nv.indexOf('MSIE')+5;
     v = nv.substring(s,s+1);
    }
    else if (navigator.appName) t = 'Netscape';
    return {type:t,version:v};
}

function bookmark(a){
    var url = window.document.location;
    var title = window.document.title;
    var b = getBrowserInfo();
    if (b.type == 'IE' && 7 > b.version && b.version >= 4) window.external.AddFavorite(url,title);
    else if (b.type == 'Opera') {
     a.href = url;
     a.rel = "sidebar";
     a.title = url+','+title;
     return true;
    }
    else if (b.type == "Netscape") window.sidebar.addPanel(title,url,"");
    else alert("Нажмите CTRL-D, чтобы добавить страницу в закладки.");
    return false;
}

function tp_create(url)
{
    $.get(
        baseUrl + '/site/getPopup',
        {},
        function(data)
        {
            $('body').append(data);
            $.post(
                url,
                {},
                function(data){
                    tp_fill(data);
                    tp_form_submit(url);
                },
                'html'
            );
        },
        'html'
    );
}
function tp_close()
{
    if ($('#tp_container').is('div')) {
        $('#tp_bg').remove();
        $('.tp_close, .tp_close_hover').remove();
        $('#tp_container').remove();
        $('#layout_parent_container').css({'height': 'auto', 'overflow': 'auto', 'margin-top': 0});
        $('#layout_top_line').css('opacity', 1);
        $(window).scrollTop(scroll_top);
        scroll_top = 0;
    }
}


function advSubmit(url, cat_id) {
    $('form').submit(function(event) {
        event.preventDefault();
        $('form').ajaxSubmit({
            url: url + '?id=' + cat_id,
            success: function(data) {
                if (data[0] != 'r') {
                    $('.adv_attributes').html(data);
                    advSubmit(url, cat_id);
                    target_top= $('.adv_attributes').offset().top - 100;
                    $('html, body').animate({scrollTop:target_top}, 'slow');
                }
                else {
                    adv_id = data.split('r')[1];
                    //alert(baseUrl + '/obyavlenie/' + adv_id);
                    location.href = baseUrl + '/obyavlenie/' + adv_id;
                }
            }

	});
    });
}

function chooseCategory(id) {
    html = '<div class="loading"><img src="../images/theme/loader.gif"/> Загрузка...</div>';
    $('.adv_attributes').html(html);
    $.get(
        baseUrl + '/adv/getAdvAttributes',
        {'id' : id},
        function(data) {
            $('.adv_category').css('font-weight', 'normal');
            $('#adv_category_' + id).css('font-weight', 'bold');
            $('.adv_attributes').html(data);
            advSubmit(baseUrl + '/adv/getAdvAttributes', id)
        },
        'html'
    );
}

function chooseRegion(id)
{
    $.cookie('gl_regid', id, {path: '/'});
    $.cookie('loc_regid', id, {path: '/'});
    location.reload();
    //$('form').submit();
}

function chooseCityLink(id,city)
{
    $.cookie('gl_regid', id, {path: '/'});
    $.cookie('loc_regid', id, {path: '/'});
    $.cookie('loc_city', city, {path: '/'});
    location.reload();
    //$('form').submit();
}

function chooseCity(city_id, reg_id, id)
{
    $.cookie('loc_regid', reg_id, {path: '/'});
    $.cookie('loc_city', city_id, {path: '/'});
    $.cookie('regall', null, {path: '/'});
    $('.loc').val(city_id + '_' + reg_id + '_' + 0);
    if (id && id == 'site') location.href = baseUrl + '/adv/index#block_premium';
    else $('form').submit();
}
function chooseRegall()
{
    $.cookie('regall', 'all', {path: '/'});
    $.cookie('loc_regid', '0', {path: '/'});
    $.cookie('loc_city', '0', {path: '/'});
    $('.loc').val('0_0_1');
    //location.reload();
    $('form').submit();
}

function lightCategory(obj)
{
    $('.adv_category').css('font-weight', 'normal');
    $(obj).css('font-weight', 'bold');
}

function overSelectIni()
{
    $.each($('select'), function(i,el){
       html = $(el).find('option[value="'+ $(el).val() +'"]').html();
       $(el).prev().find('.os_input').html(html);
    });
    $('select').change(function(){
        html = $(this).find('option[value="'+ $(this).val() +'"]').html();
        $(this).prev().find('.os_input').html(html);
    });
}
function vote(id, mark)
{
    $.post(
        baseUrl + '/review/vote',
        {'id' : id, 'mark' : mark},
        function (data){
            if (data.error == 1) alert(data.comment);
            else {
                $('#rev_vote_container_min').removeClass('hidden');
                $('#rev_vote_container').addClass('hidden');
                val = data.value;
                $('.mark_avg').html(val.toFixed(1));
            }
        },
        'json'
    );
}

function revSwitch(obj, value)
{
    $('.rev_switch_data').each(function(i, el){
        if ($(el).css('display') != 'none')
        {
            $(el).animate(
            {'opacity':'0'},
            300,
            function(){
                $(el).css('display','none');
                switch (value) {
                    case 'general':
                        $(obj).next().next().attr('class','rev_switch_item');
                        $(obj).next().attr('class','rev_switch_item');
                        $(obj).attr('class','rev_switch_item_selected');
                        height = $('.rev_data_general').height();
                        $('#rev_switch_content').animate(
                            {'height':height},
                            300,
                            function(){
                                $('.rev_data_general').css({'opacity':'1', 'display':'block'});
                            }
                        );
                        break;
                    case 'set':
                        $(obj).prev().attr('class','rev_switch_item');
                        $(obj).next().attr('class','rev_switch_item');
                        $(obj).attr('class','rev_switch_item_selected');
                        height = $('.rev_data_set').height();
                        $('#rev_switch_content').animate(
                            {'height':height},
                            300,
                            function(){
                                $('.rev_data_set').css({'opacity':'1', 'display':'block'});
                            }
                        );
                        break;
                    case 'mark':
                        $(obj).prev().attr('class','rev_switch_item');
                        $(obj).prev().prev().attr('class','rev_switch_item');
                        $(obj).attr('class','rev_switch_item_selected');
                        height = $('.rev_data_mark').height();
                        $('#rev_switch_content').animate(
                            {'height':height},
                            300,
                            function(){
                                $('.rev_data_mark').css({'opacity':'1', 'display':'block'});
                            }
                        );
                        break;
                    default:
                        break;
                }
            });
        }
    });
}
function scrollUp()
{
    $('#g-up').css('height', $(window).height() - 270);
    op_p = 117
    op_shift = 400;
    sc_top = $(window).scrollTop();
    if (sc_top >= (op_shift + op_p))
    {
        sc_op = 0.5;
        $('#g-up').css('display', 'block');
    }
    else
    {
        if (sc_top <= op_p)
        {
            sc_op = 0;
            $('#g-up').css('display', 'none');
        }
    }
    if (sc_top <= (op_shift + op_p) && sc_top >= op_p)
    {
        $('#g-up').css('display', 'block');
        sc_op = (sc_top - op_p)/op_shift;
        if (sc_op > 0.5) sc_op = 0.5;
    }
    $('#g-up').css({
        //'top': sc_top + 'px',
        'opacity': sc_op
    });
}
function onTop()
{
    $(window).scrollTop(0)
}
function checkPhone(phone)
{
    result = false;
    var r = /^\+?([87](?!95[4-9]|99\d|907|94[^0]|812[^9]|336)([34]\d|9[^7]|8[13]|7[07])\d{8}|855\d{8}|[12456]\d{9,13}|500[56]\d{4}|376\d{6}|8[68]\d{10,11}|8[14]\d{10}|82\d{9,10}|852\d{8}|90\d{10}|96(0[79]|170|13)\d{6}|96[23]\d{9}|964\d{10}|96(5[69]|89)\d{7}|96(65|77)\d{8}|92[023]\d{9}|91[1879]\d{9}|9[34]7\d{8}|959\d{7}|989\d{9}|97\d{8,12}|99[^45]\d{7,11}|994\d{9}|9955\d{8}|380[34569]\d{8}|38[15]\d{9}|375[234]\d{8}|372\d{7,8}|37[0-4]\d{8}|37[6-9]\d{7,11}|30[69]\d{9}|34[67]\d{8}|3[123569]\d{8,12}|38[1679]\d{8}|382\d{8,9})$/;
    if (phone.match(r)) {
        result = true;
    }
    return result;
}
function checkVP(phone)
{
    if (allowCVP) {
        allowCVP = false;
        $.get(
            baseUrl + '/adv/checkValid',
            {'phone' : phone},
            function(data){
                allowCVP = true;
                if (data == 1) {
                    a_check = $('<span class="green"></span>').html('подтверждено');
                    $(".adv_phone").attr("readonly","true").parents(".adv_input").addClass("blocked");
                }
                else {
                    a_check = $('<a></a>', {'href': 'javascript://', 'onclick': 'tp_create(\''+ baseUrl +'/adv/getConfirm?phone=' + phone + '\')'}).html('\u043fодтв\u0435рдить по смс');
                }
                $('.adv_phone_check').html(a_check);
            },
            'html'
        );
    }
}
function check(obj)
{
    phone = obj.val().replace(/[-+ ()]/g, '');
    if (checkPhone(phone)) {
        checkVP(phone);
    }
    else {
        $('.adv_phone_check').html('\u043fодтв\u0435рдить по смс');
    }
}
/*function premium_up() {
    if (allowPrmMove) {
        allowPrmMove = false;
        var prm_lim = parseInt($('.premium_advs').height());
        v_top = $('.premium_advs').css('top');
        v_top = parseInt(v_top);
        if (Math.abs(v_top) > 0) {
            $('.premium_advs').animate({'top': v_top + 193}, 400, function(){allowPrmMove = true;});
        }
        else allowPrmMove = true;    
        
    }
}
function premium_down() {
    if (allowPrmMove) {
        allowPrmMove = false;
        var prm_lim = parseInt($('.premium_advs').height());
        //alert(prm_lim);
        v_top = $('.premium_advs').css('top');
        v_top = parseInt(v_top);
        //alert(v_top);
        if (Math.abs(v_top) + 193 < prm_lim) {
            $('.premium_advs').animate({'top': v_top - 193}, 400, function(){allowPrmMove = true;});
        }
        else allowPrmMove = true;
    }
}*/
function orderBy(id) {
    input = $('<input />').attr({'type' : 'hidden', 'name' : 'order'}).val(id).appendTo($('form'));
    $('form').submit();
    return false;
}

function fb_form_submit(url) {
    if (!url) {url = $('form').attr('action')}
    $('.fancybox-wrap').find('form').ajaxForm({
        beforeSubmit: function() {
            //$.fancybox.showLoading();
            //$(".fancybox-wrap").fadeOut(300);
        },
        url: url,
        success: function (data) {
            switch (data) {
                case 'refresh':
                    location.reload();
                    break;
                case 'close':
                    $.fancybox.hideLoading();
                    $.fancybox.close();
                    break;
                case 'serviceThanks':
                    $('.fancybox-inner').html('<div class="adm-title adm-title-1" style="margin-bottom: 10px;"><h3>Заявка была успешно отправлена. <br/>Наши менеджеры свяжутся с Вами в ближайшее время.</h3></div>');
                    $.fancybox.hideLoading();
                    $.fancybox.update();
                    $(".fancybox-wrap").fadeIn(300);
                    setTimeout(function(){
                        $(".fancybox-wrap").fadeOut(300);
                        location.reload();
                    },4000);
                    break;
                case 'toCompleted':
                    location.href = "/service/adminIndex?type=completed";
                    break;
            }

        }
    });
}