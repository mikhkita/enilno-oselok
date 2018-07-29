<?if(!$partial):?>
<div class="b-section-nav clearfix">
	<div class="b-section-nav-back clearfix">
		<!-- <input type="file" id="tmp-input" multiple> -->
		<ul class="b-section-menu clearfix left" style="border-left: 0px; margin-right: 20px;">
			<li><a href="#" id="b-update-photo">Сохранить</a></li>
			<li><a href="#" data-path="<? echo Yii::app()->createUrl('/uploader/getForm',array('maxFiles'=>40,'extensions'=>'png,jpg,jpeg', 'title' => 'Загрузка фотографий', 'selector' => '.b-with-nav', 'afterLoad' => 'add-to-photo-sortable', 'tmpPath' => Yii::app()->params["tempFolder"]) ); ?>" class="b-get-image" >Добавить фотографии</a></li>
			<? if( $this->access("photo", "cap") ): ?>
				<li>
					<select id="b-photo-to" style="margin-left: 20px;">
						<? foreach ($caps as $i => $cap): ?>
							<option value="<?=$cap->id?>"><?=$cap->name?></option>
						<? endforeach; ?>
					</select>
				</li>
				<li><a href="#" id="b-multi-photo">Переместить</a></li>
			<? endif; ?>
			<li><a href="#" class="unselect-all" data-items="#photo-sortable li">Сбросить выделение</a></li>
		</ul>
	</div>
</div>
<div id="photo-cont">
<?endif;?>
<h1 class="b-with-nav"><?=$good->type->name?> <?=$good->fields_assoc[3]->value?>: фотографии</h1>
<? if( $this->access("photo", "cap") ): ?>
	<div class="b-photo-nav">
		<a href="#" class="select-all" data-items="#photo-sortable li">Выделить все</a>
	</div>
<? endif; ?>
<ul class="photo-sortable  clearfix" data-sort = 'Images[]' id="photo-sortable" data-href="<?=Yii::app()->createUrl('/good/adminphotoupdate',array('id'=>$good->id))?>">
	<? foreach ($images as $i => $image):?>
	<li style="background-image: url('<?=$image['small']?>?<?=rand();?>');" data-small="<?=$image['small']?>" data-src="<?=$image['original']?>" data-id="<?=$image['id']?>">
		<a href="#" class="b-photo-delete ion-icon ion-close"></a>
		<a href="<? echo Yii::app()->createUrl('/good/adminphotoedit',array("id" => $image['id'])); ?>" class="b-photo-paint ion-icon ion-paintbucket"></a>
		<a href="<? echo Yii::app()->createUrl('/good/adminphotodownload',array("file" => $image['original'])); ?>" class="b-photo-download ion-icon ion-arrow-down-a"></a>
		<a href="<? echo Yii::app()->createUrl('/good/adminphotorotate',array("id" => $image['id'], "side" => "right")); ?>" class="ajax-request b-photo-rotate b-photo-rotate-right ion-icon ion-arrow-return-right"></a>
		<a href="<? echo Yii::app()->createUrl('/good/adminphotorotate',array("id" => $image['id'], "side" => "left")); ?>" class="ajax-request b-photo-rotate b-photo-rotate-left ion-icon ion-arrow-return-left"></a>
		<input type="hidden" name="Images[]" data-name="Images[]" data-delete="Delete[]" value="<?=$image['id']?>">
	</li>
	<? endforeach; ?>
</ul>
<? if( $this->access("photo", "cap") ): ?>
<h1 class="b-with-nav-2"></h1>
<div class="b-photo-sortable-cont clearfix">
	<? foreach ($caps as $i => $cap): ?>
		<div class="b-photo-sortable-wrap" <? if($cap->id == 1): ?>id="b-site-photo"<? endif; ?>>
			<h4><?=$cap->name?><br><a href='#' class="b-clear-cap">Очистить</a></h4>
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
<? endif; ?>
<?if(!$partial):?>
</div>
<? endif; ?>