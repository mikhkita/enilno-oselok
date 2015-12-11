<?

$useragent=$_SERVER['HTTP_USER_AGENT'];
$mobile = (preg_match('/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|mobile.+firefox|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows ce|xda|xiino/i',$useragent)||preg_match('/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i',substr($useragent,0,4)));

?>
<!DOCTYPE html>
<html>
<head>
    <title><?=$this->title?></title>
    <meta name="keywords" content='<?=$this->keywords?>'>
    <meta name="description" content='<?=$this->description?>'>

    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="format-detection" content="telephone=no">

    <? if( $mobile ): ?>
        <meta name="viewport" content="width=750, user-scalable=no">
    <? else: ?>
        <meta name="viewport" content="width=device-width, user-scalable=no">
    <? endif; ?>

    <link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/html/css/reset.css" type="text/css">
    <link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/html/css/jquery.fancybox.css" type="text/css">
    <link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/html/css/slick.css" type="text/css">
    <link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/html/css/jquery-ui.css" type="text/css">
    <link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/html/css/select2.css" type="text/css">
    <link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/html/css/KitAnimate.css" type="text/css">
    <link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/html/css/layout.css" type="text/css">

    <link rel="stylesheet" media="screen and (min-width: 240px) and (max-width: 767px)" href="<?php echo Yii::app()->request->baseUrl; ?>/html/css/layout-mobile.css" />
    <link rel="stylesheet" media="screen and (min-width: 768px) and (max-width: 1000px)" href="<?php echo Yii::app()->request->baseUrl; ?>/html/css/layout-tablet.css" />

    <meta property="og:url" content="<?=Yii::app()->getBaseUrl(true).Yii::app()->request->requestUri?>">
    <meta property="og:title" content="<?=$this->title?>">
    <meta property="og:description" content="<?=$this->description?>">
    <meta property="og:image" content="<?=$this->image?>">
    <meta property="twitter:image" content="<?=$this->image?>">
    <link rel="image_src" href="<?=$this->image?>">

    <link rel="icon" type="image/vnd.microsoft.icon" href="favicon.ico">
</head>
<body>
    <ul class="ps-lines">
        <li class="v" style="margin-left:-481px"></li>
        <li class="v" style="margin-left:480px"></li>
        <li class="v" ></li>
    </ul>
    <div class="b b-header">
        <div class="b b-menu">
            <div class="b-block">
                <ul class="clearfix not-mobile">
                    <!-- <li><a href="#">О магазине</a></li> -->
                    <!-- <li><a href="#">Доставка и оплата</a></li> -->
                    <!-- <li><a href="#">Гарантия</a></li> -->
                    <li><a href="<?=Yii::app()->createUrl('/kolesoOnline/contacts')?>">Контакты</a></li>
                </ul>
                <div class="clearfix b-mobile-top-line mobile-only">
                    <h2 class="left">Вы находитесь в г. <a href="#" class="icon fancy" data-block="#b-popup-city"><?=$_SESSION['city']['name']?></a></h2>
                    <a href="#" class="right icon b-mobile-search-top-icon">Поиск</a>
                </div>
            </div>  
        </div>
        <div class="b b-header-main">
            <div class="b-block clearfix">
                <span class="stamp"></span>
                <div class="left">
                    <div class="clearfix">
                        <a class="left b-main-logo" href="<?=Yii::app()->createUrl('/kolesoOnline')?>"></a>
                        <div class="right">
                            <h1>Колесо<span>онлайн</span></h1>
                            <h2>Вы находитесь в г. <a href="#" class="fancy" data-block="#b-popup-city"><?=$_SESSION['city']['name']?></a></h2>
                        </div>
                    </div>
                </div>
                <div class="right">
                    <div class="clearfix contacts">
                        <a href="tel:+79095430402" class="mobile-not-fancy fancy left" data-block="#b-popup-callback">+7 (909) 543-04-02</a>
                        <a href="mailto:kolesotomskru@mail.ru" class="left mail">kolesotomskru@mail.ru</a>
                    </div>
                    <form action="#" method="GET">
                        <input type="text" name="search" placeholder="Поиск">
                        <button class="b-orange-butt">Поиск</button>
                    </form>
                </div>
                
            </div>
        </div>
        <div class="b b-sub-menu b-relative-top gradient-orange">
            <div class="b-block clearfix">
                <a href="#" class="b-burger icon left"></a>
                <ul class="clearfix left">
                    <li><a href="<?=Yii::app()->createUrl('/kolesoOnline/category',array('type' => 2))?>">Диски</a></li>
                    <li><a href="<?=Yii::app()->createUrl('/kolesoOnline/category',array('type' => 1))?>">Шины</a></li>
                    <!--<li><a href="<?=Yii::app()->createUrl('/kolesoOnline/category',array('type' => 3))?>">Колеса</a></li>-->
                </ul>
            </div>
        </div>
        <div class="b b-sub-menu b-fixed-top gradient-orange mobile-only">
            <div class="b-block clearfix">
                <a href="#" class="b-burger icon left"></a>
                <a href="tel:+79095430402" class="mobile-not-fancy fancy b-phone-center left" data-block="#b-popup-callback">+7 (909) 543-04-02</a>
                <a href="#" class="b-search-icon icon right"></a>
            </div>
        </div>
    </div>
        <?php echo $content;?>
    <div class="b b-footer">
        <div class="b-footer-main">
            <div class="b-block">
                <span class="stamp stamp-left"></span>
                <span class="stamp stamp-right"></span>
                <ul class="sections clearfix">
                    <li>
                        <h3>О магазине</h3>
                        <a href="<?=Yii::app()->createUrl('/kolesoOnline')?>" class="footer-logo clearfix">
                            <span class="b-footer-logo"></span>
                        </a>
                        <p>Лучший выбор автомобильных б/у шин и дисков из Японии. Удобный поиск и выгодные цены, а самое главное честное описание и фото. Мы постоянно работаем над расширением географии наших представительств на территории РФ.</p>
                    </li>
                    <li>
                        <h3>Разделы</h3>
                        <ul>
                            <li><a href="<?=Yii::app()->createUrl('/kolesoOnline/category',array('type' => 2))?>">Диски</a></li>
                            <li><a href="<?=Yii::app()->createUrl('/kolesoOnline/category',array('type' => 1))?>">Шины</a></li>
                            <!-- <li><a href="#">Колеса</a></li> -->
                            <li><a href="<?=Yii::app()->createUrl('/kolesoOnline/contacts')?>">Контакты</a></li>
                            <!-- <li><a href="#">Оплата и доставка</a></li> -->
                            <!-- <li><a href="#">Гарантия</a></li> -->
                        </ul>
                    </li>
                    <li>
                        <h3>Контактная информация</h3>
                        <a class="footer-contacts mail" href="mailto:kolesotomskru@mail.ru">kolesotomskru@mail.ru</a>
                        <a class="mobile-not-fancy fancy footer-contacts phone" data-block="#b-popup-callback" href="tel:+79095430402">+7 (909) 543-04-02</a>
                        <span class="footer-contacts map">г. Красноярск, ул. Вавилова, 1а</span>
                        <div class="social">
                            <h3>Присоединяйтесь к нам</h3>
                            <div class="social-icon clearfix">
                                <a class="tw" href="#"></a>
                                <a class="yt" href="#"></a>
                                <a class="inst" href="#"></a>
                                <a class="vk" href="#"></a>
                                <a class="fb" href="#"></a>
                            </div>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
        <div class="b-footer-sub">
            <div class="b-block clearfix">
                <h3 class="left">© 2015 Колесо Онлайн</h3>
                <!-- <h3 class="right">Разработано в <a href="#">True Media</a></h3> -->
            </div>
        </div>
    </div>
    <? 
        // list($queryCount, $queryTime) = Yii::app()->db->getStats();
        // echo "Кол-во запросов: $queryCount, Общее время запросов: ".sprintf('%0.5f',$queryTime)."s";
    ?>
<div style="display:none;">
    <div id="b-popup-callback">
        <div class="for_all b-popup-small">
            <h3>Заказать звонок</h3>
            <h4>Оставьте заявку и мы Вам перезвоним<br>в ближайшее время:</h4>
            <form action="<?=Yii::app()->createUrl('/kolesoOnline/mail/')?>" method="POST"  data-block="#b-popup-2">
                <div class="b-popup-form">
                    <label for="name">Ваше имя</label>
                    <input type="text" name="name" required placeholder="Иван"/>
                    <label for="tel">Ваш телефон</label>
                    <input type="text" name="phone" required placeholder="+7 (___) ___-__-__"/>
                    <input type="hidden" name="subject" value="Обратный звонок"/>
                </div>
                <input type="submit" class="ajax b-orange-butt" value="Отправить">
            </form>
        </div>
    </div>
    <div id="b-popup-buy">
        <div class="for_all b-popup-small">
            <h3>Купить товар</h3>
            <h4>Для покупки позвоните по одному<br>из телефонов:</h4>
            <h5><a href='tel:+79138275756'>+7 (913) 827 57-56</a><a href="tel:575756">57-57-56</a></h5>
            <h4>Или оставьте заявку и мы Вам перезвоним в ближайшее время:</h4>
            <form action="<?=Yii::app()->createUrl('/kolesoOnline/mail/')?>" id="b-form-buy" method="POST" data-block="#b-popup-2">
                <div class="b-popup-form">
                    <label for="name">Ваше имя</label>
                    <input type="text" name="name" required placeholder="Иван"/>
                    <label for="tel">Ваш телефон</label>
                    <input type="text" name="phone" required placeholder="+7 (___) ___-__-__"/>

                    <input type="hidden" name="good" id="good" required/>
                    <input type="hidden" name="good-url" id="good-url" required/>
                    <input type="hidden" name="1" value="<?=$_SERVER["REMOTE_ADDR"];?>" />
                    <input type="hidden" name="1-name" value="IP-адрес" />
                    <input type="hidden" name="subject" value="Покупка с сайта" />
                    <input type="submit" style="display:none;">
                </div>
                <a href="#" class="ajax b-orange-butt" onclick="$('#b-form-buy').submit(); return false;">Отправить</a>
                <!-- <input type="submit" class="ajax b-orange-butt" value="Отправить"> -->
            </form>
        </div>
    </div>
    <div id="b-popup-2">
        <div class="for_all b-popup-small">
            <h3>Спасибо за заявку!</h3>
            <h4>Ваша заявка успешно отправлена.<br>Наш менеджер свяжется с Вами<br>в течение 15 минут.</h4>
            <input type="submit" class="b-orange-butt" onclick="$.fancybox.close(); return false;" value="Закрыть">
        </div>
    </div>
    <div id="b-popup-error">
        <div class="b-thanks b-popup">
            <h3>Ошибка отправки!</h3>
            <h4>Приносим свои извинения. Пожалуйста, попробуйте отправить Вашу заявку позже.</h4>
            <input type="submit" class="b-orange-butt" onclick="$.fancybox.close(); return false;" value="Закрыть">
        </div>
    </div>
</div>

<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/html/js/jquery-2.1.1.min.js"></script>
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/html/js/jquery.fancybox.js"></script>
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/html/js/TweenMax.min.js"></script>
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/html/js/swipe.js"></script>
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/html/js/fastclick.js"></script>
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/html/js/css3-mediaqueries.js"></script>
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/html/js/jquery.maskedinput.min.js"></script>
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/html/js/jquery.validate.min.js"></script>
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/html/js/slick.min.js"></script>
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/html/js/jquery-ui.min.js"></script>
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/html/js/jquery.ui.touch-punch.js"></script>
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/html/js/KitProgress.js"></script>
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/html/js/KitAnimate.js"></script>
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/html/js/device.js"></script>
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/html/js/select2.full.min.js"></script>
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/html/js/i18n/ru.js"></script>
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/html/js/KitSend.js"></script>
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/html/js/main.js"></script>
<!-- Yandex.Metrika counter --><script type="text/javascript"> (function (d, w, c) { (w[c] = w[c] || []).push(function() { try { w.yaCounter34102235 = new Ya.Metrika({ id:34102235, clickmap:true, trackLinks:true, accurateTrackBounce:true, webvisor:true }); } catch(e) { } }); var n = d.getElementsByTagName("script")[0], s = d.createElement("script"), f = function () { n.parentNode.insertBefore(s, n); }; s.type = "text/javascript"; s.async = true; s.src = "https://mc.yandex.ru/metrika/watch.js"; if (w.opera == "[object Opera]") { d.addEventListener("DOMContentLoaded", f, false); } else { f(); } })(document, window, "yandex_metrika_callbacks");</script><noscript><div><img src="https://mc.yandex.ru/watch/34102235" style="position:absolute; left:-9999px;" alt="" /></div></noscript><!-- /Yandex.Metrika counter -->
</body>
</html>