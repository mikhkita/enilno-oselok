<?php echo '<?xml version="1.0" encoding="utf-8"?>'; ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ru" lang="ru">
<head>
	<meta http-equiv="X-UA-Compatible" content="IE=8" />
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="language" content="ru" />
	<title><?php echo $this->title; ?></title>
    <link rel="shortcut icon" href="/favicon2.ico" type="image/x-icon"> 
    <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/jquery.fancybox.css" />
    <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/jquery-ui.min.css" />
    <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/reset.css" />
    <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/shop.css" />

	<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/jquery-2.1.1.min.js"></script>
    <script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/jquery-ui.min.js"></script>
    <script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/jquery.fancybox.js"></script>
    <script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/device.js"></script>
    <script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/KitProgress.js"></script>
    <script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/jquery.validate.min.js"></script>
    <script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/jquery.maskedinput.min.js"></script>
    <script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/KitSend.js"></script>
    <script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/shop.js"></script>
    <?php foreach ($this->scripts AS $script): ?><script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/<?php echo $script?>.js"></script><? endforeach; ?>
</head>
<body> 
    <!--<? if(Yii::app()->params['debug']): ?>
        <div class="b-debug"><?=$this->debugText?></div>
    <? endif; ?>-->
    <div class="b b-header">
        <div class="b-block">
        <a href="http://koleso.tomsk.ru/"><img src="<?php echo Yii::app()->request->baseUrl; ?>/i/logo.png" alt=""></a><span class="b-top-title">Godzilla Wheels</span>
        </div>
    </div>
    <?php echo $content;?>
    <div class="b b-bottom">
        <div class="b-block">
            <h3>© 2015 Godzilla Wheels</h3>
        </div>
    </div>
    <div style="display: none;">
        <div id="b-popup-buy">
            <div class="b-popup b-popup-buy">
                <h3>Для покупки позвоните по одному из телефонов</h3>
                <h4>+7 (913) 827 57-56,</h4>
                <h4>57-57-56</h4>
                <h5>или</h5>
                <h6>оставьте заявку<br>и мы Вам перезвоним в ближайшее время</h6>
                <form action="<?=Yii::app()->createUrl('/shop/mail/')?>" method="POST" data-block="#b-popup-thanks" id="b-form-buy">
                    <input type="hidden" name="good" id="good" required/>
                    <input type="hidden" name="good-url" id="good-url" required/>
                    <input type="text" name="phone" id="phone" placeholder="Ваш номер телефона" required/>
                    <input type="text" name="1" id="region" placeholder="Ваш регион" required/>
                    <input type="hidden" name="1-name" value="Регион" />
                    <input type="hidden" name="2" value="<?=$_SERVER["REMOTE_ADDR"];?>" />
                    <input type="hidden" name="2-name" value="IP-адрес" />
                    <input type="hidden" name="subject" value="Покупка с сайта" />
                    <a href="#" class="ajax b-blue-butt" onclick="$('#b-form-buy').submit(); return false;">Отправить</a>
                    <input type="submit" style="display:none;">
                </form>
            </div>
        </div>
        <div id="b-popup-del" style="display:none;">
            <div class="for_all b-popup clearfix">
                <h3 class="popup-title">Ты уверен, БРАТ?</h3>
                <button type="button" data-href="#" class="left red-btn blue-btn">да, БРАТ</button>
                <button type="button" class="right red-btn" onclick="$.fancybox.close(); return false;" value="Ага">ты мне не брат</button>
            </div>
        </div>
        <div id="b-popup-thanks">
            <div class="b-thanks b-popup">
                <h3>Спасибо за заявку</h3>
                <h4>В ближайшее время мы Вам перезвоним для уточнения заказа</h4>
                <a href="#" class="ajax b-blue-butt" onclick="$.fancybox.close(); return false;">Закрыть</a>
            </div>
        </div>
        <div id="b-popup-error">
            <div class="b-thanks b-popup">
                <h3>Ошибка отправки</h3>
                <h4>Попробуйте оставить заявку позднее или позвоните по телефону</h4>
                <h5>+7 (913) 827-57-56</h5>
                <a href="#" class="ajax b-blue-butt" onclick="$.fancybox.close(); return false;">Закрыть</a>
            </div>
        </div>
    </div>        

</body>
<!-- Yandex.Metrika counter -->
<script type="text/javascript">
    (function (d, w, c) {
        (w[c] = w[c] || []).push(function() {
            try {
                w.yaCounter31382368 = new Ya.Metrika({
                    id:31382368,
                    clickmap:true,
                    trackLinks:true,
                    accurateTrackBounce:true,
                    webvisor:true
                });
            } catch(e) { }
        });

        var n = d.getElementsByTagName("script")[0],
            s = d.createElement("script"),
            f = function () { n.parentNode.insertBefore(s, n); };
        s.type = "text/javascript";
        s.async = true;
        s.src = "https://mc.yandex.ru/metrika/watch.js";

        if (w.opera == "[object Opera]") {
            d.addEventListener("DOMContentLoaded", f, false);
        } else { f(); }
    })(document, window, "yandex_metrika_callbacks");
</script>
<noscript><div><img src="https://mc.yandex.ru/watch/31382368" style="position:absolute; left:-9999px;" alt="" /></div></noscript>
<!-- /Yandex.Metrika counter -->
<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-65038666-1', 'auto');
  ga('send', 'pageview');

</script>
</html>
