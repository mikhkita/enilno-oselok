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
                <li><a href="<?=Yii::app()->createUrl('/kolesoonline/category',array('type' => 2))?>">Диски</a></li>
                <li><a href="<?=Yii::app()->createUrl('/kolesoonline/category',array('type' => 1))?>">Шины</a></li>
                <!-- <li><a href="#">Колеса</a></li> -->
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
                <div class="clearfix city-tabs popup-fo">
                    <ul class="left">
                        <? $i = 0; foreach ($cities as $name => $group): ?>
                            <li><a href="#fo-<?=$i?>"><?=$name?></a></li>
                        <? $i++; endforeach; ?>
                    </ul>
                    <? $i = 0; foreach ($cities as $name => $group): ?>
                        <div id="fo-<?=$i?>" class="popup-cities clearfix left">
                            <? foreach ($group as $col): ?>
                                <ul class="left">
                                    <? foreach ($col as $city): ?>
                                        <li><a href="#"><?=$city['name']?></a></li>
                                    <? endforeach; ?>
                                </ul>
                            <? endforeach; ?>
                        </div>
                    <? $i++; endforeach; ?>
                </div>
            </div>
            <div class="city-input clearfix">
                <?php $form=$this->beginWidget('CActiveForm', array(
                    'enableAjaxValidation'=>false,
                    'method' => 'POST',
                    'id' => "city-form"
                )); ?>
                <select class="city-select left" name="city" required>
                    <option></option>
                    <? foreach ($cities as $name => $group): ?>
                    <optgroup label="<?=$name?>">
                        <? foreach ($group as $col): ?>
                            <? foreach ($col as $city): ?>
                                <option value="<?=$city['name']?>"><?=$city['name']?></option>
                            <? endforeach; ?>
                        <? endforeach; ?>
                    </optgroup>
                    <? endforeach; ?>
                </select>
                <input type="submit" class="right b-orange-butt" value="Выбрать">
                <?php $this->endWidget(); ?> 
            </div>
        </div>
    </div>
</div>