<?if(!$partial):?>
<div class="b-section-nav clearfix">
	<div class="b-section-nav-back clearfix">
		<ul class="b-section-menu clearfix left" style="border-left: 0px; margin-right: 20px;">
			<li><a href="#" id="b-update-photo">Сохранить</a></li>
			<li><a href="#" data-path="<? echo Yii::app()->createUrl('/uploader/getForm',array('maxFiles'=>20,'extensions'=>'png,jpg', 'title' => 'Загрузка фотографий', 'selector' => '.b-with-nav', 'afterLoad' => 'add-to-photo-sortable', 'tmpPath' => Yii::app()->params["tempFolder"]) ); ?>" class="b-get-image" >Добавить фотографии</a></li>
		</ul>
	</div>
</div>
<div id="photo-cont">
<?endif;?>
<h1 class="b-with-nav"><?=$good->type->name?> <?=$good->fields_assoc[3]->value?>: фотографии</h1>
<ul class="photo-sortable  clearfix" data-sort = 'Images[]' id="photo-sortable" data-href="<?=Yii::app()->createUrl('/good/adminphotoupdate',array('id'=>$good->id))?>">
	<? foreach ($images as $i => $image):?>
	<li style="background-image: url('<?=$image['small']?>');" data-small="<?=$image['small']?>" data-src="<?=$image['original']?>" data-id="<?=$image['id']?>">
		<a href="#" class="b-photo-delete ion-icon ion-close"></a>
		<input type="hidden" name="Images[]" data-name="Images[]" data-delete="Delete[]" value="<?=$image['id']?>">
	</li>
	<? endforeach; ?>
</ul>
<h1 class="b-with-nav-2"></h1>
<div class="b-photo-sortable-cont clearfix">
	<? foreach ($caps as $i => $cap): ?>
		<div class="b-photo-sortable-wrap">
			<h4><?=$cap->name?></h4>
			<ul class="photo-sortable photo-sortable-cap clearfix" id="photo-sortable-<?=$cap->id?>" data-sort = 'Caps[<?=$cap->id?>][]'>
				<? foreach ($cap->images as $i => $image):?>
				<li style="background-image: url('<?=$image['small']?>');" data-small="<?=$image['small']?>" data-src="<?=$image['original']?>" data-id="<?=$image['id']?>">
					<a href="#" class="b-photo-delete ion-icon ion-close"></a>
					<input type="hidden" name="Caps[<?=$cap->id?>][]" data-name="Caps[<?=$cap->id?>][]" value="<?=$image['id']?>">
				</li>
				<? endforeach; ?>
			</ul>
		</div>
	<? endforeach; ?>
</div>
<?if(!$partial):?>
</div>
<? endif; ?>