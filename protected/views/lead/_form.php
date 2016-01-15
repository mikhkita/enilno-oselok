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
        <?php echo $form->labelEx($model,'first_city'); ?>
        <?php echo $form->textField($model,'first_city',array('class' => 'autocomplete-input')); ?>
        <?php echo $form->error($model,'first_city'); ?>
    </div>
    <div class="row">
        <?php echo $form->labelEx($model,'second_city'); ?>
        <?php echo $form->textField($model,'second_city',array('class' => 'autocomplete-input')); ?>
        <?php echo $form->error($model,'second_city'); ?>
    </div>
    <div class="row">
        <?php echo $form->labelEx($model,'tk_id'); ?>
        <?php echo $form->dropDownList($model,'tk_id',CHtml::listData(Desktop::getList(80), 'row_id', 'value'),array('class'=> 'select2',"empty" => "Не задано")); ?>
        <?php echo $form->error($model,'tk_id'); ?>
    </div>
    <div class="row">
        <?php echo $form->labelEx($model,'tk_pay_id'); ?>
        <?php echo $form->dropDownList($model,'tk_pay_id',CHtml::listData(Desktop::getList(81), 'row_id', 'value'),array('class'=> 'select2',"empty" => "Не задано","id" => "tk-pay")); ?>
        <?php echo $form->error($model,'tk_pay_id'); ?>
    </div>
    <div class="row">
        <?php echo $form->labelEx($model,'tk_price'); ?>
        <?php echo $form->textField($model,'tk_price',array("disabled" => true, 'id' => 'tk-price')); ?>
        <?php echo $form->error($model,'tk_price'); ?>
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
<div style="display:none;" id="cities">
    <? foreach ($cities as $city):?>
        <p><?=$city?></p>
    <? endforeach; ?>
</div>
</div><!-- form -->