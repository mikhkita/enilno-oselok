<div class="b-section-nav clearfix">
	<div class="b-section-nav-back clearfix">
		<ul style="border-left: 0px;" class="b-section-menu clearfix left">
			<li><a href="<?php echo $this->createUrl('/advert/adminIndex')?>">Назад</a></li>
		</ul>
	</div>
</div>
<h1 class="b-with-nav">Охват (Томск)</h1>
<table class="b-table" border="1">
	<tr>
		<th>Все товары (<?=count($goods)?>)</th>
		<? foreach ($place as $city => $item): ?>
			<th><?=$city?> (<span title="Выложено">В: <?=$item["count"]?></span>, <span title="Не выложено">Н: <?=(count($goods)-$item["count"]-$item["grey"])?></span>, <span title="Дублей">Д: <?=$item["double"]?>)</span></th>
		<? endforeach; ?>
	</tr>
	<? foreach ($goods as $code => $good): ?>
		<tr>
			<td><?=$good["good"]->fields_assoc[3]->value?></td>
		<? foreach ($good["adverts"] as $city => $item): ?>
			<td <? if( $item["grey"] === true ): ?>class="grey"<? endif; ?>> <a href="#" class="green"><? if(isset($item["url"])): ?>Ссылка<? endif; ?></a></td>
		<? endforeach; ?>
		</tr>
	<? endforeach; ?>
</table>
   
