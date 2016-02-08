<? echo '<?xml version="1.0" encoding="utf-8"?>'; ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ru" lang="ru">
<head>
	<meta http-equiv="X-UA-Compatible" content="IE=8" />
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="language" content="ru" />
	<title><?php echo $this->pageTitle; ?></title>
    <link rel="shortcut icon" href="/favicon2.ico" type="image/x-icon"> 
    <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/reset.css" />
    <link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/css/jquery.qtip.min.css" type="text/css">
    <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/jquery.fancybox.css" />
    <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/jquery-ui.min.css" />
    <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/jquery-ui.theme.css" />
    <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/preloader.css" />
    <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/select2.css" />
    <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/js/plupload/jquery.plupload.queue/css/jquery.plupload.queue.css" />
    <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/ionicons/css/ionicons.min.css" />
    <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/admin.css" />

    <link rel="apple-touch-icon-precomposed" sizes="57x57" href="<?php echo Yii::app()->request->baseUrl; ?>/html/icon/admin/apple-touch-icon-57x57.png" />
    <link rel="apple-touch-icon-precomposed" sizes="114x114" href="<?php echo Yii::app()->request->baseUrl; ?>/html/icon/admin/apple-touch-icon-114x114.png" />
    <link rel="apple-touch-icon-precomposed" sizes="72x72" href="<?php echo Yii::app()->request->baseUrl; ?>/html/icon/admin/apple-touch-icon-72x72.png" />
    <link rel="apple-touch-icon-precomposed" sizes="144x144" href="<?php echo Yii::app()->request->baseUrl; ?>/html/icon/admin/apple-touch-icon-144x144.png" />
    <link rel="apple-touch-icon-precomposed" sizes="60x60" href="<?php echo Yii::app()->request->baseUrl; ?>/html/icon/admin/apple-touch-icon-60x60.png" />
    <link rel="apple-touch-icon-precomposed" sizes="120x120" href="<?php echo Yii::app()->request->baseUrl; ?>/html/icon/admin/apple-touch-icon-120x120.png" />
    <link rel="apple-touch-icon-precomposed" sizes="76x76" href="<?php echo Yii::app()->request->baseUrl; ?>/html/icon/admin/apple-touch-icon-76x76.png" />
    <link rel="apple-touch-icon-precomposed" sizes="152x152" href="<?php echo Yii::app()->request->baseUrl; ?>/html/icon/admin/apple-touch-icon-152x152.png" />
    <link rel="icon" type="image/png" href="<?php echo Yii::app()->request->baseUrl; ?>/html/icon/admin/favicon-96x96.png" sizes="96x96" />
    <link rel="icon" type="image/png" href="<?php echo Yii::app()->request->baseUrl; ?>/html/icon/admin/favicon-32x32.png" sizes="32x32" />
    <link rel="icon" type="image/png" href="<?php echo Yii::app()->request->baseUrl; ?>/html/icon/admin/favicon-16x16.png" sizes="16x16" />
    <link rel="icon" type="image/png" href="<?php echo Yii::app()->request->baseUrl; ?>/html/icon/admin/favicon-128.png" sizes="128x128" />
    <meta name="application-name" content="Колесо Онлайн"/>
    <meta name="msapplication-TileColor" content="#FFFFFF" />
    <meta name="msapplication-TileImage" content="<?php echo Yii::app()->request->baseUrl; ?>/html/icon/admin/mstile-144x144.png" />
    <meta name="msapplication-square70x70logo" content="<?php echo Yii::app()->request->baseUrl; ?>/html/icon/admin/mstile-70x70.png" />

	<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/jquery-2.1.1.min.js"></script>
    <script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/jquery.qtip.min.js"></script>
    <script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/jquery-ui.min.js"></script>
    <script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/datepicker-ru.js"></script>
    <script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/plupload/plupload.full.js"></script>
    <script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/plupload/jquery.plupload.queue/jquery.plupload.queue.js"></script>
    <script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/tinymce/jquery.tinymce.min.js"></script>
    <script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/tinymce/tinymce.min.js"></script>
    <script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/KitProgress.js"></script>
    <script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/jquery.fancybox.js"></script>
    <script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/jquery.validate.min.js"></script>
    <script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/jquery.maskedinput.min.js"></script>
    <script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/numericInput.min.js"></script>
    <script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/TweenMax.min.js"></script>
    <script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/select2.min.js"></script>
    <script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/jquery.cookie.js"></script>
    <script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/form2js.js"></script>
    <script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/admin.js"></script>
    <? foreach ($this->scripts as $script): ?>
        <script type="text/javascript" src="<?=Yii::app()->request->baseUrl?>/js/<?=$script?>.js"></script>
    <? endforeach; ?>
</head>
<body>
    <div class="b-for-image-form"></div>
    <? if( Yii::app()->user->isGuest ): ?>
        <?php echo $content;?>
    <? else: ?>
        <div class="nav_container">
            <div class="b-place-state">
                <span class="<?=(($this->place_states["DROM"] == "on")?("b-green"):("b-red"))?>" data-id="2047">Дром</span>
                <span class="<?=(($this->place_states["AVITO"] == "on")?("b-green"):("b-red"))?>" data-id="2048">Авито</span>
                <p><?=Cron::model()->count();?></p>
            </div>
            <div class="who_am_i">
                <div class="b-user clearfix">
                    <div class="b-user-icon"></div>
                    <div class="b-user-info">
                        <h3><? echo Yii::app()->user->name; ?></h3>
                        <small><? echo $this->getUserRoleRus(); ?></small>
                    </div>
                </div>
                <div class="b-panel">
                    <div class="b-panel-icons">
                        <div class="b-panel-icons-wrap">
                            <div class="b-panel-icons-item b-panel-icons-home"><a title="На сайт" href="/"></a></div>
                            <div class="b-panel-icons-item b-panel-icons-logout"><a title="Выйти" href="<?php echo $this->createUrl('site/logout')?>"></a></div>
                       </div>
                    </div>
                </div>
            </div>
            <div class="b-modules-cont">
                <ul class="modules">
                    <?foreach ($this->adminMenu["items"] as $i => $menuItem):?>
                        <li data-name="<?=$menuItem->code?>"><a href="<?php echo $this->createUrl('/'.$menuItem->code.'/adminindex')?>"><?=$menuItem->name?></a></li>
                    <?endforeach;?>
                </ul>
                <? if(Yii::app()->params['debug']): ?>
                    <div class="b-debug"><?=$this->debugText?></div>
                <? endif; ?>
            </div>
        </div>
        <div class="main">
            <div class="b-find-advert-button ion-ios-search-strong" id="b-find-advert-button"></div>
            <div class="b-find-advert">
                <form action="<?php echo $this->createUrl('/advert/adminfindbyid')?>" method="GET" target="_blank">
                    <input type="text" name="find_advert_id" id="b-find-advert" />
                    <input type="submit" style="display:none;" />
                </form>
            </div>
            <div class="b-main-center">
                <?php echo $content;?>
                <br>
                <? 
                    list($queryCount, $queryTime) = Yii::app()->db->getStats();
                    echo "Кол-во запросов: $queryCount, Общее время запросов: ".sprintf('%0.5f',$queryTime)."s";
                ?>
            </div>
        </div>
    <? endif; ?>
</body>
</html>