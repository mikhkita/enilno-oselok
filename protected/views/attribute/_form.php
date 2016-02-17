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
		<?php echo $form->labelEx($model,'alias'); ?>
		<?php echo $form->textField($model,'alias',array('maxlength'=>255)); ?>
		<?php echo $form->error($model,'alias'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'label'); ?>
		<?php echo $form->textField($model,'label',array('maxlength'=>255,'class'=>'numeric')); ?>
		<?php echo $form->error($model,'label'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'attribute_type_id'); ?>
		<?php echo $form->DropDownList($model,'attribute_type_id',CHtml::listData(AttributeType::model()->findAll(array('order'=>'id ASC')), 'id', 'name')); ?>
		<?php echo $form->error($model,'attribute_type_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->CheckBox($model,'major',array("class"=>"b-checkbox")); ?>
		<?php echo $form->labelEx($model,'major'); ?>
		<?php echo $form->error($model,'major'); ?>
	</div>

	<div class="row">
		<?php echo $form->CheckBox($model,'multi',array("class"=>"b-checkbox")); ?>
		<?php echo $form->labelEx($model,'multi'); ?>
		<?php echo $form->error($model,'multi'); ?>
	</div>

	<div class="row">
		<?php echo $form->CheckBox($model,'list',array("class"=>"b-checkbox")); ?>
		<?php echo $form->labelEx($model,'list'); ?>
		<?php echo $form->error($model,'list'); ?>
	</div>

	<div class="row">
		<?php echo $form->CheckBox($model,'dynamic',array("class"=>"b-checkbox")); ?>
		<?php echo $form->labelEx($model,'dynamic'); ?>
		<?php echo $form->error($model,'dynamic'); ?>
	</div>

	<div class="row">
		<?php echo $form->CheckBox($model,'required',array("class"=>"b-checkbox")); ?>
		<?php echo $form->labelEx($model,'required'); ?>
		<?php echo $form->error($model,'required'); ?>
	</div>

	<div class="row">
		<?php echo $form->CheckBox($model,'folder',array("class"=>"b-checkbox")); ?>
		<?php echo $form->labelEx($model,'folder'); ?>
		<?php echo $form->error($model,'folder'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'group_id'); ?>
		<?php echo $form->dropDownList($model, 'group_id', CHtml::listData(Attribute::model()->findAll( ((isset($_GET["id"]))?("id!=".$_GET["id"]):NULL ) ), 'id', 'name'),array('class'=> 'select2',"empty" => "Не задано",'style' => 'width:442px;')); ?>
		<?php echo $form->error($model,'group_id'); ?>
	</div>

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