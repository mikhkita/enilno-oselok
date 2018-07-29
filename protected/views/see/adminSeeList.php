<div class="b-section-nav clearfix">
	<div class="b-section-nav-back clearfix">
		<ul style="border-left: 0px;" class="b-section-menu clearfix left">
			<li><a href="<?php echo $this->createUrl('/see/adminindex')?>">Назад</a></li>
			<? if($_GET["problem_only"]): ?>
				<li><a href="<?php echo $this->createUrl('/see/adminlist', array("good_type_id" => $_GET["good_type_id"]))?>" class="b-link left">Все объявления</a></li>
			<? else: ?>
				<li><a href="<?php echo $this->createUrl('/see/adminlist', array("good_type_id" => $_GET["good_type_id"], "problem_only" => true))?>" class="b-link left">Только проблемные</a></li>
			<? endif; ?>
		</ul>
	</div>
</div>
<h1 class="b-with-nav">Охват (Томск). <?=(($_GET["problem_only"])?"Проблемных":"Всего")?> объявлений: <?=count($goods)?></h1>
<table class="b-table b-see-list" border="1">
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
			<? if( $item["grey"] !== true && $item["error"] && !$item["url"] ): ?>
				<td>
					<?=implode(", ", $item["error"])?>
				</td>
			<? else: ?>
				<td class="<? if( $item["grey"] === true ): ?>grey<? endif; ?><?if($item["not_active"]):?> red<?endif;?>"><? if( $item["double"] === true ): ?><span class="red">Дубли</span><?elseif(isset($item["url"])): ?><a href="<?=Advert::getUrl($item["code"],$item["url"]);?>" target="_blank" class="green">Ссылка</a><? endif; ?></td>
			<? endif; ?>
		<? endforeach; ?>
		</tr>
	<? endforeach; ?>
</table>
<h3>Дубли</h3>
<textarea rows="20"><? foreach ($double_arr as $code): ?><?=($code."\n")?><? endforeach; ?></textarea>
   
