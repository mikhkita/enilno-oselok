<?
$mobile = (preg_match('/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|mobile.+firefox|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows ce|xda|xiino/i',$_SERVER['HTTP_USER_AGENT'])||preg_match('/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i',substr($_SERVER['HTTP_USER_AGENT'],0,4)));
?>
<!DOCTYPE html>
<html>
<head>
    <title><?=$this->cityReplace($this->title)?></title>
    <meta name="keywords" content='<?=$this->cityReplace($this->keywords)?>'>
    <meta name="description" content='<?=$this->cityReplace($this->description)?>'>
    
    <script language="javascript">
    // document.ondragstart = xenforo;
    // document.onselectstart = xenforo;
    document.oncontextmenu = xenforo;
    function xenforo() {return false}
    </script>

    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="format-detection" content="telephone=no">

    <? if( $mobile ): ?>
        <meta name="viewport" content="width=750, user-scalable=no">
    <? else: ?>
        <meta name="viewport" content="width=device-width, user-scalable=no">
    <? endif; ?>
    
    <? $css_version = 1; ?>
    <link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/html/css/reset.css?<?=$css_version?>" type="text/css">
    <link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/html/css/jquery.fancybox.css?<?=$css_version?>" type="text/css">
    <link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/html/css/slick.css?<?=$css_version?>" type="text/css">
    <link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/html/css/jquery-ui.css?<?=$css_version?>" type="text/css">
    <link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/html/css/select2.css?<?=$css_version?>" type="text/css">
    <link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/html/css/jquery.mobile.custom.structure.css?<?=$css_version?>" type="text/css">
    <link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/html/css/jquery.mobile.custom.theme.css?<?=$css_version?>" type="text/css">
    <link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/html/css/stroll.css?<?=$css_version?>" type="text/css">
    <link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/html/css/KitAnimate.css?<?=$css_version?>" type="text/css">
    <link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/html/css/layout.css?<?=$css_version?>" type="text/css">

    <link rel="stylesheet" media="screen and (min-width: 240px) and (max-width: 767px)" href="<?php echo Yii::app()->request->baseUrl; ?>/html/css/layout-mobile.css" />
    <!-- <link rel="stylesheet" media="screen and (min-width: 768px) and (max-width: 1000px)" href="<?php echo Yii::app()->request->baseUrl; ?>/html/css/layout-tablet.css" /> -->

    <meta property="og:url" content="<?=Yii::app()->getBaseUrl(true).Yii::app()->request->requestUri?>">
    <meta property="og:title" content="<?=$this->cityReplace($this->title)?>">
    <meta property="og:description" content="<?=$this->cityReplace($this->description)?>">
    <meta property="og:image" content="<?=$this->image?>">
    <meta property="twitter:image" content="<?=$this->image?>">
    <link rel="image_src" href="<?=$this->image?>">

    <link rel="apple-touch-icon-precomposed" sizes="57x57" href="<?php echo Yii::app()->request->baseUrl; ?>/html/icon/apple-touch-icon-57x57.png" />
    <link rel="apple-touch-icon-precomposed" sizes="114x114" href="<?php echo Yii::app()->request->baseUrl; ?>/html/icon/apple-touch-icon-114x114.png" />
    <link rel="apple-touch-icon-precomposed" sizes="72x72" href="<?php echo Yii::app()->request->baseUrl; ?>/html/icon/apple-touch-icon-72x72.png" />
    <link rel="apple-touch-icon-precomposed" sizes="144x144" href="<?php echo Yii::app()->request->baseUrl; ?>/html/icon/apple-touch-icon-144x144.png" />
    <link rel="apple-touch-icon-precomposed" sizes="60x60" href="<?php echo Yii::app()->request->baseUrl; ?>/html/icon/apple-touch-icon-60x60.png" />
    <link rel="apple-touch-icon-precomposed" sizes="120x120" href="<?php echo Yii::app()->request->baseUrl; ?>/html/icon/apple-touch-icon-120x120.png" />
    <link rel="apple-touch-icon-precomposed" sizes="76x76" href="<?php echo Yii::app()->request->baseUrl; ?>/html/icon/apple-touch-icon-76x76.png" />
    <link rel="apple-touch-icon-precomposed" sizes="152x152" href="<?php echo Yii::app()->request->baseUrl; ?>/html/icon/apple-touch-icon-152x152.png" />
    <link rel="icon" type="image/png" href="<?php echo Yii::app()->request->baseUrl; ?>/html/icon/favicon-96x96.png" sizes="96x96" />
    <link rel="icon" type="image/png" href="<?php echo Yii::app()->request->baseUrl; ?>/html/icon/favicon-32x32.png" sizes="32x32" />
    <link rel="icon" type="image/png" href="<?php echo Yii::app()->request->baseUrl; ?>/html/icon/favicon-16x16.png" sizes="16x16" />
    <link rel="icon" type="image/png" href="<?php echo Yii::app()->request->baseUrl; ?>/html/icon/favicon-128.png" sizes="128x128" />
    <meta name="application-name" content="Колесо Онлайн"/>
    <meta name="msapplication-TileColor" content="#FFFFFF" />
    <meta name="msapplication-TileImage" content="<?php echo Yii::app()->request->baseUrl; ?>/html/icon/mstile-144x144.png" />
    <meta name="msapplication-square70x70logo" content="<?php echo Yii::app()->request->baseUrl; ?>/html/icon/mstile-70x70.png" />
    <script type="text/javascript" src="//vk.com/js/api/openapi.js?121"></script>
    <? if(isset(Yii::app()->params['server']) && Yii::app()->params['server'] === true): ?>
    <script>
      (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
      (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
      m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
      })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

      ga('create', 'UA-71817799-1', 'auto');
      ga('send', 'pageview');

    </script>
    <? endif; ?>
    <script type="text/javascript">(window.Image ? (new Image()) : document.createElement('img')).src = location.protocol + '//vk.com/rtrg?r=wReeJi7haO54u82VAm4SkJa7i3u/s8Y0HFxl1u2ifSR6NbgZ7pl*IyCZUQpNlerjmMncA53Q6uwz1y9W/xeJfUcGEy7Cl2C6h5FqWx5VmPmH4G8w7x0HlLzMflJ4htZhPPBOEvpzIar3I*1E/sGRbUjnRcJ*wkubMrY4LQW*ui8-&pixel_id=1000091393';</script>
</head>
<body>
    <ul class="ps-lines">
        <li class="v" style="margin-left:-481px"></li>
        <li class="v" style="margin-left:480px"></li>
        <li class="v" ></li>
    </ul>
    <div class="b b-header">
        <div class="b-mobile-menu">
            <a class="left b-main-logo b-mobile-menu-a" href="<?=Yii::app()->createUrl('/kolesoOnline')?>"></a>
            <ul>
                <!-- <li><a href="<?=Yii::app()->createUrl('/about')?>" class="b-mobile-menu-a">О магазине</a></li> -->
                <li><a href="<?=Yii::app()->createUrl('/delivery.html')?>" class="b-mobile-menu-a">Доставка</a></li>
                <li><a href="<?=Yii::app()->createUrl('/guarantees.html')?>" class="b-mobile-menu-a">Гарантии</a></li>
                <li><a href="<?=Yii::app()->createUrl('/payment.html')?>" class="b-mobile-menu-a">Способы оплаты</a></li>
                <li><a href="<?=Yii::app()->createUrl('/contacts.html')?>" class="b-mobile-menu-a">Контакты</a></li>
                <li><a href="<?=Yii::app()->createUrl('/kolesoOnline/pay')?>" class="b-mobile-menu-a">Оплата заказа</a></li>
            </ul>
            <a href="tel:+<?=Yii::app()->params["city"]->phone?>" class="b-menu-call b-orange-butt">Позвонить</a>
        </div>
        <div class="b b-menu">
            <div class="b-block">
                <ul class="clearfix not-mobile">
                    <li><a href="<?=Yii::app()->createUrl('/delivery.html')?>">Доставка</a></li>
                    <li><a href="<?=Yii::app()->createUrl('/guarantees.html')?>">Гарантии</a></li>
                    <li><a href="<?=Yii::app()->createUrl('/payment.html')?>">Способы оплаты</a></li>
                    <li><a href="<?=Yii::app()->createUrl('/contacts.html')?>">Контакты</a></li>
                    <li><a href="<?=Yii::app()->createUrl('/kolesoOnline/pay')?>">Оплата заказа</a></li>
                </ul>
                <div class="clearfix b-mobile-top-line mobile-only">
                    <h2 class="left">Вы находитесь в г. <a href="#" class="icon fancy" data-block="#b-popup-city"><?=Yii::app()->params["city"]->name?></a></h2>
                    <a href="#" class="right icon b-mobile-search-top-icon">Поиск</a>
                </div>
            </div>  
        </div>
        <div class="b b-header-main">
            <div class="b-block clearfix">
                <span class="stamp"></span>
                <div class="left">
                    <? if(Yii::app()->params["region"]): ?>
                        <?  $this->renderPartial(Yii::app()->params["city"]->code.'/_logo'); ?>
                    <? else: ?>
                        <div class="clearfix">
                            <a class="left b-main-logo" href="<?=Yii::app()->createUrl('/kolesoOnline')?>"></a>
                            <div class="right">
                                <h2>Вы находитесь в г. <a href="#" class="fancy" data-block="#b-popup-city"><?=Yii::app()->params["city"]->name?></a></h2>
                            </div>
                        </div>
                    <? endif; ?>
                </div>
                <h1>Колесо.Томск.Ру - БУ шины и диски в Томске</h1>
                <div class="right">
                    <div class="clearfix contacts">
                        <? 
                            $phone = str_split(Yii::app()->params["city"]->phone); 
                            $phone = $phone[0]." (".$phone[1].$phone[2].$phone[3].") ".$phone[4].$phone[5].$phone[6]."-".$phone[7].$phone[8]."-".$phone[9].$phone[10];
                        ?>
                        <? /* ?><a href="tel:+<?=Yii::app()->params["city"]->phone?>" class="mobile-not-fancy fancy left" data-block="#b-popup-callback">+<?=$phone?></a><? */ ?>
                        <a href="mailto:info@koleso.online" class="left mail">info@koleso.online</a>
                        <!-- <a href="#" class="right basket">Корзина (0)</a> -->
                    </div>
                    <form action="<?=Yii::app()->createUrl('kolesoOnline/search')?>" method="GET" id="search-form" class="b-search-form">
                        <input type="text" id="search" autocomplete="off" name="search" placeholder="Поиск">
                        <button type="submit" class="icon b-orange-butt">Поиск</button>
                        <ul class="b-search-results"></ul>
                        <a href="#" class="b-orange-butt b-search-close">Закрыть</a>
                    </form>
                </div>
                
            </div>
        </div>
        <div class="b-sub-menu-cont">
            <div class="b b-sub-menu b-relative-top gradient-orange">
                <div class="b-block clearfix">
                    <a href="#" class="b-burger icon left"></a>
                    <ul class="b-sub-menu-list clearfix left">
                        <li><a href="<?=Yii::app()->createUrl('/kolesoOnline/category',array('type' => 2))?>">Диски</a></li>
                        <li><a href="<?=Yii::app()->createUrl('/kolesoOnline/category',array('type' => 1))?>">Шины</a></li>
                        <li><a href="<?=Yii::app()->createUrl('/kolesoOnline/category',array('type' => 3))?>">Колеса</a></li>
                    </ul>
                    <? if(!$mobile):?>
                    <div id="minicart" class="right">
                        <div class="b-cart-menu-cont">
                            <div class="b-cart-menu gradient-grey">
                                <h2 class="close-cart">Корзина</h2>
                                <ul class="b-cart-items clearfix">
                                    <?  if(isset($_SESSION["BASKET"]) && $_SESSION["BASKET"]) 
                                        $this->renderPartial('_basket',array('partial' => false)); 
                                    ?>
                                </ul>
                                <div class="b-cart-bottom clearfix">
                                    <div class="b-total-price left"><span>0</span> руб.</div>
                                    <a id="cart-href" href="/cart" class="b-orange-butt right">Оформить</a>
                                </div>
                            </div>
                        </div>
                        <? $count = (isset($_SESSION["BASKET"]) && count($_SESSION["BASKET"])) ? count($_SESSION["BASKET"]) : 0; ?>
                        <a href="#" class="fixed-link basket right <? if(!$count) echo 'empty'; ?>">Корзина (<span><?=$count?></span>)</a>    
                    </div>
                    <a class="fixed-link right fancy" href="#" data-block="#b-popup-callback">Заказать звонок</a>
                    <? endif;?>
                </div>
            </div>
        </div>
        <div class="b b-sub-menu b-fixed-top gradient-orange mobile-only">
            <div class="b-block clearfix">
                <a href="#" class="b-burger icon left"></a>
                <!-- <a href="tel:+<?=Yii::app()->params["city"]->phone?>" class="mobile-not-fancy fancy b-phone-center left" data-block="#b-popup-callback">+<?=$phone?></a> -->
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
                        <? if(Yii::app()->params["region"]): ?>
                            <?  $this->renderPartial(Yii::app()->params["city"]->code.'/_logo'); ?>
                            <p>Ежедневное пополнение каталога.<br>Достоверные фотографии и описания.<br>Наши фирменные гарантии.<br>Профессионалы своего дела.</p>
                        <? else: ?>
                            <a href="<?=Yii::app()->createUrl('/kolesoOnline')?>" class="footer-logo clearfix">
                                <span class="b-footer-logo"></span>
                            </a>
                            <p>Более тысячи позиций <nobr>б/у</nobr>&nbsp;шин и&nbsp;дисков. Полная актуальность всех имеющихся товаров на сайте. Ежедневное пополнение каталога. Достоверные фотографии и&nbsp;описания. Наши фирменные гарантии. Профессионалы своего дела.</p>
                        <? endif; ?>
                    </li>
                    <li>
                        <h3>Разделы</h3>
                        <ul>
                            <li><a href="<?=Yii::app()->createUrl('/kolesoOnline/category',array('type' => 2))?>">Диски</a></li>
                            <li><a href="<?=Yii::app()->createUrl('/kolesoOnline/category',array('type' => 1))?>">Шины</a></li>
                            <li><a href="<?=Yii::app()->createUrl('/kolesoOnline/category',array('type' => 3))?>">Колеса</a></li>
                            <li><a href="<?=Yii::app()->createUrl('/delivery.html')?>">Доставка</a></li>
                            <li><a href="<?=Yii::app()->createUrl('/guarantees.html')?>">Гарантии</a></li>
                            <li><a href="<?=Yii::app()->createUrl('/payment.html')?>">Способы оплаты</a></li>
                            <li><a href="<?=Yii::app()->createUrl('/contacts.html')?>">Контакты</a></li>
                        </ul>
                    </li>
                    <li>
                        <h3>Контактная информация</h3>
                        <a class="footer-contacts mail" href="mailto:info@koleso.online">info@koleso.online</a>
                        <!-- <a class="mobile-not-fancy fancy footer-contacts phone" data-block="#b-popup-callback" href="tel:+<?=Yii::app()->params["city"]->phone?>">+<?=$phone?></a> -->
                        <? if(Yii::app()->params["city"]->id == 1081): ?>
                            <!-- <span class="footer-contacts map">г. Томск, Комсомольский проспект, 7, стр. 9</span> -->
                        <? else: ?>
                            <br>
                        <? endif; ?>
                       <div class="social clearfix">
                            <!-- <h3>Присоединяйтесь к нам</h3>
                            <div id="vk_subscribe"></div>
                            <script type="text/javascript">
                            VK.Widgets.Subscribe("vk_subscribe", {soft: 1}, -118079986);
                            </script> -->
                            <!-- <div class="social-icon clearfix">
                                <a class="tw" href="#"></a>
                                <a class="yt" href="#"></a>
                                <a class="inst" href="#"></a>
                                <a class="vk" href="#"></a>
                                <a class="fb" href="#"></a>
                            </div> -->
                        </div>
                    </li>
                </ul>
            </div>
        </div>
        <div class="b-footer-sub">
            <div class="b-block clearfix">
                <? if(Yii::app()->params["region"]): ?>
                    <h3 class="left"><?  $this->renderPartial(Yii::app()->params["city"]->code.'/_copyright'); ?></h3>
                <? else: ?>
                    <h3 class="left">© 2015 Колесо Онлайн</h3>
                <? endif; ?>
                <h3 class="right b-copyright">Дизайн и разработка <a href="http://redder.pro/" target="_blank" class="b-redder"></a></h3>
            </div>
        </div>
    </div>
    <span class="exit fancy" data-block="#b-popup-exit"></span>
<div style="display:none;">
    <div id="b-popup-exit">
        <div class="for_all b-popup-small">
            <h3>Не нашли подходящий товар?</h3>
            <h4 class="exit-desc">Оставьте заявку и наши менеджеры подберут вам дополнительные варианты</h4>
            <form action="<?=Yii::app()->createUrl('/kolesoOnline/mail/')?>" id="b-form-exit" method="POST"  data-block="#b-popup-2">
                <div class="b-popup-form">
                    <label for="phone">Ваш телефон *</label>
                    <input type="text" name="phone" required placeholder="+7 (___) ___-__-__"/>               
                    <label for="email">Ваш E-mail</label>
                    <input type="text" name="email" placeholder="info@koleso.online"/>
                    <label for="name">Ваши параметры</label>
                    <textarea type="text" name="1" placeholder="R17, сверловка 5*114.3, ширина 7 на mazda 3"></textarea>
                    <input type="hidden" name="1-name" value="Параметры" />
                    <input type="hidden" name="subject" value="Подбор товара"/>
                </div>
                <input type="submit" class="ajax b-orange-butt" value="Отправить">
            </form>
        </div>
    </div>
    <div id="b-popup-city">
        <?php $this->renderPartial('_cities', array('cities' => $this->getCityGroups(),"show" => Yii::app()->params["city"]->popup )); ?>
    </div>
    <div id="b-popup-callback">
        <div class="for_all b-popup-small">
            <h3>Заказать звонок</h3>
            <!-- <h4>Для получения подробной информации Вы можете позвонить по телефону:</h4> -->
            <!-- <h5><a href='tel:+<?=Yii::app()->params["city"]->phone?>'>+<?=$phone?></a></h5> -->
            <h4>Оставьте заявку и мы Вам перезвоним в ближайшее время:</h4>
            <form action="<?=Yii::app()->createUrl('/kolesoOnline/mail/')?>" id="b-form-call" method="POST"  data-block="#b-popup-2">
                <div class="b-popup-form">
                    <label for="name">Ваше имя *</label>
                    <input type="text" name="name" required placeholder="Иван"/>
                    <label for="tel">Ваш телефон *</label>
                    <input type="text" name="phone" required placeholder="+7 (___) ___-__-__"/>
                    <input type="hidden" name="subject" value="Обратный звонок"/>
                </div>
                <input type="submit" class="ajax b-orange-butt" value="Отправить">
            </form>
        </div>
    </div>
    <div id="b-popup-buy">
        <div class="for_all b-popup-small">
            <h3>Уточнить цену</h3>
            <!-- <h4>Для получения подробной информации Вы можете позвонить по телефону:</h4> -->
            <!-- <h5><a href='tel:+<?=Yii::app()->params["city"]->phone?>'>+<?=$phone?></a></h5> -->
            <h4>Оставьте заявку и мы Вам перезвоним в ближайшее время:</h4>
            <form action="<?=Yii::app()->createUrl('/kolesoOnline/mail/')?>" id="b-form-buy" method="POST" data-block="#b-popup-2">
                <div class="b-popup-form">
                    <label for="name">Ваше имя *</label>
                    <input type="text" name="name" required placeholder="Иван"/>
                    <label for="tel">Ваш телефон *</label>
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
    <div id="b-popup-pay">
        <div class="for_all b-popup-small">
            <h3>Оплата заказа</h3>
            <form action="<?=Yii::app()->createUrl('/kolesoOnline/pay')?>" id="b-form-pay" method="POST" data-block="#b-popup-2">
                <div class="b-popup-form">
                    <label for="name">Номер заказа *</label>
                    <input type="text" name="name" required placeholder="1234"/>
                    <input type="submit" style="display:none;">
                </div>
                <a href="#" class="ajax b-orange-butt" onclick="$('#b-form-pay').submit(); return false;">Оплатить</a>
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
        <div class="for_all b-popup-small">
            <h3>Ошибка отправки!</h3>
            <h4>Приносим свои извинения. Пожалуйста, попробуйте отправить Вашу заявку позже.</h4>
            <input type="submit" class="b-orange-butt" onclick="$.fancybox.close(); return false;" value="Закрыть">
        </div>
    </div>
</div>

<script type="text/javascript" src="/html/js/jquery-2.1.1.min.js"></script>
<script type="text/javascript" src="/html/js/jquery.fancybox.js"></script>
<? if( $mobile ): ?>
<script type="text/javascript" src="/html/js/TweenMax.min.js"></script>
<script type="text/javascript" src="/html/js/fastclick.js"></script>
<? endif; ?>
<script type="text/javascript" src="/html/js/jquery.maskedinput.min.js"></script>
<script type="text/javascript" src="/html/js/jquery.validate.min.js"></script>
<script type="text/javascript" src="/html/js/slick.min.js"></script>
<script type="text/javascript" src="/html/js/jquery-ui.min.js"></script>
<script type="text/javascript" src="/html/js/jquery.ui.touch-punch.js"></script>
<script type="text/javascript" src="/html/js/device.js"></script>
<script type="text/javascript" src="/html/js/select2.full.min.js"></script>
<script type="text/javascript" src="/html/js/stroll.js"></script>
<script type="text/javascript" src="/html/js/jquery.zoom.min.js"></script>
<script type="text/javascript" src="/html/js/i18n/ru.js"></script>
<script type="text/javascript" src="/html/js/jquery.cookie.js"></script>
<script type="text/javascript" src="/html/js/KitSend.js"></script>
<script type="text/javascript" src="/html/js/main.js"></script>

<? if(isset(Yii::app()->params['server']) && Yii::app()->params['server'] === true && !isset($this->user->usr_id)): ?>
    <!-- Yandex.Metrika counter --><script type="text/javascript"> (function (d, w, c) { (w[c] = w[c] || []).push(function() { try { w.yaCounter34102235 = new Ya.Metrika({ id:34102235, clickmap:true, trackLinks:true, accurateTrackBounce:true, webvisor:true }); } catch(e) { } }); var n = d.getElementsByTagName("script")[0], s = d.createElement("script"), f = function () { n.parentNode.insertBefore(s, n); }; s.type = "text/javascript"; s.async = true; s.src = "https://mc.yandex.ru/metrika/watch.js"; if (w.opera == "[object Opera]") { d.addEventListener("DOMContentLoaded", f, false); } else { f(); } })(document, window, "yandex_metrika_callbacks");</script><noscript><div><img src="https://mc.yandex.ru/watch/34102235" style="position:absolute; left:-9999px;" alt="" /></div></noscript><!-- /Yandex.Metrika counter -->
    <?if(!$mobile && 0):?>
        <!-- BEGIN JIVOSITE CODE {literal} -->
        <script type='text/javascript'>
        (function(){ var widget_id = 'ACaUy3Bqm4';
        var s = document.createElement('script'); s.type = 'text/javascript'; s.async = true; s.src = '//code.jivosite.com/script/widget/'+widget_id; var ss = document.getElementsByTagName('script')[0]; ss.parentNode.insertBefore(s, ss);})();</script>
        <!-- {/literal} END JIVOSITE CODE -->
    <? endif; ?>
<? endif; ?>
</body>
</html>