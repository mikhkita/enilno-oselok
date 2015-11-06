<div class="form b-desktop-table-form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'table-form',
	'enableAjaxValidation'=>false,
)); ?>

	<?php echo $form->errorSummary($table); ?>
	<div class="clearfix">
	
	<? foreach ($table->cols as $col): ?>
		<? $val = (isset($cells[$col->id]))?$cells[$col->id]->value:""; ?>
		<div class="row">
			<label><?=$col->name?></label>
			<? if($col->type->code == "text"):?>
				<?php echo Chtml::textArea("rows[".$col->id."]",$val,array('rows'=>4,"required"=>($col->required))); ?>
			<? elseif($col->type->code == "int"):?>
				<?php echo Chtml::numberField("rows[".$col->id."]",$val,array('maxlength'=>255,"required"=>($col->required))); ?>
			<? else: ?>
				<?php echo Chtml::textField("rows[".$col->id."]",$val,array('maxlength'=>255,"required"=>($col->required),"class"=>$col->type->class)); ?>
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