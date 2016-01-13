<div class="b-house">
	<div class="b-block"><h2><? echo $title; ?></h2></div>
	<div class="b-house-grass">
		<div class="b-house-slider">
			<? foreach ($data as $i => $item): ?>
				<div class="b-house-item b-house-item-<? echo $item->hou_typ_id; ?>">
		        	<a href="#"><h3><? echo $item->hou_name; ?></h3></a>
		        </div>
			<? endforeach; ?>
		</div>
		<div class="controls">
			<a href="#" class="b-nav b-nav-left"></a>
			<a href="#" class="b-nav b-nav-right"></a>
		</div>
	</div>
</div>