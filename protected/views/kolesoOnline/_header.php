<div class="b b-header">
    <div class="b b-menu">
        <div class="b-block">
            <ul class="clearfix not-mobile">
                <li><a href="#">О магазине</a></li>
                <li><a href="#">Доставка и оплата</a></li>
                <li><a href="#">Гарантия</a></li>
                <li><a href="#">Контакты</a></li>
            </ul>
            <div class="clearfix b-mobile-top-line mobile-only">
                <h2 class="left">Вы находитесь в г. <a href="#" class="icon"><?=$_SESSION['city']['name']?></a></h2>
                <a href="#" class="right icon b-mobile-search-top-icon">Поиск</a>
            </div>
        </div>  
    </div>
    <div class="b b-header-main">
        <div class="b-block clearfix">
            <span class="stamp"></span>
            <div class="left">
                <div class="clearfix">
                    <a class="left" href="<?=Yii::app()->createUrl('/kolesoonline')?>"><img src="<?php echo Yii::app()->request->baseUrl; ?>/html/i/logo-2.png"></a>
                    <div class="right">
                        <h1>Колесо<span>онлайн</span></h1>
                        <h2>Вы находитесь в г. <a href="#" class="fancy" data-block="#b-popup-city"><?=$_SESSION['city']['name']?></a></h2>
                    </div>
                </div>
            </div>
            <div class="right">
                <div class="clearfix contacts">
                    <a href="#" class="fancy left" data-block="#b-popup-callback">+7 (999) 321-11-22</a>
                    <a href="mailto:koleso@yandex.ru" class="left mail">koleso@yandex.ru</a>
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
                <li><a href="#">Диски</a></li>
                <li><a href="#">Шины</a></li>
                <li><a href="#">Колеса</a></li>
            </ul>
        </div>
    </div>
    <div class="b b-sub-menu b-fixed-top gradient-orange mobile-only">
        <div class="b-block clearfix">
            <a href="#" class="b-burger icon left"></a>
            <a href="callto:+79993211122" class="fancy b-phone-center left" data-block="#b-popup-callback">+7 (999) 321-11-22</a>
            <a href="#" class="b-search-icon icon right"></a>
        </div>
    </div>
</div>
<div style="display:none">
    <div id="b-popup-city">
        <div class="for_all b-popup-city">
            <div class="city-top">
                <h3>Выбор города</h3>
                <h4>Федеральный округ<span>Город</span></h4>
                <div class="clearfix main-tabs popup-fo">
                    <ul class="left">
                        <? $i = 0; foreach ($cities as $name => $group): ?>
                            <li><a href="#fo-<?=$i?>"><?=$name?></a></li>
                        <? $i++; endforeach; ?>
                    </ul>
                    <div id="sib-fo" class="popup-cities clearfix left">
                        <ul class="left">
                            <li><a href="#">Кемерово</a></li>
                            <li><a href="#">Новосибирск</a></li>
                            <li><a href="#">Новокузнецк</a></li>
                            <li><a href="#">Ленинск-Кузнецкий</a></li>
                            <li><a href="#">Белово</a></li>
                            <li><a href="#">Юрга</a></li>
                            <li><a href="#">Полысаево</a></li>
                            <li><a href="#">Барнаул</a></li>    
                        </ul>
                        <ul class="left">
                            <li><a href="#">Новокузнецк</a></li>
                            <li><a href="#">Бердск</a></li>
                            <li class="active"><a href="#">Тяжин</a></li>
                            <li><a href="#">Березовский</a></li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="city-input clearfix">
                <input type="text" class="left" placeholder="Или укажите в поле...">
                <input type="submit" class="right b-orange-butt" value="Выбрать">
            </div>
        </div>
    </div>
</div>