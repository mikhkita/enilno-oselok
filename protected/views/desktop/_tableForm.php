<div class="form full-width">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'faculties-form',
	'enableAjaxValidation'=>false,
)); ?>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'name'); ?>
		<?php echo $form->textField($model,'name',array('maxlength'=>255,'required'=>true,'style'=>'width:622px;')); ?>
		<?php echo $form->error($model,'name'); ?>
	</div>
	<input type="hidden" name="DesktopTable[folder_id]" value="<?=$_GET["folder_id"]?>">

	<div class="row">
		<ul class="b-add-items">
			<? foreach ($model->cols as $col): ?>
			<li>
				<p><span><?=$col->name?> (<?=$col->type->name?>)</span><a href="#" class="b-add-remove">Удалить</a></p>
				<input type="hidden" name="col[]" value="<?=$col->id?>">
			</li>
	        <? endforeach; ?>
		</ul>
	</div>

	<div class="row b-add-cont clearfix">
		<input type="text" class="left" name="add-code" id="add-code">
		<?php echo CHtml::DropDownList('add',array(),CHtml::listData(DesktopTableColType::model()->findAll(array('order'=>'id ASC')), 'id', 'name'),array("id"=>"add-inter","class"=>"left")); ?>
		<div class="buttons left">
			<input type="button" id="add-inter-button" data-name="new_col" value="Добавить">
		</div>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Добавить' : 'Сохранить'); ?>
		<input type="button" onclick="$.fancybox.close(); return false;" value="Отменить">
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->