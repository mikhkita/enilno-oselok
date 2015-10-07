<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'faculties-form',
	'enableAjaxValidation'=>false,
)); ?>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'usr_name'); ?>
		<?php echo $form->textField($model,'usr_name',array('maxlength'=>255,'required'=>true)); ?>
		<?php echo $form->error($model,'usr_name'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'usr_login'); ?>
		<?php echo $form->textField($model,'usr_login',array('maxlength'=>255,'required'=>true)); ?>
		<?php echo $form->error($model,'usr_login'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'usr_password'); ?>
		<?php echo $form->passwordField($model,'usr_password',array('size'=>60,'maxlength'=>128,'required'=>true)); ?>
		<?php echo $form->error($model,'usr_password'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'usr_email'); ?>
		<?php echo $form->textField($model,'usr_email',array('maxlength'=>255,'required'=>true)); ?>
		<?php echo $form->error($model,'usr_email'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'usr_models'); ?>
		<?php echo $form->textField($model,'usr_models',array('maxlength'=>255)); ?>
		<?php echo $form->error($model,'usr_models'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'usr_rol_id'); ?>
		<?php echo $form->dropDownList($model, 'usr_rol_id', CHtml::listData(Role::model()->findAll(), 'id', 'name')); ?>
		<?php echo $form->error($model,'usr_rol_id'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Добавить' : 'Сохранить'); ?>
		<input type="button" onclick="$.fancybox.close(); return false;" value="Отменить">
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->