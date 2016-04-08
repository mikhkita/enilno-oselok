<div class="b-content">
	<div class="b-cart">
		<div class="b-block">
			<ul class="navigation clearfix">
				<li><a href="<?=Yii::app()->createUrl('/kolesoOnline')?>"></a></li>
				<li><a href="#">Корзина</a></li>
			</ul>
			<h1 class="category-title">Корзина</h1>
			<? if(count($goods)): ?>
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
						<td>2310 <span class="b-rub-long">руб.</span></td>
						<td><a href="#" class="b-cart-delete">Удалить</a></td>
					</tr>
				<? endforeach;?>
				</tbody>
				</table>    
			<? else: ?>
			    <p>корзина пуста</p>
			<? endif;?> 
		</div>
	</div>
</div>
</div>

