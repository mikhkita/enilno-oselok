<? if( count($filter_list) ): ?>
	<ul class="b-filter-list clearfix">
		<? foreach ($filter_list as $key => $value): ?>
			<li><span><?=$value?></span></li>
		<? endforeach; ?>
		<li><a href="#" class="b-clear-filter-form">Сбросить фильтр</a></li>
	</ul>
<? endif; ?>