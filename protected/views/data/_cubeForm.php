<div class="form">

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

	<div class="row">
		<?php echo $form->labelEx($model,'attribute_id_1'); ?>
		<?php echo $form->DropDownList($model,'attribute_id_1',CHtml::listData(Attribute::model()->findAll(array('order'=>'name ASC','condition'=>'list=1')), 'id', 'name')); ?>
		<?php echo $form->error($model,'attribute_id_1'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'attribute_id_2'); ?>
		<?php echo $form->DropDownList($model,'attribute_id_2',CHtml::listData(Attribute::model()->findAll(array('order'=>'name ASC','condition'=>'list=1')), 'id', 'name')); ?>
		<?php echo $form->error($model,'attribute_id_2'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'attribute_id_3'); ?>
		<?php echo $form->DropDownList($model,'attribute_id_3',CHtml::listData(Attribute::model()->findAll(array('order'=>'name ASC','condition'=>'list=1')), 'id', 'name')); ?>
		<?php echo $form->error($model,'attribute_id_3'); ?>
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