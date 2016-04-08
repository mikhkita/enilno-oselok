<div class="b-content">
	<ul class="navigation clearfix">
		<li><a href="<?=Yii::app()->createUrl('/kolesoOnline')?>"></a></li>
		<li><a href="#">Корзина</a></li>
	</ul>
	<? if(count($goodss)): ?>
		<table id="basket_items">
		<tbody>
			<tr>
				<th>Название</th>
				<th>Цена</th>
				<th></th>
			</tr>
		<tr>
			<td class="item-title">
				<div>
					<div class="img-cont">
						<img src="/upload/resize_cache/iblock/567/112_112_2/567d6df91b38282991a9022c4cbd8dff.png">	   			
					</div>
					<h4 class="sub-title">Калиновый</h4>
				</div>
			</td>
			<td>2310 <span class="b-rub-long">руб.</span><span class="b-rub-short">Р</span></td>
			<td><a href="#" class="b-cart-delete">Удалить</a></td>
		</tr>
		</tbody>
		</table>    
	<? else: ?>
	    <p>корзина пуста</p>
	<? endif;?> 
</div>

