<h1>Выбор динамических параметров</h1>
<?php $form=$this->beginWidget('CActiveForm',array("action"=>$this->createUrl('/export/adminexport',array('id'=>$id)))); ?>

<div class="b-choose-dynamic">
	<div class="clearfix">
	<? foreach ($data as $key => $attribute): ?>
		<div class="left b-dynamic b-choosable">
			<p><label><?=$attribute->name?></label></p>
			<?php echo CHtml::dropDownList('dynamic['.$attribute->id.']', $defaults[$attribute->id], CHtml::listData($attribute->variants, 'variant_id', 'value')); ?>
		</div>
	<? endforeach; ?>
	</div>
</div>
<div class="row">
	<a href="#" onclick="$('form').submit(); return false;" class="b-butt" style="float: none;">Экспорт</a>
</div>
<?php $this->endWidget(); ?>