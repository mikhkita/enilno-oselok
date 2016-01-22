<div class="form b-good-form clearfix">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'good-edit-form',
	'enableAjaxValidation'=>false,
	'htmlOptions' => array(
        "data-type" => ((isset($json_type))?"json":"none")
    ),
)); ?>
	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Добавить' : 'Сохранить'); ?>
		<input type="button" onclick="$.fancybox.close(); return false;" value="Отменить">
	</div>
	<?php echo $form->errorSummary($model); ?>
	<div class="clearfix">
		<? if(isset($view_fields) && count($view_fields)): ?>
		<div class="clearfix left b-left-column">
		<? endif; ?>
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
								<?php echo Chtml::dropDownList("Good_attr[".$item->attribute_id."]", "", (($only_cities)?(array("-"=>"Удалить города")):(array())) + CHtml::listData(AttributeVariant::model()->with("variant")->findAll(array("condition" => "attribute_id=".$item->attribute_id,"order" => "variant.sort ASC")), 'variant_id', 'value'),array('class'=> 'select2','multiple' => 'true', 'options' => $selected, "required"=>($item->attribute->required))); ?>	
						<? else: ?>
							<?php echo Chtml::dropDownList("Good_attr[".$item->attribute_id."][single]", $attr_id, CHtml::listData(AttributeVariant::model()->with("variant")->findAll(array("condition" => "attribute_id=".$item->attribute_id,"order" => "variant.sort ASC")), 'variant_id', 'value'),array('class'=> 'select2',"empty" => "Не задано", "required"=>($item->attribute->required))); ?>
						<? endif; ?>
					<? elseif($item->attribute->type->code == "text"):?>
						<?php echo Chtml::textArea("Good_attr[".$item->attribute_id."]",$attr_id,array('rows'=>4,"required"=>($item->attribute->required))); ?>
					<? elseif($item->attribute->type->code == "int"):?>
						<?php echo Chtml::numberField("Good_attr[".$item->attribute_id."]",$attr_id,array('maxlength'=>255,"required"=>($item->attribute->required))); ?>
					<? else: ?>
						<?php echo Chtml::textField("Good_attr[".$item->attribute_id."]",$attr_id,array('maxlength'=>255,"required"=>($item->attribute->required))); ?>
					<?endif;?>
				</div>
			<?endif;?>
		<? endforeach; ?>
		<? if(isset($view_fields) && count($view_fields)): ?>
		</div>
		<div class="right b-right-column clearfix">
			<table class="b-params-table">
				<? foreach ($view_fields as $field): ?>
				<tr>
					<td></td>
					<td></td>
				</tr>
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