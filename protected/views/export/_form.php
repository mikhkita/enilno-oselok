<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'export-form',
	'enableAjaxValidation'=>false,
	'htmlOptions' => array("data-beforeAjax"=>"attributesAjax",'data-beforeShow' => 'exportBeforeShow','data-getFieldsUrl' => Yii::app()->createUrl('/'.$this->adminMenu["cur"]->code.'/admingetfields')),
)); ?>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'name'); ?>
		<?php echo $form->textField($model,'name',array('maxlength'=>255,'required'=>true,'style'=>'width:622px;')); ?>
		<?php echo $form->error($model,'name'); ?>
	</div>

    <div class="row">
        <?php echo $form->labelEx($model,'good_type_id'); ?>
        <?php echo $form->dropDownList($model, 'good_type_id', CHtml::listData(GoodType::model()->findAll(), 'id', 'name'),array("id"=>"export-good-type-id")); ?>
        <?php echo $form->error($model,'good_type_id'); ?>
    </div>

	<div class="row double-list clearfix">
        <div class="left">
            <label for="">Все атрибуты</label>
            <ul id="sortable1" class="sortable connectedSortable">
                <? foreach ($allAttr as $key => $value): ?>
                <li class="ui-state-default" data-id="<?=$value->name?>"><p><?=$value->name?></p><input type="hidden" name="sorted[]" value="<?if($key[strlen($key)-1] == "a"):?>attributes<?else:?>interpreters<?endif;?>-<?=$value->id?>"></li>
                <? endforeach; ?>
            </ul>
        </div>
        <div class="left">
            <label for="">Атрибуты этого шаблона<?=( ( isset($_GET["name"]) )?(" \"".$_GET["name"]."\""):("") )?></label>
            <ul id="sortable2" class="sortable connectedSortable">
				<? foreach ($attr as $key => $value): ?>
                <li class="ui-state-default" data-id="<?=$value->name?>"><p><?=$value->name?></p><input type="hidden" name="sorted[]" value="<?if($key[strlen($key)-1] == "a"):?>attributes<?else:?>interpreters<?endif;?>-<?=$value->id?>"><span></span></li>
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