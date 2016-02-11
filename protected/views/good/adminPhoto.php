<h1><?=$good->type->name?> <?=$good->fields_assoc[3]->value?>: фотографии</h1>
<ul class="photo-sortable clearfix" data-href="<?=Yii::app()->createUrl('/good/adminphotoupdate',array('id'=>$good->id))?>">
	<? foreach ($images as $i => $image):?>
	<li style="background-image: url('<?=$image?>');">
		<input type="hidden" name="Images[]" value="<?=$image?>">
	</li>
	<? endforeach; ?>
</ul>