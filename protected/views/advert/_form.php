<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'export-form',
	'enableAjaxValidation'=>false,
	'htmlOptions' => array("data-beforeAjax"=>"attributesAjax",'data-beforeShow' => 'exportBeforeShow','data-getFieldsUrl' => Yii::app()->createUrl('/'.$this->adminMenu["cur"]->code.'/admingetfields')),
)); ?>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<label>Номер объявления</label>
		<?php echo $form->textField($model,'url',array('maxlength'=>255)); ?>
		<?php echo $form->error($model,'url'); ?>
	</div>

	<input type="hidden" name="id" value="<?=$model->id?>">

	<div class="row buttons">
		<?php echo CHtml::submitButton('Сохранить'); ?>
		<input type="button" onclick="$.fancybox.close(); return false;" value="Отменить">
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->