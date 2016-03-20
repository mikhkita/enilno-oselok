<?if(!$partial):?>
<div class="b-section-nav clearfix">
	<div class="b-section-nav-back clearfix">
		<ul class="b-section-menu clearfix left" style="border-left: 0px; margin-right: 20px;">
			<li><a href="#" id="b-update-photo">Сохранить</a></li>
			<!-- <li><a href="#" id="b-reset-photo">Сбросить</a></li> -->
			<li><a href="#" data-path="<? echo Yii::app()->createUrl('/uploader/getForm',array('maxFiles'=>20,'extensions'=>'png,jpg', 'title' => 'Загрузка фотографий', 'selector' => '.b-with-nav', 'afterLoad' => 'add-to-photo-sortable', 'tmpPath' => Yii::app()->params["tempFolder"]) ); ?>" class="b-get-image" >Добавить фотографии</a></li>
			<li><a href="#" data-path="<? echo Yii::app()->createUrl('/uploader/getForm',array('maxFiles'=>20,'extensions'=>'png,jpg', 'title' => 'Загрузка фотографий', 'selector' => '.b-with-nav-2', 'afterLoad' => 'add-to-photo-sortable-2', 'tmpPath' => Yii::app()->params["tempFolder"]) ); ?>" class="b-get-image" >Добавить доп. фотографии</a></li>
		<!-- 	<li><a href="<?php echo $this->createUrl('/good/adminupdateall',array('good_type_id'=> $_GET["good_type_id"],'GoodFilter_page' => ($pages->currentPage+1) ))?>" class="ajax-form ajax-create" data-block=".b-popup-filter">Города</a></li>
			<li><a href="<?php echo $this->createUrl('/advert/adminindex',array('good_type_id'=> $_GET["good_type_id"]))?>">Объявления</a></li> -->
			<!-- <li><a href="<?php echo $this->createUrl('/good/adminupdateall',array('good_type_id'=> $_GET["good_type_id"],'GoodFilter_page' => ($pages->currentPage+1) ))?>" class="ajax-form ajax-create" data-block=".b-popup-filter">Экспорт</a></li> -->
			<!-- <li><a href="<?php echo $this->createUrl('/good/adminupdateadverts',array('good_type_id'=> $_GET["good_type_id"], 'images' => '1'))?>" class="ajax-update-prices">Обновить&nbsp;фотографии</a></li> -->
		</ul>
	</div>
</div>
<div id="photo-cont">
<?endif;?>
<h1 class="b-with-nav"><?=$good->type->name?> <?=$good->fields_assoc[3]->value?>: фотографии</h1>
<ul class="photo-sortable clearfix" id="photo-sortable" data-href="<?=Yii::app()->createUrl('/good/adminphotoupdate',array('id'=>$good->id))?>">
	<? foreach ($images as $i => $image):?>
	<li style="background-image: url('<?=$image['small']?>');" data-small="<?=$image['small']?>" data-src="<?=$image['original']?>">
		<a href="#" class="b-photo-delete ion-icon ion-close"></a>
		<input type="hidden" name="Images[]" data-name="Images[]" data-delete="Delete[]" value="<?=$image['original']?>">
	</li>
	<? endforeach; ?>
</ul>
<h1 class="b-with-nav-2"></h1>
<ul class="photo-sortable clearfix" id="photo-sortable-2">
	<? foreach ($extra as $i => $image):?>
	<li style="background-image: url('<?=$image['small']?>');" data-small="<?=$image['small']?>" data-src="<?=$image['original']?>">
		<a href="#" class="b-photo-delete ion-icon ion-close"></a>
		<input type="hidden" name="Extra[]" data-name="Extra[]" data-delete="Delete[]" value="<?=$image['original']?>">
	</li>
	<? endforeach; ?>
</ul>
<?if(!$partial):?>
</div>
<? endif; ?>