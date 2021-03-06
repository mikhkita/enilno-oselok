<!DOCTYPE html>
<html>
<head>
	<title>Категории</title>
	<meta name="keywords" content=''>
	<meta name="description" content=''>

	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="format-detection" content="telephone=no">

	<? if( $mobile ): ?>
		<meta name="viewport" content="width=750, user-scalable=no">
	<? else: ?>
		<meta name="viewport" content="width=device-width, user-scalable=no">
	<? endif; ?>

	<link rel="stylesheet" href="css/reset.css" type="text/css">
	<link rel="stylesheet" href="css/jquery.fancybox.css" type="text/css">
	<link rel="stylesheet" href="css/slick.css" type="text/css">
	<link rel="stylesheet" href="css/jquery-ui.css" type="text/css">
	<link rel="stylesheet" href="css/KitAnimate.css" type="text/css">
	<link rel="stylesheet" href="css/layout.css" type="text/css">
	
	<link rel="stylesheet" media="screen and (min-width: 240px) and (max-width: 767px)" href="css/layout-mobile.css" />
	<link rel="stylesheet" media="screen and (min-width: 768px) and (max-width: 1000px)" href="css/layout-tablet.css" />

	<link rel="icon" type="image/vnd.microsoft.icon" href="favicon.ico">
	
	<!--[if gte IE 9]>
		<style type="text/css">
		    .gradient-orange,
		    .gradient-black,
		    .gradient-grey,
		    .gradient-lightBlack,
		    .b-orange-butt,.b-black-butt,
		    .b-filters .b-block .tabs li:hover a,
		    .b-filters .b-block .tabs li.ui-tabs-active a{
		       filter: none;
		    }
	    </style>
	<![endif]-->
	
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
				<ul class="clearfix">
					<li><a href="#">О магазине</a></li>
					<li><a href="#">Доставка и оплата</a></li>
					<li><a href="#">Гарантия</a></li>
					<li><a href="#">Контакты</a></li>
				</ul>
			</div>  
		</div>
		<div class="b b-header-main">
			<div class="b-block clearfix">
				<span class="stamp"></span>
				<div class="left">
					<div class="clearfix">
						<a class="left" href="#"><img src="i/logo-2.png"></a>
						<div class="right">
							<h1>Колесо<span>онлайн</span></h1>
							<h2>Вы находитесь в г. <a href="#">Томск</a></h2>
						</div>
					</div>
				</div>
				<div class="right">
					<div class="clearfix contacts">
						<a href="#" class="left">+7 (999) 321-11-22</a>
						<a href="mailto:koleso@yandex.ru" class="left mail">koleso@yandex.ru</a>
					</div>
					<form action="#" method="GET">
						<input type="text" name="search" placeholder="Поиск">
						<button class="b-orange-butt">Поиск</button>
					</form>
				</div>
				
			</div>
		</div>
		<div class="b b-sub-menu gradient-orange">
			<div class="b-block">
				<ul class="clearfix">
					<li><a href="#">Шины</a></li>
					<li><a href="#">Диски</a></li>
					<li><a href="#">Колеса</a></li>
				</ul>
			</div>
		</div>
	</div>
	<div class="b-content">
		<div class="b-category">
			<div class="b-block clearfix">
				<div class="grey-block left">
					<div class="gradient-grey">
						<h3>Фильтры</h3>
						<div class="filter-block">
							<form action="#">
							<!-- <h5>Сезонность</h5> -->
							<div class="tire-type clearfix">	
								<input id="tire-winter" type="radio" name="tire-type" value="0">
								<label for="tire-winter">Зимние нешипованные</label>
								<input id="tire-spike" type="radio" name="tire-type" value="1">
								<label for="tire-spike">Зимние шипованные</label>
								<input id="tire-summer" type="radio" name="tire-type" value="2">
								<label for="tire-summer">Летние</label>
							</div>
							<div class="filter-item">
								<h5>Производитель</h5>
								<div class="input"></div>	
								<div class="variants clearfix">
									<div>
										<label>
											<input type="checkbox">
											<span onselectstart="return false;">Achilles</span>
										</label>
										<label>
											<input type="checkbox">
											<span onselectstart="return false;">Bridgestone</span>
										</label>
										<label>
											<input type="checkbox">
											<span onselectstart="return false;">Bridgestone</span>
										</label>
										<label>
											<input type="checkbox">
											<span onselectstart="return false;">Bridgestone</span>
										</label>
										<label>
											<input type="checkbox">
											<span onselectstart="return false;">Bridgestone</span>
										</label>
										<label>
											<input type="checkbox">
											<span onselectstart="return false;">Bridgestone</span>
										</label>
										<label>
											<input type="checkbox">
											<span onselectstart="return false;">Bridgestone</span>
										</label>
										<label>
											<input type="checkbox">
											<span onselectstart="return false;">Bridgestone</span>
										</label>
										<label>
											<input type="checkbox">
											<span onselectstart="return false;">Bridgestone</span>
										</label>
										<label>
											<input type="checkbox">
											<span onselectstart="return false;">Bridgestone</span>
										</label>
									</div>
									<div>
										<label>
											<input type="checkbox">
											<span onselectstart="return false;">Achilles</span>
										</label>
										<label>
											<input type="checkbox">
											<span onselectstart="return false;">Bridgestone</span>
										</label>
										<label>
											<input type="checkbox">
											<span onselectstart="return false;">Bridgestone</span>
										</label>
										<label>
											<input type="checkbox">
											<span onselectstart="return false;">Bridgestone</span>
										</label>
										<label>
											<input type="checkbox">
											<span onselectstart="return false;">Bridgestone</span>
										</label>
										<label>
											<input type="checkbox">
											<span onselectstart="return false;">Bridgestone</span>
										</label>
										<label>
											<input type="checkbox">
											<span onselectstart="return false;">Bridgestone</span>
										</label>
										<label>
											<input type="checkbox">
											<span onselectstart="return false;">Bridgestone</span>
										</label>
										<label>
											<input type="checkbox">
											<span onselectstart="return false;">Bridgestone</span>
										</label>
										<label>
											<input type="checkbox">
											<span onselectstart="return false;">Bridgestone</span>
										</label>
									</div>
								</div>
							</div>
							<div class="filter-item">
								<h5>Тип</h5>
								<div class="input"></div>	
							</div>
							<div class="filter-item">
								<h5>Тип</h5>
								<div class="input"></div>	
							</div>
							<h5>Ценовой диапазон</h5>
							<div class="slide-type">
								<input class="min-val price" type="text" maxlength="5" placeholder="Мин.">
								<span class="dash">-</span>
								<input class="max-val price" type="text" maxlength="5" placeholder="Макс.">
								<div data-min="6500" data-max="9000" data-min-cur="6500" data-max-cur="9000" data-step="100" class="slider-range"></div>
							</div>	
							<div class="filter-butt-cont">
								<input type="submit" class="b-black-butt" value="Принять">
							</div>
							</form>						
						</div>
					</div>
				</div>
				<div class="right good-list">
					<ul class="navigation clearfix">
						<li><a href="#"></a></li>
						<li><a href="#">Каталог</a></li>
						<li><a href="#">Шины</a></li>
					</ul>
					<h3 class="category-title">раздел шины</h3>
					<div class="b-sort clearfix">
						<div class="left clearfix">
							<h4 class="left">Сортировать по:</h4>
							<ul class="left clearfix">
								<li class="active">Цене</li>
								<li>Диаметру</li>
								<li>Ширине</li>
								<li>Профилю</li>
							</ul>
						</div>
						<div class="right clearfix">
							<h4 class="left">Вид:</h4>
							<span class="active grid-view view"></span>
							<span class="list-view view"></span>
						</div>
					</div>
					<ul class="goods clearfix">
						<li class="gradient-grey">
							<div class="good-img" style="background-image: url('i/tire.jpg');"></div>
							<div class="params-cont">
								<h4>Yokohama DNA</h4>
								<h5><span>8900 р.</span> + 800 р.</h5>
								<h5>доставка в г. Томск</h5>
								<h6>225/45/17  2 шт.</h6>
								<h3>Износ: <span>82%</span></h3>
								<h3>Год выпуска: <span>2013</span></h3>
								<a href="#" class="b-orange-butt">Купить</a>
							</div>
						</li>
						<li class="gradient-grey">
							<div class="good-img" style="background-image: url('i/tire.jpg');"></div>
							<div class="params-cont">
								<h4>Yokohama DNA</h4>
								<h5><span>8900 р.</span> + 800 р.</h5>
								<h5>доставка в г. Томск</h5>
								<h6>225/45/17  2 шт.</h6>
								<h3>Износ: <span>82%</span></h3>
								<h3>Год выпуска: <span>2013</span></h3>
								<a href="#" class="b-orange-butt">Купить</a>
							</div>
						</li>
						<li class="gradient-grey">
							<div class="good-img" style="background-image: url('i/tire.jpg');"></div>
							<div class="params-cont">
								<h4>Yokohama DNA</h4>
								<h5><span>8900 р.</span> + 800 р.</h5>
								<h5>доставка в г. Томск</h5>
								<h6>225/45/17  2 шт.</h6>
								<h3>Износ: <span>82%</span></h3>
								<h3>Год выпуска: <span>2013</span></h3>
								<a href="#" class="b-orange-butt">Купить</a>
							</div>
						</li>
						<li class="gradient-grey">
							<div class="good-img" style="background-image: url('i/tire.jpg');"></div>
							<div class="params-cont">
								<h4>Yokohama DNA</h4>
								<h5><span>8900 р.</span> + 800 р.</h5>
								<h5>доставка в г. Томск</h5>
								<h6>225/45/17  2 шт.</h6>
								<h3>Износ: <span>82%</span></h3>
								<h3>Год выпуска: <span>2013</span></h3>
								<a href="#" class="b-orange-butt">Купить</a>
							</div>
						</li>
						<li class="gradient-grey">
							<div class="good-img" style="background-image: url('i/tire.jpg');"></div>
							<div class="params-cont">
								<h4>Yokohama DNA</h4>
								<h5><span>8900 р.</span> + 800 р.</h5>
								<h5>доставка в г. Томск</h5>
								<h6>225/45/17  2 шт.</h6>
								<h3>Износ: <span>82%</span></h3>
								<h3>Год выпуска: <span>2013</span></h3>
								<a href="#" class="b-orange-butt">Купить</a>
							</div>
						</li>
						<li class="gradient-grey">
							<div class="good-img" style="background-image: url('i/tire.jpg');"></div>
							<div class="params-cont">
								<h4>Yokohama DNA</h4>
								<h5><span>8900 р.</span> + 800 р.</h5>
								<h5>доставка в г. Томск</h5>
								<h6>225/45/17  2 шт.</h6>
								<h3>Износ: <span>82%</span></h3>
								<h3>Год выпуска: <span>2013</span></h3>
								<a href="#" class="b-orange-butt">Купить</a>
							</div>
						</li>
						<li class="gradient-grey">
							<div class="good-img" style="background-image: url('i/tire.jpg');"></div>
							<div class="params-cont">
								<h4>Yokohama DNA</h4>
								<h5><span>8900 р.</span> + 800 р.</h5>
								<h5>доставка в г. Томск</h5>
								<h6>225/45/17  2 шт.</h6>
								<h3>Износ: <span>82%</span></h3>
								<h3>Год выпуска: <span>2013</span></h3>
								<a href="#" class="b-orange-butt">Купить</a>
							</div>
						</li>
						<li class="gradient-grey">
							<div class="good-img" style="background-image: url('i/tire.jpg');"></div>
							<div class="params-cont">
								<h4>Yokohama DNA</h4>
								<h5><span>8900 р.</span> + 800 р.</h5>
								<h5>доставка в г. Томск</h5>
								<h6>225/45/17  2 шт.</h6>
								<h3>Износ: <span>82%</span></h3>
								<h3>Год выпуска: <span>2013</span></h3>
								<a href="#" class="b-orange-butt">Купить</a>
							</div>
						</li>
						<li class="gradient-grey">
							<div class="good-img" style="background-image: url('i/tire.jpg');"></div>
							<div class="params-cont">
								<h4>Yokohama DNA</h4>
								<h5><span>8900 р.</span> + 800 р.</h5>
								<h5>доставка в г. Томск</h5>
								<h6>225/45/17  2 шт.</h6>
								<h3>Износ: <span>82%</span></h3>
								<h3>Год выпуска: <span>2013</span></h3>
								<a href="#" class="b-orange-butt">Купить</a>
							</div>
						</li>
						<li class="gradient-grey">
							<div class="good-img" style="background-image: url('i/tire.jpg');"></div>
							<div class="params-cont">
								<h4>Yokohama DNA</h4>
								<h5><span>8900 р.</span> + 800 р.</h5>
								<h5>доставка в г. Томск</h5>
								<h6>225/45/17  2 шт.</h6>
								<h3>Износ: <span>82%</span></h3>
								<h3>Год выпуска: <span>2013</span></h3>
								<a href="#" class="b-orange-butt">Купить</a>
							</div>
						</li>
						<li class="gradient-grey">
							<div class="good-img" style="background-image: url('i/tire.jpg');"></div>
							<div class="params-cont">
								<h4>Yokohama DNA</h4>
								<h5><span>8900 р.</span> + 800 р.</h5>
								<h5>доставка в г. Томск</h5>
								<h6>225/45/17  2 шт.</h6>
								<h3>Износ: <span>82%</span></h3>
								<h3>Год выпуска: <span>2013</span></h3>
								<a href="#" class="b-orange-butt">Купить</a>
							</div>
						</li>
						<li class="gradient-grey">
							<div class="good-img" style="background-image: url('i/tire.jpg');"></div>
							<div class="params-cont">
								<h4>Yokohama DNA</h4>
								<h5><span>8900 р.</span> + 800 р.</h5>
								<h5>доставка в г. Томск</h5>
								<h6>225/45/17  2 шт.</h6>
								<h3>Износ: <span>82%</span></h3>
								<h3>Год выпуска: <span>2013</span></h3>
								<a href="#" class="b-orange-butt">Купить</a>
							</div>
						</li>
						<li class="gradient-grey">
							<div class="good-img" style="background-image: url('i/tire.jpg');"></div>
							<div class="params-cont">
								<h4>Yokohama DNA</h4>
								<h5><span>8900 р.</span> + 800 р.</h5>
								<h5>доставка в г. Томск</h5>
								<h6>225/45/17  2 шт.</h6>
								<h3>Износ: <span>82%</span></h3>
								<h3>Год выпуска: <span>2013</span></h3>
								<a href="#" class="b-orange-butt">Купить</a>
							</div>
						</li>
						<li class="gradient-grey">
							<div class="good-img" style="background-image: url('i/tire.jpg');"></div>
							<div class="params-cont">
								<h4>Yokohama DNA</h4>
								<h5><span>8900 р.</span> + 800 р.</h5>
								<h5>доставка в г. Томск</h5>
								<h6>225/45/17  2 шт.</h6>
								<h3>Износ: <span>82%</span></h3>
								<h3>Год выпуска: <span>2013</span></h3>
								<a href="#" class="b-orange-butt">Купить</a>
							</div>
						</li>
						<li class="gradient-grey">
							<div class="good-img" style="background-image: url('i/tire.jpg');"></div>
							<div class="params-cont">
								<h4>Yokohama DNA</h4>
								<h5><span>8900 р.</span> + 800 р.</h5>
								<h5>доставка в г. Томск</h5>
								<h6>225/45/17  2 шт.</h6>
								<h3>Износ: <span>82%</span></h3>
								<h3>Год выпуска: <span>2013</span></h3>
								<a href="#" class="b-orange-butt">Купить</a>
							</div>
						</li>
						<li class="gradient-grey">
							<div class="good-img" style="background-image: url('i/tire.jpg');"></div>
							<div class="params-cont">
								<h4>Yokohama DNA</h4>
								<h5><span>8900 р.</span> + 800 р.</h5>
								<h5>доставка в г. Томск</h5>
								<h6>225/45/17  2 шт.</h6>
								<h3>Износ: <span>82%</span></h3>
								<h3>Год выпуска: <span>2013</span></h3>
								<a href="#" class="b-orange-butt">Купить</a>
							</div>
						</li>
					</ul>
				</div>
			</div>
		</div>
	</div>
	<div class="b b-footer">
		<div class="b-footer-main">
			<div class="b-block">
				<span class="stamp stamp-left"></span>
				<span class="stamp stamp-right"></span>
				<ul class="sections clearfix">
					<li>
						<h3>О магазине</h3>
						<a href="#" class="footer-logo clearfix">
							<img class="left" src="i/logo.png">
							<div class="left">
								<h4><span>колесо</span> онлайн</h4>
							</div>
						</a>
						<p>Этот магазин сделан специально для оптимального выбора шин, дисков и других аксессуаров для вашего автомобиля.</p>
						<p>Удобство выбора и простота оформления покупки - вот это очень хорошо.</p>
					</li>
					<li>
						<h3>Разделы</h3>
						<ul>
							<li><a href="#">Шины</a></li>
							<li><a href="#">Диски</a></li>
							<li><a href="#">Колеса</a></li>
							<li><a href="#">Контакты</a></li>
							<li><a href="#">Оплата и доставка</a></li>
							<li><a href="#">Гарантия</a></li>
						</ul>
					</li>
					<li>
						<h3>Контактная информация</h3>
						<a class="footer-contacts mail" href="#">koleso@yandex.ru</a>
						<a class="footer-contacts phone" href="#">+7-(999)-321-11-22</a>
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
				<h3 class="right">Разработано в <a href="#">True Media</a></h3>
			</div>
		</div>
	</div>
<div style="display:none;">
	<div id="b-popup-1">
		<div class="for_all b-popup" >
			<h3>Оставьте заявку</h3>
			<h4>и наши специалисты<br>свяжутся с Вами в ближайшее время</h4>
			<form action="kitsend.php" method="POST" id="b-form-1" data-block="#b-popup-1">
				<div class="b-popup-form">
					<label for="name">Введите Ваше имя</label>
					<input type="text" id="name" name="name" required/>
					<label for="tel">Введите Ваш номер телефона</label>
					<input type="text" id="tel" name="phone" required/>
					<input type="hidden" name="subject" value="Заказ"/>
					<input type="submit" class="ajax b-orange-butt" value="Заказать">
				</div>
			</form>
		</div>
	</div>
	<div id="b-popup-2">
		<div class="b-thanks b-popup">
			<h3>Спасибо!</h3>
			<h4>Ваша заявка успешно отправлена.<br/>Наш менеджер свяжется с Вами в течение часа.</h4>
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

<script type="text/javascript" src="js/jquery-2.1.1.min.js"></script>
<script type="text/javascript" src="js/jquery.fancybox.js"></script>
<script type="text/javascript" src="js/TweenMax.min.js"></script>
<script type="text/javascript" src="js/swipe.js"></script>
<script type="text/javascript" src="js/css3-mediaqueries.js"></script>
<script type="text/javascript" src="js/jquery.maskedinput.min.js"></script>
<script type="text/javascript" src="js/jquery.validate.min.js"></script>
<script type="text/javascript" src="js/slick.min.js"></script>
<script type="text/javascript" src="js/jquery-ui.min.js"></script>
<script type="text/javascript" src="js/jquery.ui.touch-punch.js"></script>
<script type="text/javascript" src="js/KitProgress.js"></script>
<script type="text/javascript" src="js/KitAnimate.js"></script>
<script type="text/javascript" src="js/device.js"></script>
<script type="text/javascript" src="js/KitSend.js"></script>
<script type="text/javascript" src="js/main.js"></script>

</body>
</html>