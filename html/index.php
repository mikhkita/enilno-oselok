<?

$useragent=$_SERVER['HTTP_USER_AGENT'];
$mobile = (preg_match('/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|mobile.+firefox|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows ce|xda|xiino/i',$useragent)||preg_match('/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i',substr($useragent,0,4)))

?>
<!DOCTYPE html>
<html>
<head>
	<title>Главная</title>
	<meta name="keywords" content=''>
	<meta name="description" content=''>

	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<meta name="viewport" content="width=device-width, user-scalable=no">
    <meta name="format-detection" content="telephone=no">

	<? if( $mobile ): ?>
	<meta name="viewport" content="width=750, user-scalable=no">
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
						<a class="left" href="#"><img src="i/logo.png"></a>
						<div class="right">
							<h1>Колесо<span>онлайн</span></h1>
							<h2>Вы находитесь в г. <a href="#">Томск</a></h2>
						</div>
					</div>
				</div>
				<div class="right">
					<div class="clearfix contacts">
						<a href="callto:+79993211122" class="left">+7 (999) 321-11-22</a>
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
			<div class="b-block clearfix">
				<a href="#" class="b-burger icon left"></a>
				<ul class="clearfix left">
					<li><a href="#">Диски</a></li>
					<li><a href="#">Шины</a></li>
					<li><a href="#">Колеса</a></li>
				</ul>
			</div>
		</div>
	</div>
	<div class="b-content">
	<div class="b b-main-slider">
		<div class="slide" style="background-image: url('i/back-main.jpg');">
			<div class="b-block">
				<h2>ШИРОКИЙ АССОРТИМЕНТ</h2>
				<p>Только в нашем магазине вы найдете именно те колеса, которые вам нужны.</p>
			</div>
		</div>
		<div class="slide" style="background-image: url('i/back-main.jpg');">
			<div class="b-block">
				<h2>ШИРОКИЙ АССОРТИМЕНТ</h2>
				<p>Только в нашем магазине вы найдете именно те колеса, которые вам нужны.</p>
			</div>
		</div>
	</div>
	<div class="b b-filters">
		<div class="b-block gradient-grey main-tabs">
			<ul class="tabs clearfix">
				<li class="gradient-lightBlack"><a href="#tabs-disc"><span class="disc-icon icon">Подбор дисков</span></a></li>
				<li class="gradient-lightBlack"><a href="#tabs-tire"><span class="tire-icon icon">Подбор шин</span></a></li>
				<li class="gradient-lightBlack"><a href="#tabs-wheel"><span class="wheel-icon icon">Подбор Колес</span></a></li>
			</ul>
			<div id="tabs-disc">
				<form action="#" method="GET">
					<div class="filter-cont">
						<div class="tire-type clearfix">	
							<input id="tire-winter" type="radio" name="tire-type" value="0">
							<label for="tire-winter">Зимние нешaafsипованные</label>
							<input id="tire-spike" type="radio" name="tire-type" value="1">
							<label for="tire-spike">Зимние шипованные</label>
							<input id="tire-summer" type="radio" name="tire-type" value="2">
							<label for="tire-summer">Летние</label>
						</div>
					</div>
					<div class="filter-cont clearfix">
						<div class="filter-item">
							<h5>Производитель</h5>
							<div class="input"></div>	
						</div>
						<div class="filter-item">
							<h5>Тип</h5>
							<div class="input"></div>	
						</div>
						<div class="filter-item">
							<h5>Состояние</h5>
							<div class="input"></div>	
						</div>
						<div class="filter-item">
							<h5>Посадочный диаметр</h5>
							<div class="input"></div>	
						</div>
					</div>
					<div class="filter-cont clearfix">
						<div class="filter-item">
							<h5>Сверловка</h5>
							<div class="input"></div>	
						</div>
						<div class="filter-item">
							<h5>Ширина диска</h5>
							<div class="input"></div>	
						</div>
						<div class="filter-item">
							<h5>Вылет</h5>
							<div class="input"></div>	
						</div>
					</div>
					<div class="filter-cont">
						<div class="slide-type clearfix">
							<div class="left">
							<h3>Ценовой диапазон от</h3>
							<input class="min-val price" type="text" maxlength="5" placeholder="Мин.">
							<span class="dash">до</span>
							<input class="max-val price" type="text" maxlength="5" placeholder="Макс.">
							</div>
							<div data-min="6500" data-max="9000" data-min-cur="6500" data-max-cur="9000" data-step="100" class="slider-range left"></div>
						</div>	
					</div>
					<div class="filter-butt-cont">
						<input type="submit" class="b-black-butt" value="Принять">
					</div>
				</form>
			</div>
			<div id="tabs-tire">
				<form action="#" method="GET">
					<div class="filter-cont">
						<div class="tire-type clearfix">	
							<input id="tire-winter" type="radio" name="tire-type" value="0">
							<label for="tire-winter">Зимние нешипованные</label>
							<input id="tire-spike" type="radio" name="tire-type" value="1">
							<label for="tire-spike">Зимние шипованные</label>
							<input id="tire-summer" type="radio" name="tire-type" value="2">
							<label for="tire-summer">Летние</label>
						</div>
					</div>
					<div class="filter-cont clearfix">
						<div class="filter-item">
						<!-- 	<select name="asd" id="" multiple="multiple">
								<option value="1">Achilles</option>
								<option value="2">Bridgestone</option>
								<option value="3">Achilles</option>
								<option value="4">Bridgestone</option>
								<option value="5">Achilles</option>
								<option value="6">Bridgestone</option>
								<option value="7">Achilles</option>
								<option value="8">Bridgestone</option>
								<option value="9">Achilles</option>
								<option value="0">Bridgestone</option>
								<option value="s">Achilles</option>
								<option value="g">Bridgestone</option>
								<option value="h">Achilles</option>
							</select> -->
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
							<h5>Состояние</h5>
							<div class="input"></div>	
						</div>
						<div class="filter-item">
							<h5>Посадочный диаметр</h5>
							<div class="input"></div>	
							<div class="variants">
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
					</div>
					<div class="filter-cont clearfix">
						<div class="filter-item">
							<h5>Сверловка</h5>
							<div class="input"></div>	
						</div>
						<div class="filter-item">
							<h5>Ширина диска</h5>
							<div class="input"></div>	
						</div>
						<div class="filter-item">
							<h5>Вылет</h5>
							<div class="input"></div>	
						</div>
					</div>
				</form>
			</div>
			<div id="tabs-wheel">
				<form action="#" method="GET">
					<div class="filter-cont">
						<div class="tire-type clearfix">	
							<input id="tire-winter" type="radio" name="tire-type" value="0">
							<label for="tire-winter">Зимние нешипованные</label>
							<input id="tire-spike" type="radio" name="tire-type" value="1">
							<label for="tire-spike">Зимние шипованные</label>
							<input id="tire-summer" type="radio" name="tire-type" value="2">
							<label for="tire-summer">Летние</label>
						</div>
					</div>
					<div class="filter-cont clearfix">
						<div class="filter-item">
							<h5>Производитель</h5>
							<div class="input"></div>	
						</div>
						<div class="filter-item">
							<h5>Тип</h5>
							<div class="input"></div>	
						</div>
						<div class="filter-item">
							<h5>Состояние</h5>
							<div class="input"></div>	
						</div>
						<div class="filter-item">
							<h5>Посадочный диаметр</h5>
							<div class="input"></div>	
						</div>
					</div>
					<div class="filter-cont clearfix">
						<div class="filter-item">
							<h5>Сверловка</h5>
							<div class="input"></div>	
						</div>
						<div class="filter-item">
							<h5>Ширина диска</h5>
							<div class="input"></div>	
						</div>
						<div class="filter-item">
							<h5>Вылет</h5>
							<div class="input"></div>	
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
	<div class="b b-popular">
		<div class="b-block clearfix">
			<div class="grey-block main-category left">
				<div class="gradient-grey">
					<h3>Категории товаров</h3>
					<ul>
						<li><a href="#"><span class="disc-icon icon">Диски</span></a></li>
						<li><a href="#"><span class="tire-icon icon">Шины</span></a></li>
						<li><a href="#"><span class="wheel-icon icon">Колеса</span></a></li>
					</ul>
				</div>
				<div class="gradient-grey">
					<h3>О нас</h3>
					<p>Этот магазин сделан специально для оптимального выбора шин, дисков и других аксессуаров для вашего автомобиля.
					<p>Удобство выбора и простота оформления покупки - вот два простых принципа, которые делают наш магазин лучшим.</p>
				</div>
			</div>
			<div class="popular-good right main-tabs">
				<h3 class="category-title">популярные товары</h3>
				<ul class="popular-category clearfix">
					<li><a href="#popular-disc"><span class="disc-icon icon">Диски</span></a></li>
					<li><a href="#popular-tire"><span class="tire-icon icon">Шины</span></a></li>
					<li><a href="#popular-wheel"><span class="wheel-icon icon">Колеса</span></a></li>
				</ul>
				<div id="popular-disc">
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
					</ul>
				</div>
				<div id="popular-tire">
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
					</ul>
				</div>
				<div id="popular-wheel">
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
					</ul>
				</div>
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
						<h3>О МАГАЗИНЕ</h3>
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
						<h3>КОНТАКТНАЯ ИНФОРМАЦИЯ</h3>
						<a class="footer-contacts mail" href="mailto:koleso@yandex.ru">koleso@yandex.ru</a>
						<a class="footer-contacts phone" href="callto:+79993211122">+7-(999)-321-11-22</a>
						<span class="footer-contacts map">г. Красноярск, ул. Вавилова, 1а</span>
						<div class="social">
							<h3>ПРИСОЕДИНЯЙТЕСЬ К НАМ</h3>
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
	<div class="b b-6">
		<div class="b-block">
			
		</div>
	</div>
	<div class="b b-7">
		<div class="b-block">
			
		</div>
	</div>
	<div class="b b-8">
		<div class="b-block">
			
		</div>
	</div>
	<div class="b b-9">
		<div class="b-block">
			
		</div>
	</div>
<div style="display:none;">
	<div id="b-popup-callback">
		<div class="for_all b-popup">
			<h3>Заказать звонок</h3>
			<h4>Или оставьте заявку и мы Вам перезвоним в ближайшее время:</h4>
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
<script type="text/javascript" src="js/KitProgress.js"></script>
<script type="text/javascript" src="js/KitAnimate.js"></script>
<script type="text/javascript" src="js/device.js"></script>
<script type="text/javascript" src="js/KitSend.js"></script>
<script type="text/javascript" src="js/main.js"></script>

</body>
</html>