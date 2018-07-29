<?
$mobile = (preg_match('/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|mobile.+firefox|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows ce|xda|xiino/i',$_SERVER['HTTP_USER_AGENT'])||preg_match('/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i',substr($_SERVER['HTTP_USER_AGENT'],0,4)));
?>
<? if(!$partial): ?>
<div class="b-content">
	<div class="b-cart">
		<div class="b-block">
			<ul class="navigation clearfix">
				<li><a href="<?=Yii::app()->createUrl('/kolesoOnline')?>"></a></li>
				<li><a href="#">Оплата</a></li>
			</ul>
				<h1 class="category-title">Оплата</h1>
			<div class="order-cont">
				<form class="clearfix" method="GET" id="order-number" action="<?=Yii::app()->createUrl('/kolesoOnline/pay')?>">
					<input class="left" name="order_id" type="text" placeholder="Введите номер заказа" required>
					<input type="submit" class="left b-orange-butt" value="Найти">
				</form>
			<div class="order-desc">
			<? endif; ?>
				<? if(count($goods)): $empty = false; $delivery = false;?>
					<table class="gradient-grey">
					<tbody>
					<!-- 	<tr>
							<th>Наименование</th>
							<th>Цена</th>
							<th></th>
						</tr> -->
					<? foreach ($goods as $good): $type = $good->good_type_id; $href = Yii::app()->createUrl('/kolesoOnline/detail',array('id' => ($good->code)?$good->code:$good->fields_assoc[3]->value,'type' => $type)); ?>
						<? if(!$good->archive):?>
							<? if(!$mobile): ?>
							<tr>		
								<td class="clearfix title">
									<a target="_blank" href="<?=$href?>" class="img left" style="background-image: url(<? $images = $good->getImages(1, array("small"), 1, NULL, true); echo $images[0]["small"];?>);" href="<?=$href?>"></a>
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
								<td>&nbsp;					
								</td>
								<td class="price"><? $price = Interpreter::generate($this->params[$type]["PRICE_CODE"], $good, $dynamic); ?>
									<h3 data-price="<?=$price?>" class="cart-price"><?=number_format($price, 0, ',', ' ' )." р."; ?></h3>
									<? $is_available = Interpreter::generate($this->params[$type]["AVAILABLE"], $good, $dynamic); ?>
									<? if($is_available != "В наличии"): ?>
										<? $delivery = Interpreter::generate($this->params[$type]["SHIPPING"], $good,$dynamic);?>
										<? if($delivery):?>
											<h4 <?if($delivery=="бесплатная"): $delivery = "+ доставка бесплатно"?>class="b-free-delivery"<?endif;?>> <span> <?=$delivery?></span> (<?=$is_available?>)</h4>
										<? endif; ?>
									<? endif; ?>
								</td>
								
							</tr>
							<? else:?>
							<tr>
								<td>
									<a target="_blank" href="<?=$href?>" class="img" style="background-image: url(<? $images = $good->getImages(1, array("small"), 1, NULL, true); echo $images[0]["small"];?>);" href="<?=$href?>"></a>
								</td>
							</tr>
							<tr>
								<td>
									<? 
						                if($type == 1) $title = $good->fields_assoc[16]->value." ".$good->fields_assoc[17]->value;
						                if($type == 2) $title = $good->fields_assoc[6]->value;
						                if($type == 3) $title = Interpreter::generate($this->params[$type]["TITLE_CATEGORY"], $good,$dynamic);
						            ?>
						            <div>
										<a class="mobile-title" href="<?=$href?>" target="_blank"><?=$title?></a>
										<h4><?=Interpreter::generate($this->params[$type]["TITLE_2_CODE"], $good,$dynamic);?> <?if($type == 1) echo $good->fields_assoc[$this->params[$type]["CATEGORY"]["AMOUNT"]['ID']]->value." ".$this->params[$type]["CATEGORY"]["AMOUNT"]['UNIT']?></h4>
									</div>
								</td>
							</tr>
							<tr>
								<td><? $price = Interpreter::generate($this->params[$type]["PRICE_CODE"], $good, $dynamic);?>
									<h3 data-price="<?=$price?>" class="cart-price"><?=number_format($price, 0, ',', ' ' )." р."; ?></h3>
									<? $is_available = Interpreter::generate($this->params[$type]["AVAILABLE"], $good, $dynamic); ?>
									<? if($is_available != "В наличии"): ?>
										<? $delivery = Interpreter::generate($this->params[$type]["SHIPPING"], $good,$dynamic);?>
										<? if($delivery):?>
											<h4 <?if($delivery=="бесплатная"): $delivery = "+ доставка бесплатно"?>class="b-free-delivery"<?endif;?>> <span> <?=$delivery?></span>	(<?=$is_available?>)</h4>
										<? endif; ?>
									<?endif;?> 
								</td>
							</tr>
							<? endif; ?>
						<? endif; ?>
					<? endforeach;?>
					</tbody>
					</table> 
				<? if($delivery):?>
					<h3 class="mobile-deliv"><span>Обратите внимание:</span> в&nbsp;карточке товара указывается расчет сроков и&nbsp;стоимости доставки для вашего города. Указанные данные рассчитаны на&nbsp;основе информации предоставленной транспортной компанией и&nbsp;являются примерными. Оплата услуг транспортной компании может производиться как нашими сотрудниками, так и&nbsp;самим покупателем при получении (по&nbsp;договоренности с&nbsp;менеджером).</h3>
				<? endif;?>
				<form class="order-form clearfix" action="https://paymaster.ru/Payment/Init" method="POST">
					<!-- <div>
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
                    <input type="hidden" name="subject" value="Оформление заказа"> -->

                    <input type="hidden" name="LMI_MERCHANT_ID" value="054d638b-fe9a-4a62-8a48-1f722ce4fdbb">
                    <input type="hidden" name="LMI_PAYMENT_AMOUNT">
                    <input type="hidden" name="LMI_CURRENCY" value="RUB">
                    <input type="hidden" name="LMI_PAYMENT_NO" value="<?=$order_id?>">
                    <input type="hidden" name="LMI_PAYMENT_DESC" value="Оплата заказа №<?=$order_id?>">
	                <input type="submit" class="right b-orange-butt" value="Оплатить">
	                <h4 class="total-price right">Сумма к оплате: <span>0</span> р.</h4>
	            </form> 
				<? elseif($order_id): ?>
				    <p class="empty-cart" style="display:block;">Заказ не найден</p>
				<? endif;?> 
			<? if(!$partial) :?>
			</div>
			</div>
			<!-- <p class="empty-cart" <?if($empty) echo 'style="display:block;"';?>>Ваша корзина пуста. <a href="<?=Yii::app()->createUrl('/kolesoOnline/')?>">Перейти на главную страницу</a></p> -->
		</div>
	</div>
</div>
</div>
<? endif; ?>
