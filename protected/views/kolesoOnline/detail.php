<?php $this->renderPartial('_header', array('cities' => $cities)); ?>
<div class="b-content">
	<div class="good-detail">
		<div class="b-block">
			<ul class="navigation clearfix">
				<li><a href="<?=Yii::app()->createUrl('/kolesoonline')?>"></a></li>
				<!-- <li><a href="#">Каталог</a></li> -->
				<li><a id="go-back" href="#"><?=$this->params[$_GET['type']]["NAME"]?></a></li>
				<li><a href="#"><?=$this->title?></a></li>
			</ul>
			<h3 class="category-title" id="buy-title"><?=$this->title?></h3>
			<div class="detail-wrap clearfix">
				<div class="detail-photo left">
					<div class="detail-slider-for">
						<? foreach ($imgs as $img): ?>
							<div><a href="<?=$img?>" class="fancy-img big" style="background-image:url('<?=$img?>');" rel="gallery0"></a></div>
						<? endforeach; ?>
					</div>
					<ul class="detail-thumb">
						<? if (count($imgs)>1): ?>
							<? foreach ($imgs as $img): ?>
								<li class="thumb" style="background-image:url('<?=$img?>');"></li>
							<? endforeach; ?>
						<? endif; ?>
					</ul>
				</div>
				<div class="detail-price gradient-grey right">
					<? $price = 0; $price = ($good->fields_assoc[51])?$good->fields_assoc[51]->value:0; $order = Interpreter::generate($this->params[$_GET['type']]["ORDER"], $good); ?>
					<h3><?=(!$price )? Yii::app()->params["zeroPrice"] : number_format( $price, 0, ',', ' ' )." р."?><span>+ 800 р.</span></h3>
					<h4>доставка в г. Томск</h4>
					<a href="#" class="fancy b-orange-butt" data-block="#b-popup-buy" data-aftershow="detail_buy">Купить</a>
					<h5>Доставка и оплата</h5>
					<h6>Товар: <span class="stock">В наличии</span></h6>
				</div>
				<div class="detail-desc left">
					<h3>Характеристики</h3>
					<ul>
						<? foreach ($params[$_GET['type']]["CATEGORY"] as $key => $attr): ?>
							<? if(isset($good->fields_assoc[$attr['ID']]->value)): ?>
								<li><?=$attr['LABEL']?>:<span><?=$good->fields_assoc[$attr['ID']]->value." ".$attr['UNIT']?></span></li>
							<? endif; ?>
						<? endforeach; ?>
					</ul>
				</div>
			</div>
			<div class="main-tabs">
				<ul class="desc-tabs clearfix">
					<li class="active gradient-grey"><a href="#tabs-desc">Описание</a><span></span></li>
					<li class="gradient-grey"><a href="#tabs-shippping">Доставка</a><span></span></li>
					<li class="gradient-grey"><a href="#tabs-warranty">Гарантия</a><span></span></li>
				</ul>
				<div id="tabs-desc"><? if(isset($good->fields_assoc[35]->value)) echo $good->fields_assoc[35]->value.'<br>'; ?><?=$this->replaceToBr($this->description);?></div>
				<div id="tabs-shippping">
					2Является первой зимней автошиной в которой применяется технология 3D-BIS на всей площади протектора. Данная технология, позволяет увеличить количество используемых ламелей. Для зимней авторезины высокая плотность ламелей на протекторе создает дополнительное сцепление на льду и снегу, но для движения на мокрой и сухой дороге для эффективного торможения, блоки должны быть жесткими и твердыми.
				</div>
				<div id="tabs-warranty"><?=$this->replaceToBr(Interpreter::generate($this->params[$_GET['type']]["GARANTY_CODE"], $good));?></div>
			</div>
			<!-- <h3 class="category-title similar">Похожие товары</h3>
			<div class="goods clearfix" id="similar-slider">
				<div class="gradient-grey">
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
				</div>
				<div class="gradient-grey">
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
				</div>
				<div class="gradient-grey">
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
				</div>
				<div class="gradient-grey">
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
				</div>
				<div class="gradient-grey">
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
				</div>
				<div class="gradient-grey">
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
				</div>
				<div class="gradient-grey">
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
				</div>
			</div> -->
		</div>
	</div>
</div>