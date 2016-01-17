<div class="b-popup">
    <h1>Продажа</h1>
    <div class="form">
        <?php $form=$this->beginWidget('CActiveForm', array(
        	'id'=>'faculties-form',
        	'enableAjaxValidation'=>false,
        	'htmlOptions' => array("data-beforeAjax"=>"attributesAjax"),
        )); ?>

        	<?php echo $form->errorSummary($model); ?>
        	<div class="row">
        		<?php echo $form->labelEx($model,'summ'); ?>
        		<?php echo $form->textField($model,'summ',array('maxlength'=>6,'required'=>true)); ?>
        		<?php echo $form->error($model,'summ'); ?>
        	</div>
            <div class="row">
                <?php echo $form->labelEx($model,'extra'); ?>
                <?php echo $form->textField($model,'extra',array('maxlength'=>6)); ?>
                <?php echo $form->error($model,'extra'); ?>
            </div>
            <div class="row">
                <?php echo $form->labelEx($model,'date'); ?>
                <?php echo $form->textField($model,'date',array("id" => 'datepicker','required' => true)); ?>
                <?php echo $form->error($model,'date'); ?>
            </div>
            <div class="row">
                <?php echo $form->labelEx($model,'channel_id'); ?>
                <?php echo $form->dropDownList($model,'channel_id',CHtml::listData(Desktop::getList(80), 'row_id', 'value'),array('class'=> 'select2',"empty" => "Не задано")); ?>
                <?php echo $form->error($model,'channel_id'); ?>
            </div>
            <div class="row">
                <?php echo $form->labelEx($model,'city'); ?>
                <?php echo $form->textField($model,'city',array('class' => 'autocomplete-input')); ?>
                <?php echo $form->error($model,'city'); ?>
            </div>
            <div class="row">
                <?php echo $form->labelEx($model,'comment'); ?>
                <?php echo $form->textArea($model,'comment'); ?>
                <?php echo $form->error($model,'comment'); ?>
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
    </div>
</div>