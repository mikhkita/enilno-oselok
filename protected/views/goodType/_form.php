<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'faculties-form',
	'enableAjaxValidation'=>false,
	'htmlOptions' => array("data-beforeAjax"=>"attributesAjax"),
)); ?>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'name'); ?>
		<?php echo $form->textField($model,'name',array('maxlength'=>255,'required'=>true,'style'=>'width:622px;')); ?>
		<?php echo $form->error($model,'name'); ?>
	</div>

	<div class="row double-list clearfix">
        <div class="left">
            <label for="">Все атрибуты</label>
            <ul id="sortable1" class="sortable connectedSortable">
                <? foreach ($allAttr as $key => $value): ?>
                <li class="ui-state-default" data-id="<?=$value?>"><p><?=$value?></p><input type="hidden" name="attributes[<?=$key?>]" value="<?=$value?>"></li>
                <? endforeach; ?>
            </ul>
        </div>
        <div class="left">
            <label for="">Атрибуты этого <?=$this->adminMenu["cur"]->rod_name?><?=( ( isset($_GET["name"]) )?(" \"".$_GET["name"]."\""):("") )?></label>
            <ul id="sortable2" class="sortable connectedSortable">
				<? foreach ($attr as $key => $value): ?>
                <li class="ui-state-default" data-id="<?=$value?>"><p><?=$value?></p><input type="hidden" name="attributes[<?=$key?>]" value="<?=$value?>"><span></span></li>
                <? endforeach; ?>
            </ul>
        </div>
    </div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Добавить' : 'Сохранить'); ?>
		<input type="button" onclick="$.fancybox.close(); return false;" value="Отменить">
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->