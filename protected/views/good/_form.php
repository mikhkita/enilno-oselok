<div class="form b-good-form clearfix" data-href="<?php echo $this->createUrl('/good/admincheckcode',array('good_type_id' => $good_type_id))?>">
<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'good-edit-form',
	'enableAjaxValidation'=>false,
	'htmlOptions' => array(
        "data-type" => ((isset($json_type))?"json":"none")
    ),
)); ?>
	<?if($_GET["to_task"]):?>
		<input type="hidden" name="to_task" value="1">
	<?endif;?>
	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Добавить' : 'Сохранить'); ?>
		<input type="button" onclick="$.fancybox.close(); return false;" value="Отменить">
	</div>
	<?php echo $form->errorSummary($model); ?>
	<div class="clearfix">
		<? if(isset($view_fields) && count($view_fields)): ?>
		<div class="clearfix left b-left-column">
		<? endif; ?>
		<?$ind = 0;?>
		<? foreach ($fields as $item): ?>
			<? if(!$only_cities || $item->attribute->dynamic ): ?>
				<div class="row<?  if($item->attribute->multi): ?> double-row<? endif; ?>">
					<label><?=$item->attribute->name?><?if($item->attribute->required):?> <span style="color: #F00;">*</span><?endif;?></label>
					<? $attr_id = (isset($result[$item->attribute_id]) && $result[$item->attribute_id] != "") ? $result[$item->attribute_id] : ""; if($item->attribute->list): ?>
						<?  if($item->attribute->multi): ?>
							<? $selected = array(); if(!empty($attr_id)) foreach ($attr_id as $multi) $selected[$multi] = array('selected' => 'selected'); ?>
								<?  if($item->attribute->dynamic): ?>
									<a href="#" class="select2-all">Все</a>
									<?  if($cities): ?>
										<? foreach ($cities as $key => $city):?>
											<!-- <a href="#" class="select-city-group" data-ids="<?=$city->ids?>"><?=$city->name?></a> -->
										<? endforeach; ?>
									<?endif;?>
									<a href="#" class="select2-none right">Сбросить</a>
								<? endif; ?>
								<?php echo Chtml::dropDownList("Good_attr[".$item->attribute_id."]", "", (($only_cities)?(array("-"=>"Удалить города")):(array())) + CHtml::listData(AttributeVariant::model()->with("variant")->findAll(array("condition" => "attribute_id=".$item->attribute_id,"order" => "variant.sort ASC")), 'variant_id', 'value'),array('class'=> 'select2','multiple' => 'true', 'options' => $selected)); ?>	
						<? else: ?>
							<?php echo Chtml::dropDownList("Good_attr[".$item->attribute_id."][single]", $attr_id, (($only_cities)?(array("-"=>"Нет")):(array())) + $dropdown[$item->attribute_id],array('class'=> 'select2',"empty" => "Не задано")); ?>
						<? endif; ?>
					<? elseif($item->attribute->type->code == "text"):?>
						<?php echo Chtml::textArea("Good_attr[".$item->attribute_id."]",$attr_id,array('rows'=>4,"required"=>($item->attribute_id==3), "disabled" => (!$model->isNewRecord && $item->attribute_id==3 && 0) )); ?>
					<? elseif($item->attribute->type->code == "int"):?>
						<?php echo Chtml::numberField("Good_attr[".$item->attribute_id."]",$attr_id,array('maxlength'=>255,"required"=>($item->attribute_id==3), "disabled" => (!$model->isNewRecord && $item->attribute_id==3 && 0) )); ?>
					<? else: ?>
						<?php echo Chtml::textField("Good_attr[".$item->attribute_id."]",$attr_id,array('maxlength'=>255,"required"=>($item->attribute_id==3), "disabled" => (!$model->isNewRecord && $item->attribute_id==3 && 0) )); ?>
					<?endif;?>
				</div>
			<?endif;?>
		<? endforeach; ?>
		<? if(isset($view_fields) && count($view_fields)): ?>
		</div>
		<div class="right b-right-column clearfix">
			<table class="b-params-table b-table" border="1">
				<tr>
					<th>Параметр</th>
					<th>Значение</th>
				</tr>
				<? $fields_assoc = $model->fields_assoc; ?>
				<? foreach ($view_fields as $field): ?>
					<? $item = (isset($fields_assoc[$field->attribute_id]) && $fields_assoc[$field->attribute_id] != "") ? $fields_assoc[$field->attribute_id] : NULL; ?>
					<? if( $item ): ?>
						<? if( is_array($item) ): ?>
							<tr>
								<td><?=$field->attribute->name?></td>
								<td>
								<? foreach ($item as $i => $value): ?>
									<span><?=$value->value?></span>
								<? endforeach; ?>
								</td>
							</tr>
						<? else: ?>
							<tr>
								<td><?=$field->attribute->name?></td>
								<td><?=$item->value?></td>
							</tr>
						<? endif; ?>
					<? endif; ?>
				<? endforeach; ?>
			</table>
		</div>
		<? endif; ?>
	</div>
	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Добавить' : 'Сохранить'); ?>
		<input type="button" onclick="$.fancybox.close(); return false;" value="Отменить">
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->