 <div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'faculties-form',
	'enableAjaxValidation'=>false,
	'htmlOptions'=>array(
		'data-input'=>1,
	),
)); ?>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'code'); ?>
		<?php echo $form->textField($model,'code',array('maxlength'=>255,'required'=>true)); ?>
		<?php echo $form->error($model,'code'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'price'); ?>
		<?php echo $form->numberField($model,'price',array('maxlength'=>255,'required'=>true)); ?>
		<?php echo $form->error($model,'price'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Добавить' : 'Добавить'); ?>
		<input type="button" onclick="$.fancybox.close(); return false;" value="Отменить">
	</div>

	<? if(isset($_GET["id"])): ?>
		<input type="hidden" name="id" value="<?=$_GET['id']?>">
	<? endif; ?>

<?php $this->endWidget(); ?>
</div><!-- form -->