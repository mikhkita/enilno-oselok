<div class="b-section-nav clearfix">
	<div class="b-section-nav-back clearfix">
		<ul style="border-left: 0px;" class="b-section-menu clearfix left">
			<li><a href="<?php echo $this->createUrl('/advert/adminIndex')?>">Назад</a></li>
		</ul>
	</div>
</div>
<h1 class="b-with-nav">Охват</h1>
<table class="b-table" border="1">
	<tr>
		<th>Товары</th>
		<? foreach ($place as $city => $item): ?>
			<th><?=$city?></th>
		<? endforeach; ?>
	</tr>
	<? foreach ($goods as $code => $good): ?>
		<tr>
			<td><?=$code?></td>
		<? foreach ($good as $city => $item): ?>
			<td><? if(isset($item["url"])): ?>+<? endif; ?></td>
		<? endforeach; ?>
		</tr>
	<? endforeach; ?>
</table>
   
