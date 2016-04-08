<div class="b-content">
	<div class="good-detail">
		<div class="b-block">
			<ul class="navigation clearfix">
				<li><a href="<?=Yii::app()->createUrl('/kolesoOnline')?>"></a></li>
				<!-- <li><a href="#">Каталог</a></li> -->
				<li><a href="<? echo Yii::app()->createUrl('/kolesoOnline/category',array('type' => $_GET['type']))?>"><?=($this->params[$_GET['type']]["NAME"])?></a></li>
				<li><a href="#"><?=$good_title?></a></li>
			</ul>
			<h1 class="category-title" id="buy-title"><?=$good_title?></h1>
			<div class="detail-wrap clearfix">
				<div class="detail-photo left">
					<div class="detail-slider-for">
						<?$i=0;?>
						<? foreach ($imgs as $img): ?>
							<div><a href="<?=$img["original"]?>" <?if(strpos($img["original"], "default")):?>onclick="return false;"<?endif;?> class="<?if(!strpos($img["original"], "default")):?>fancy-img <?endif;?>big<?=(($i==0)?"":" after-load-back")?>" <?=(($i==0)?"":"data-")?>style="background-image:url('<?=$img["big"]?>');" rel="gallery0"></a></div>
							<?$i++;?>
						<? endforeach; ?>
					</div>
					<ul class="detail-thumb">
						<? if (count($imgs)>1): ?>
							<? foreach ($imgs as $img): ?>
								<li class="thumb" style="background-image:url('<?=$img["small"]?>');"></li>
							<? endforeach; ?>
						<? endif; ?>
					</ul>
				</div>
				<div class="detail-price gradient-grey right">
					<div class="clearfix">
						<? $price = Interpreter::generate($this->params[$_GET['type']]["PRICE_CODE"], $good, $dynamic);?>
						<? $price = number_format($price, 0, ',', ' ' )." р."; $order = Interpreter::generate($this->params[$_GET['type']]["ORDER"], $good,$dynamic); ?>
						<? $price = ($good->archive)?(($this->user)?("Продано за ".$price):"Продано"):$price; ?>
						<h3><?=$price=( !$good->fields_assoc[20]->value || $good->fields_assoc[20]->value == 0 )? Yii::app()->params["zeroPrice"] : $price ?><?if(!$good->archive):?><?$delivery = Interpreter::generate($this->params[$_GET['type']]["SHIPPING"], $good,$dynamic);?><span <?if($delivery=="бесплатная"):?>class="b-free-delivery"<?endif;?>> <?=$delivery?></span><?endif;?></h3>
						<? $is_available = Interpreter::generate($this->params[$_GET['type']]["AVAILABLE"], $good, $dynamic); ?>
						<? if(!$good->archive): ?>
							<? if($is_available != "В наличии"): ?>
								<h4 <?if($delivery=="бесплатная"):?>class="b-free-delivery"<?endif;?>><?=$is_available?></h4>
							<? endif; ?>
						<? endif; ?>
					</div>
					<div class="clearfix">
						<div class="left">
						<a href="#" class="fancy b-orange-butt" data-block="#b-popup-buy" data-aftershow="category_buy">Купить</a>
							<!-- <? if(isset($_SESSION["BASKET"]) && array_search($good->id, $_SESSION["BASKET"]) !== false): ?>
								<a href="<?=Yii::app()->createUrl('/kolesoOnline/basket',array('id' => $good->id,'add' => true))?>" class="b-orange-butt carted">добавлено</a>
							<? elseif(mb_strpos($price,"Продано",0,"UTF-8") === false && mb_strpos($price,Yii::app()->params["zeroPrice"],0,"UTF-8") === false): ?>
		                        <a href="<?=Yii::app()->createUrl('/kolesoOnline/basket',array('id' => $good->id,'add' => true))?>" class="b-orange-butt to-cart">в корзину</a>
		                    <? elseif(mb_strpos($price,Yii::app()->params["zeroPrice"],0,"UTF-8") !== false): ?> -->
		                    	<!-- <a href="#" class="fancy b-orange-butt" data-block="#b-popup-buy" data-aftershow="category_buy">Купить</a> -->
		                   <!--  <? else:?>
		                    	<a href="#" class="b-orange-butt carted">Продано</a>
		                    <? endif; ?> -->
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
							<? if(Interpreter::generate($attr['ID'], $good, $dynamic)): ?>
								<li><?=$attr['LABEL']?>: <span><? echo Interpreter::generate($attr['ID'], $good, $dynamic); if(Interpreter::generate($attr['ID'], $good, $dynamic) != "Новая резина") echo $attr['UNIT']?></span></li>
							<? endif; ?>
						<? else: ?>
							<? if(isset($good->fields_assoc[$attr['ID']])): ?>
								<li><?=$attr['LABEL']?>:<span><?=$good->fields_assoc[$attr['ID']]->value.$attr['UNIT']?></span></li>
							<? endif; ?>
						<? endif; ?>
						<? endforeach; ?>

						<? if($partner): ?>
							<li>Партнерское объявление:<span><a href="<?=$partner['link']?>" target="_blank"><?=$partner['label']?></a></span></li>
						<? endif; ?>
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
			<? if(count($similar)): ?>
                <h3 class="category-title similar">Похожие товары</h3>
                <ul class="goods clearfix" id="similar-slider">  
                    <?php $this->renderPartial('_list', array('goods' => $similar,'last' => 0,'params' => $params,'type' => $_GET['type'],'dynamic'=>$dynamic)); ?>
                </ul>
        	<? endif; ?>
		</div>
	</div>
</div>