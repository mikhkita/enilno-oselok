<div class="b-content">
	<div class="good-detail">
		<div class="b-block">
			<ul class="navigation clearfix">
				<li><a href="<?=Yii::app()->createUrl('/kolesoOnline')?>"></a></li>
				<!-- <li><a href="#">Каталог</a></li> -->
				<li><a href="<? echo Yii::app()->createUrl('/kolesoOnline/category',array('type' => $_GET['type']))?>"><?=($this->params[$_GET['type']]["NAME"]." ".Yii::app()->params["city"]->in)?></a></li>
				<li><a href="#"><?=$good_title?></a></li>
			</ul>
			<h1 class="category-title" id="buy-title"><?=$good_title?></h1>
			<div class="detail-wrap clearfix">
				<div class="detail-photo left">
					<div class="detail-slider-for">
						<?$i=0;?>
						<? foreach ($imgs as $img): ?>
							<div><a href="<?=$img?>" class="fancy-img big<?=(($i==0)?"":" after-load-back")?>" <?=(($i==0)?"":"data-")?>style="background-image:url('<?=$img?>');" rel="gallery0"></a></div>
							<?$i++;?>
						<? endforeach; ?>
					</div>
					<ul class="detail-thumb">
						<? if (count($imgs)>1): ?>
							<? foreach ($imgs as $img): ?>
								<li class="thumb after-load-back" data-style="background-image:url('<?=$img?>');"></li>
							<? endforeach; ?>
						<? endif; ?>
					</ul>
				</div>
				<div class="detail-price gradient-grey right">
					<div class="clearfix">
						<? $price = number_format(($good->fields_assoc[20])?$good->fields_assoc[20]->value:0, 0, ',', ' ' )." р."; $order = Interpreter::generate($this->params[$_GET['type']]["ORDER"], $good,$dynamic); ?>
						<? $price = ($good->archive)?"Продано":$price; ?>
						<h3><?=( !$good->fields_assoc[20]->value || $good->fields_assoc[20]->value == 0 )? Yii::app()->params["zeroPrice"] : $price ?><span> <?=Interpreter::generate($this->params[$_GET['type']]["SHIPPING"], $good,$dynamic);?></span></h3>
						<? $is_available = Interpreter::generate($this->params[$_GET['type']]["AVAILABLE"], $good,$dynamic); ?>
						<? if($is_available != "В наличии"): ?>
							<h4><?=$is_available?></h4>
						<? endif; ?>
					</div>
					<div class="clearfix">
						<div class="left">
							<a href="#" class="fancy b-orange-butt" data-block="#b-popup-buy" data-aftershow="detail_buy">Купить</a>
						</div>
						<div class="left">
							<h5 class="b-go" data-block="#shipping">Доставка и оплата</h5>
							<? if( $is_available == "В наличии" ): ?>
								<h6><p>Товар: </p><span class="stock">В&nbsp;наличии</span></h6>
							<? else: ?>
								<h6><p>Товар: </p><span class="deliv">Под&nbsp;заказ</span></h6>
							<? endif; ?>
						</div>
					</div>
				</div>
				<div class="detail-desc left">
					<h3>Характеристики</h3>
					<ul>
						<? foreach ($params[$_GET['type']]["CATEGORY"] as $key => $attr): ?>
						<? if( isset($attr["TYPE"]) && $attr["TYPE"] == "INTER" ): ?>
							<li><?=$attr['LABEL']?>:<span><?=Interpreter::generate($attr['ID'], $good, $dynamic).$attr['UNIT']?></span></li>
						<? else: ?>
							<? if(isset($good->fields_assoc[$attr['ID']])): ?>
								<li><?=$attr['LABEL']?>:<span><?=$good->fields_assoc[$attr['ID']]->value.$attr['UNIT']?></span></li>
							<? endif; ?>
						<? endif; ?>
						<? endforeach; ?>
					</ul>
				</div>
			</div>
			<div class="main-tabs" id="shipping">
				<ul class="desc-tabs clearfix">
					<li class="active gradient-grey"><a href="#tabs-desc">Описание</a><span></span></li>
					<li class="gradient-grey"><a href="#tabs-shippping">Доставка</a><span></span></li>
					<li class="gradient-grey"><a href="#tabs-warranty">Гарантия</a><span></span></li>
				</ul>
				<div id="tabs-desc"><?=$this->replaceToBr(Interpreter::generate($this->params[$_GET['type']]["DESCRIPTION_CODE"], $good,$dynamic));?></div>
				<div id="tabs-shippping"><?=$this->replaceToBr(Interpreter::generate($this->params[$_GET['type']]["ORDER"], $good,$dynamic));?></div>
				<div id="tabs-warranty"><?=$this->replaceToBr(Interpreter::generate($this->params[$_GET['type']]["GARANTY_CODE"], $good,$dynamic));?></div>
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