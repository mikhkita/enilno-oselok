<div class="form b-good-form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'faculties-form',
	'enableAjaxValidation'=>false,
)); ?>

	<?php echo $form->errorSummary($model); ?>
	<div class="clearfix">
	
	<? foreach ($model->type->fields as $item): ?>
		<div class="row">
			<label><?=$item->attribute->name?></label>
			<? $attr_id = (isset($result[$item->attribute_id]) && $result[$item->attribute_id]) ? $result[$item->attribute_id] : ""; if($item->attribute->list): ?>
				<?  if($item->attribute->multi): ?>
					<? $selected = array(); if(!empty($attr_id)) foreach ($attr_id as $multi) $selected[$multi] = array('selected' => 'selected'); ?>
						<?php echo Chtml::dropDownList("Good_attr[".$item->attribute_id."]", "", CHtml::listData(AttributeVariant::model()->findAll(array("condition" => "attribute_id=".$item->attribute_id,"order" => "sort ASC")), 'id', $item->attribute->type->code.'_value'),array('class'=> 'select2','multiple' => 'true', 'options' => $selected)); ?>	
				<? else: ?>
					<?php echo Chtml::dropDownList("Good_attr[".$item->attribute_id."][single]", $attr_id, CHtml::listData(AttributeVariant::model()->findAll(array("condition" => "attribute_id=".$item->attribute_id,"order" => "sort ASC")), 'id', $item->attribute->type->code.'_value'),array('class'=> 'select2',"empty" => "Не задано")); ?>
				<? endif; ?>
			<? elseif($item->attribute->type->code == "text"):?>
				<?php echo Chtml::textArea("Good_attr[".$item->attribute_id."]",$attr_id,array('rows'=>4)); ?>
			<? else: ?>
				<?php echo Chtml::textField("Good_attr[".$item->attribute_id."]",$attr_id,array('maxlength'=>255)); ?>
			<?endif;?>
		</div>
	<? endforeach; ?>
	</div>
	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Добавить' : 'Сохранить'); ?>
		<input type="button" onclick="$.fancybox.close(); return false;" value="Отменить">
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->