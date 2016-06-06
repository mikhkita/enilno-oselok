<div class="row">
	<?php echo CHtml::activeLabelEx($model,'name'); ?>
	<?php echo CHtml::activeTextField($model,'name',array('maxlength'=>255)); ?>
	<?php echo CHtml::error($model,'name'); ?>
</div>
<div class="row">
    <?php echo CHtml::activeLabelEx($model,'sex'); ?>
    <?php echo CHtml::activeRadioButtonList($model, 'sex', array("1" => "мужской","2" => "женский")); ?>
    <?php echo CHtml::error($model,'sex'); ?>
</div>
<div class="row">
    <?php echo CHtml::activeLabelEx($model,'car'); ?>
    <?php echo CHtml::activeTextField($model,'car',array('maxlength'=>50)); ?>
    <?php echo CHtml::error($model,'car'); ?>
</div>
<div class="clearfix">
    <div class="row row-half">
        <?php echo CHtml::activeLabelEx($model,'source_id'); ?>
        <?php echo CHtml::activeDropDownList($model, 'source_id', CHtml::listData(Desktop::getList(82), 'row_id', 'value'),array("empty" => "Не задано")); ?>
        <?php echo CHtml::error($model,'source_id'); ?>
    </div>
    <div class="row row-half">
        <?php echo CHtml::activeLabelEx($model,'client_type_id'); ?>
        <?php echo CHtml::activeDropDownList($model, 'client_type_id', CHtml::listData(Desktop::getList(135), 'row_id', 'value'),array("empty" => "Не задано")); ?>
        <?php echo CHtml::error($model,'client_type_id'); ?>
    </div>
</div>
<div class="row">
    <?php echo CHtml::activeLabelEx($model,'city'); ?>
    <?php echo CHtml::activeTextField($model,'city',array('maxlength'=>100)); ?>
    <?php echo CHtml::error($model,'city'); ?>
</div>

<div class="row">
    <?php echo CHtml::activeLabelEx($model,'link'); ?>
    <?php echo CHtml::activeTextField($model,'link',array('maxlength'=>100)); ?>
    <?php echo CHtml::error($model,'link'); ?>
</div>
<div class="row">
    <?php echo CHtml::activeLabelEx($model,'other'); ?>
    <?php echo CHtml::activeTextArea($model,'other',array('class' => 'small-textarea')); ?>
    <?php echo CHtml::error($model,'other'); ?>
</div>