<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'faculties-form',
	'enableAjaxValidation'=>false,
)); ?>

	<?php echo $form->errorSummary($model); ?>
	<div class="row">
		<?php echo $form->labelEx($model,'name'); ?>
		<?php echo $form->textField($model,'name',array('maxlength'=>255,'required'=>true,'disabled'=> !( $this->getUserRole() == "root" ) )); ?>
		<?php echo $form->error($model,'name'); ?>
	</div>
<? if( $this->getUserRole() == "root" ):  ?>
	<div class="clearfix">
		<div class="row row-half">
			<?php echo $form->labelEx($model,'code'); ?>
			<?php echo $form->textField($model,'code',array('maxlength'=>255)); ?>
			<?php echo $form->error($model,'code'); ?>
		</div>
		<div class="row row-half">
			<?php echo $form->labelEx($model,'sort'); ?>
			<?php echo $form->numberField($model,'sort',array('maxlength'=>255,'required'=>true)); ?>
			<?php echo $form->error($model,'sort'); ?>
		</div>
	</div>
<? endif; ?>
	<div class="row">
		<?php echo $form->labelEx($model,'value'); ?>
		<?php echo $form->textArea($model,'value',array('class'=>"b-settings-textarea")); ?>
		<?php echo $form->error($model,'value'); ?>
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
	<? if(isset($_GET["category_id"])): ?>
		<input type="hidden" name="Settings[category_id]" value="<?=$_GET['category_id']?>">
	<? endif; ?>
	<? if(isset($_GET["parent_id"])): ?>
		<input type="hidden" name="Settings[parent_id]" value="<?=$_GET['parent_id']?>">
	<? endif; ?>
<?php $this->endWidget(); ?>

</div><!-- form -->