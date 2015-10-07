<?php
$this->pageTitle=Yii::app()->name . ' - Авторизация';
$this->breadcrumbs=array(
	'Login',
);
?>
<h1>Авторизация</h1>
<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'login-form',
	'enableAjaxValidation'=>false,
)); ?>
	<div class="clearfix b-form-cont">
		<div class="left">
			<div class="row row-1">
				<?php echo $form->labelEx($model,'username'); ?>
				<?php echo $form->textField($model,'username'); ?>
			</div>

			<script>
				$(".row-1 input").focus();
			</script>
	
			<div class="row">
				<?php echo $form->labelEx($model,'password'); ?>
				<?php echo $form->passwordField($model,'password'); ?>
				<?php echo $form->error($model,'username'); ?>
				<?php echo $form->error($model,'password'); ?>
			</div>
		</div>
		<div class="right">
			<div class="row submit">
				<?php echo CHtml::submitButton('Войти'); ?>
			</div>
		</div>
	</div>


<?php $this->endWidget(); ?>
