<?if(!$save):?>
<div class="b-section-nav clearfix">
	<div class="b-section-nav-back clearfix">
		<ul class="b-section-menu clearfix left" style="border-left: 0px; margin-right: 20px;">
		    <li><a href="<? echo Yii::app()->createUrl('/good/adminphoto',array("id" => $good_id)); ?>">К фотографиям</a></li>
			<li><a href="#" id="b-photo-save">Сохранить</a></li>
			<li><a href="#" id="rect-cancel">Отменить (Ctrl + z)</a></li>
			<li><a href="#"><input type="color" id="color" name="color" value="#000"></a></li>
		</ul>
	</div>
</div>
<div class="photo-edit-cont" onselectstart="return false;">
<?endif;?>
	
	<div style="background-image: url('/<?=$image_path?>?<?=rand();?>'); width: <?=$width?>px; height: <?=$height?>px;" id="myCanvas" data-href="<?=Yii::app()->createUrl('/good/adminphotoedit',array('id'=>$id, 'save' => true))?>" onselectstart="return false;">
		<div class="helper"></div>
	</div>
<?if(!$save):?>
</div>
<?endif;?>