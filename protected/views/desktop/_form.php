<div class="form b-full-width">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'faculties-form',
	'enableAjaxValidation'=>false,
)); ?>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'name'); ?>
		<?php echo $form->textField($model,'name',array('maxlength'=>255,'required'=>true)); ?>
		<?php echo $form->error($model,'name'); ?>
	</div>

	<div class="row checkbox-row clearfix">
		<?php echo $form->labelEx($model,'link'); ?>
		<?php echo $form->checkBox($model,'link'); ?>
		<?php echo $form->error($model,'link'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'value'); ?>
		<?php echo $form->textArea($model,'value',array('maxlength'=>20000,'required'=>true,'style'=>'height: 200px;')); ?>
		<?php echo $form->error($model,'value'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'sort'); ?>
		<?php echo $form->numberField($model,'sort',array('maxlength'=>255,'required'=>true)); ?>
		<?php echo $form->error($model,'sort'); ?>
	</div>

	<? if( Yii::app()->user->checkAccess("rootActions") ): ?>
	<div class="row">
		<?php echo $form->labelEx($model,'rule_code'); ?>
		<?php echo $form->DropDownList($model,'rule_code',CHtml::listData(Rule::model()->findAll(array('order'=>'name ASC')), 'code', 'name')); ?>
		<?php echo $form->error($model,'rule_code'); ?>
	</div>
	<? endif; ?>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Добавить' : 'Сохранить'); ?>
		<input type="button" onclick="$.fancybox.close(); return false;" value="Отменить">
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->