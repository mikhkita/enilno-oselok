<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<title><?php echo CHtml::encode($this->pageTitle); ?></title>
	<meta name="keywords" content=''>
	<meta name="description" content=''>

	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="language" content="ru" />
	<meta name="viewport" content="width=1000">
	<link rel="icon" type="image/vnd.microsoft.icon" href="favicon.ico">
	
	<!-- blueprint CSS framework -->
	<link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/css/reset.css" type="text/css">
	<link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/css/jquery.fancybox.css" type="text/css">
	<link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/css/KitAnimate.css" type="text/css">
	<link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/css/slick.css" type="text/css">
	<link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/css/layout.css" type="text/css">
	<!--[if lt IE 8]>
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/ie.css" media="screen, projection" />
	<![endif]-->
	<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/jquery-2.1.1.min.js"></script>
	<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/jquery-ui.min.js"></script>
	<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/jquery.fancybox.js"></script>
	<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/css3-mediaqueries.js"></script>
	<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/jquery.maskedinput.min.js"></script>
	<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/jquery.validate.min.js"></script>
	<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/KitProgress.js"></script>
	<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/device.js"></script>
	<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/main.js"></script>
	<?php foreach ($this->scripts AS $script): ?><script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/<?php echo $script?>.js"></script><? endforeach; ?>
</head>
<body>
	<ul class="ps-lines">
		<li class="v" style="margin-left:-481px"></li>
		<li class="v" style="margin-left:480px"></li>
		<li class="v" ></li>
	</ul>
	<div class="b b-1">
		<div class="b-block clearfix">
			<div class="left">
				<ul class="b-menu">
					<? $menu = $this->getMenuCodes(); ?>
					<li class="b-menu-item"><a href="<?php echo Yii::app()->request->baseUrl; ?>/">Главная</a></li>
					<li class="b-menu-item"><a href="<?php echo Yii::app()->createUrl('/page/index',array('code'=>$menu[0]->pag_code))?>">О проекте</a></li>
					<li class="b-menu-item"><a href="#">Блог</a></li>
					<li class="b-menu-item"><a href="<?php echo Yii::app()->createUrl('/page/index',array('code'=>$menu[1]->pag_code))?>">Контакты</a></li>
				</ul>
			</div>
			<div class="left">
				<a href="#" class="b-search-icon"></a>
			</div>
			<div class="right">
				<a href="<? echo $this->createUrl(Yii::app()->params['defaultAdminRedirect']); ?>" class="b-btn">Вход</a>
				<a href="#" class="b-btn">Регистрация</a>
			</div>
		</div>
	</div>
	<div class="b b-2">
		<?php echo $content; ?>
	</div>
	<div class="b b-3">
		<div class="b-block">
			<div>
				<ul class="b-menu clearfix">
					<li class="b-menu-item"><a href="#">Главная</a></li>
					<li class="b-menu-item"><a href="#">О проекте</a></li>
					<li class="b-menu-item"><a href="#">Блог</a></li>
					<li class="b-menu-item"><a href="#">Контакты</a></li>
				</ul>
			</div>
			<ul class="b-soc clearfix">
				<li class="b-soc-item b-soc-fb"><a href="#"></a></li>
				<li class="b-soc-item b-soc-tw"><a href="#"></a></li>
				<li class="b-soc-item b-soc-dr"><a href="#"></a></li>
				<li class="b-soc-item b-soc-vm"><a href="#"></a></li>
				<li class="b-soc-item b-soc-gp"><a href="#"></a></li>
			</ul>
			<p class="b-copyright">© Youla 2014 – Все права защищены</p>
		</div>
	</div>
</body>
</html>