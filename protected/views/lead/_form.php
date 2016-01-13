<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'faculties-form',
	'enableAjaxValidation'=>false,
	'htmlOptions' => array("data-beforeAjax"=>"attributesAjax"),
)); ?>

	<?php echo $form->errorSummary($model); ?>
	<div class="row">
		<?php echo $form->labelEx($model,'name'); ?>
		<?php echo $form->textField($model,'name',array('maxlength'=>255)); ?>
		<?php echo $form->error($model,'name'); ?>
	</div>
    <div class="row">
        <?php echo $form->labelEx($model,'first_phone'); ?>
        <?php echo $form->textField($model,'first_phone',array('maxlength'=>25,'required'=>true,'class'=>'phone')); ?>
        <?php echo $form->error($model,'first_phone'); ?>
    </div>
    <div class="row">
        <?php echo $form->labelEx($model,'date'); ?>
        <?php echo $form->textField($model,'date',array("id" => 'datepicker','required' => true)); ?>
        <?php echo $form->error($model,'date'); ?>
    </div>
    <div class="row">
        <?php echo $form->labelEx($model,'referrer_id'); ?>
        <?php echo $form->dropDownList($model, 'referrer_id', CHtml::listData(Desktop::getList(82), 'row_id', 'value'),array('class'=> 'select2',"empty" => "Не задано")); ?>
        <?php echo $form->error($model,'referrer_id'); ?>
    </div>
    <div class="row">
        <?php echo $form->labelEx($model,'state_id'); ?>
        <?php echo $form->dropDownList($model, 'state_id', CHtml::listData(Desktop::getList(83), 'row_id', 'value'),array('class'=> 'select2')); ?>
        <?php echo $form->error($model,'state_id'); ?>
    </div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Добавить' : 'Сохранить'); ?>
		<input type="button" onclick="$.fancybox.close(); return false;" value="Отменить">
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->