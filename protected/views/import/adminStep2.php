<h1><?=$model->name?></h1>

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'import-step2',
	'action' => Yii::app()->createUrl('/'.$this->adminMenu["cur"]->code.'/adminstep3'),
	'enableAjaxValidation'=>false
)); ?>

	<?php echo $form->errorSummary($model); ?>

	<div class="row import-list clearfix" style="display:inline-block;">
        <div class="left">
            <label for="">Все атрибуты</label>
            <ul id="attr-list">
                <? foreach ($model->fields as $key => $value): ?>
                <li data-id="<?=$value->attribute->id?>"><?=$value->attribute->name?></li>
                <? endforeach; ?>
            </ul>
        </div>
        <div class="left">
            <label for="">Атрибуты Excel</label>
          	<ul id="imp-sort">
                <? foreach ($xls as $key=>$value): ?>
                <li class="ui-widget-content"><?=$value?><input type="hidden" name="excel[<?=$key?>]" value="no-id"></li>
                <? endforeach; ?>
                <? if( count($model->fields) > count($xls) ): ?>
                    <? for( $i = 0; $i < count($model->fields)-count($xls); $i++ ): ?>
                    <li class="ui-state-default">&nbsp;</li>
                    <? endfor; ?>
                <? endif; ?>
            </ul>
        </div>
    </div>

	<div class="row buttons" style="text-align: center;">
        <a href="#" onclick="$('#import-step2').submit();" style="float:none;" class="b-butt">Перейти к предпросмотру</a>
		<input type="hidden" name="excel_path" value="<?=$excel_path?>">
        <input type="hidden" name="GoodTypeId" value="<?=$GoodTypeId?>">
	</div>

<?php $this->endWidget(); ?>