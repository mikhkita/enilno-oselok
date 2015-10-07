<h1><?=$this->adminMenu["cur"]->name?></h1>
<div class="b-import">
	<div class="progress">
	    <div class="progress-bar progress-bar-striped active" role="progressbar" style="width:3%">3%</div>
	</div>
	<ul class="b-log"></ul>
</div>
<?php $form=$this->beginWidget('CActiveForm', array(
	'enableAjaxValidation'=>false,
	'id' => "link"
)); ?>
	<textarea class="link" name="link"></textarea>
	<input type="submit" class="b-butt" value="Получить изображения">
	
<?php $this->endWidget(); ?>

