<div class="b-section-nav clearfix">
	<div class="b-section-nav-back clearfix">
		<ul style="border-left: 0px;" class="b-section-menu clearfix left">
			<li><a href="<?php echo $this->createUrl('/advert/adminSee')?>">Назад</a></li>
		</ul>
	</div>
</div>
<h1 class="b-with-nav">Охват (Томск)</h1>
<table class="b-table" border="1">
	<tr>
		<th>Все товары (<?=count($goods)?>)</th>
		<? foreach ($place as $city => $item): ?>
			<th><?=$city?> (<span title="Выложено">В: <?=$item["count"]?></span>, <span title="Не выложено">Н: <?=(count($goods)-$item["count"]-$item["grey"])?></span>, <span title="Дубли">Д: <?=$item["double"]?>)</span></th>
		<? endforeach; ?>
	</tr>
	<? foreach ($goods as $code => $good): ?>
		<tr>
			<td><?=$good["good"]->fields_assoc[3]->value?></td>
		<? foreach ($good["adverts"] as $city => $item): ?>
			<td <? if( $item["grey"] === true ): ?>class="grey"<? endif; ?>><? if( $item["double"] === true ): ?><span class="red">Дубли</span><?elseif(isset($item["url"])): ?><a href="#" class="green">Ссылка</a><? endif; ?></td>
		<? endforeach; ?>
		</tr>
	<? endforeach; ?>
</table>
<h3>Дубли</h3>
<textarea rows="20"><? foreach ($double_arr as $code): ?><?=($code."\n")?><? endforeach; ?></textarea>
   
