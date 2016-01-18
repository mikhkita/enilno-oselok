<div class="row">
	<?php echo CHtml::activeLabelEx($model,'name'); ?>
	<?php echo CHtml::activeTextField($model,'name',array('maxlength'=>255)); ?>
	<?php echo CHtml::error($model,'name'); ?>
</div>
<div class="row">
    <?php echo CHtml::activeLabelEx($model,'city'); ?>
    <?php echo CHtml::activeTextField($model,'city',array('maxlength'=>100)); ?>
    <?php echo CHtml::error($model,'city'); ?>
</div>
<div class="row">
    <?php echo CHtml::activeLabelEx($model,'referer_id'); ?>
    <?php echo CHtml::activeDropDownList($model, 'referer_id', CHtml::listData(Desktop::getList(82), 'row_id', 'value'),array("empty" => "Не задано")); ?>
    <?php echo CHtml::error($model,'referer_id'); ?>
</div>
<div class="row">
    <?php echo CHtml::activeLabelEx($model,'comment'); ?>
    <?php echo CHtml::activeTextArea($model,'comment',array('class' => 'small-textarea')); ?>
    <?php echo CHtml::error($model,'comment'); ?>
</div>