<div class="b-content">
	<div class="b-cart">
		<div class="b-block">
			<ul class="navigation clearfix">
				<li><a href="<?=Yii::app()->createUrl('/kolesoOnline')?>"></a></li>
				<li><a href="#">Корзина</a></li>
			</ul>
			<h1 class="category-title">Корзина</h1>
			<div class="order-cont">
				<? if(count($goods)): $empty = false; $delivery = false;?>
					<table class="gradient-grey">
					<tbody>
					<!-- 	<tr>
							<th>Наименование</th>
							<th>Цена</th>
							<th></th>
						</tr> -->
					<? foreach ($goods as $good): $type = $good->good_type_id; $href = Yii::app()->createUrl('/kolesoOnline/detail',array('id' => ($good->code)?$good->code:$good->fields_assoc[3]->value,'type' => $type)); ?>
						<tr>
							<td class="clearfix title">
								<a target="_blank" href="<?=$href?>" class="img left" style="background-image: url(<? $images = $good->getImages(1, array("small"), NULL, NULL, true); echo $images[0]["small"];?>);" href="<?=$href?>"></a>
								<? 
					                if($type == 1) $title = $good->fields_assoc[16]->value." ".$good->fields_assoc[17]->value;
					                if($type == 2) $title = $good->fields_assoc[6]->value;
					                if($type == 3) $title = Interpreter::generate($this->params[$type]["TITLE_CATEGORY"], $good,$dynamic);
					            ?>
					            <div class="desc left">
									<a href="<?=$href?>" target="_blank"><?=$title?></a>
									<h4><?=Interpreter::generate($this->params[$type]["TITLE_2_CODE"], $good,$dynamic);?> <?if($type == 1) echo $good->fields_assoc[$this->params[$type]["CATEGORY"]["AMOUNT"]['ID']]->value." ".$this->params[$type]["CATEGORY"]["AMOUNT"]['UNIT']?></h4>
								</div>
							</td>
							<td class="price"><? $price = Interpreter::generate($this->params[$type]["PRICE_CODE"], $good, $dynamic); $data_price = $price; ?>
							<? $price = number_format($price, 0, ',', ' ' )." р."; ?>
							<? $price = ($good->archive)?(($this->user)?("Продано за ".$price):"Продано"):$price; ?>
							<h3 data-price="<?=$data_price?>" class="cart-price"><?=$price=( !$good->fields_assoc[20]->value || $good->fields_assoc[20]->value == 0 )? Yii::app()->params["zeroPrice"] : $price ?></h3>
							<? $is_available = Interpreter::generate($this->params[$type]["AVAILABLE"], $good, $dynamic); ?>
							<? if(!$good->archive): ?>
								<? if($is_available != "В наличии"): ?>
									<? $delivery = true; if(!$good->archive):?><?$delivery = Interpreter::generate($this->params[$type]["SHIPPING"], $good,$dynamic);?>
									<h4 <?if($delivery=="бесплатная"): $delivery = "+ доставка бесплатно"?>class="b-free-delivery"<?endif;?>> <span> <?=$delivery?></span><?endif;?> (<?=$is_available?>)</h4>
								<? endif; ?>
							<? endif; ?></td>
							<td><a href="<?=Yii::app()->createUrl('/kolesoOnline/basket',array('id' => $good->id,'type' => $type))?>" class="b-orange-butt b-cart-delete">Удалить</a></td>
						</tr>
					<? endforeach;?>
					</tbody>
					</table> 
				<? if($delivery):?>
					<h3><span>Обратите внимание:</span> в&nbsp;карточке товара указывается расчет сроков и&nbsp;стоимости доставки для вашего города. Указанные данные рассчитаны на&nbsp;основе информации предоставленной транспортной компанией и&nbsp;являются примерными. Оплата услуг транспортной компании может производиться как нашими сотрудниками, так и&nbsp;самим покупателем при получении (по&nbsp;договоренности с&nbsp;менеджером).</h3>
				<? endif;?>
				<form class="order-form clearfix" action="<?=Yii::app()->createUrl('/kolesoOnline/mail')?>" method="POST" data-block="#b-popup-2">
					<div>
	                    <label for="name">Ваше имя *</label>
	                    <input type="text" name="name" required placeholder="Иван">
	                </div>
	                <div>
	                    <label for="tel">Ваш телефон *</label>
	                    <input type="text" name="phone" required placeholder="+7 (___) ___-__-__">
	                </div>
	                <div class="last">
	                    <label for="tel">Ваша почта</label>
	                    <input type="text" name="mail" placeholder="example@mail.ru">
	                </div>
                    <input type="hidden" name="subject" value="Оплата заказа">
	                <input type="submit" class="right ajax b-orange-butt" value="Оплатить">
	                <h4 class="total-price right">Сумма к оплате: <span>0</span> р.</h4>
	            </form> 
				<? else: $empty = true;?>
				    
				<? endif;?> 
			</div>
			<p class="empty-cart" <?if($empty) echo 'style="display:block;"';?>>корзина пуста</p>
		</div>
	</div>
</div>
</div>

