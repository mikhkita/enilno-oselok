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

	<div class="row">
		<?php echo $form->labelEx($model,'good_type_id'); ?>
		<?php echo $form->dropDownList($model, 'good_type_id', CHtml::listData(GoodType::model()->findAll(), 'id', 'name')); ?>
		<?php echo $form->error($model,'good_type_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->CheckBox($model,'service',array("class"=>"b-checkbox","style"=>"width:auto;")); ?>
		<?php echo $form->labelEx($model,'service',array("style"=>"display:inline-block;")); ?>
		<?php echo $form->error($model,'service'); ?>
	</div>

	<div class="row">
		<?php echo $form->CheckBox($model,'unique',array("class"=>"b-checkbox","style"=>"width:auto;")); ?>
		<?php echo $form->labelEx($model,'unique',array("style"=>"display:inline-block;")); ?>
		<?php echo $form->error($model,'unique'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'template'); ?>
		<?php echo $form->textArea($model,'template',array('maxlength'=>20000,'class'=>'visual-inter','required'=>true,'style'=>'height: 250px; resize: vertical;','data-href'=>$this->createUrl('/interpreter/adminvisual'),'data-block'=>".b-inter-visual")); ?>
		<?php echo $form->error($model,'template'); ?>
	</div>

	<div class="row">
		<div class="b-inter-visual">
			<?=$this->visualInter($model->template)?>
		</div>
	</div>

	<? if($this->isRoot()): ?>
	<div class="row">
		<?php echo $form->labelEx($model,'rule_code'); ?>
		<?php echo $form->DropDownList($model,'rule_code',CHtml::listData(Rule::model()->findAll(array('order'=>'name ASC')), 'code', 'name')); ?>
		<?php echo $form->error($model,'rule_code'); ?>
	</div>
	<? endif; ?>

	<div class="row">
		<?php echo $form->labelEx($model,'width'); ?>
		<?php echo $form->textField($model,'width',array('maxlength'=>255,'required'=>true,'class'=>'numeric')); ?>
		<?php echo $form->error($model,'width'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Добавить' : 'Сохранить'); ?>
		<input type="button" onclick="$.fancybox.close(); return false;" value="Отменить">
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->