<div class="b-popup b-detail-lot-popup">
	<h1><?=$item->title?></h1>
	<div class="clearfix b-detail-lot">
		<div class="left b-detail-lot-table">
			<table class="b-table" border=1>
				<?=$item->table?>
			</table>
			<div class="b-detail-lot-text">
				<?=$item->text?>
			</div>
		</div>
		<div class="right b-detail-lot-images">
			<?=$item->images?>
		</div>
	</div>
	<div class="row buttons">
		<input type="button" onclick="$.fancybox.close(); return false;" value="Ясно">
		<input type="button" onclick="$('.b-delete-<?=$code?>').click(); $.fancybox.close(); return false;" value="Не показывать">
		<form action="/admin/yahoo/auctioncreate?id=<?=$code?>" method="POST" data-not-scroll="1">>
			<input type="number" required="true" name="Auction[price]" placeholder="Цена выкупа">
			<input type="hidden" name="Auction[code]" value="<?=$code?>">
			<?php echo CHtml::submitButton('Добавить в снайпер'); ?>
		</form
	</div>
</div>