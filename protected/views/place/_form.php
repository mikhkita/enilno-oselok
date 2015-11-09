<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'faculties-form',
	'enableAjaxValidation'=>false,
	'htmlOptions' => array("data-beforeAjax"=>"attributesAjax"),
)); ?>

	<?php echo $form->errorSummary($model); ?>

	<? if($new): ?>
		<div class="clearfix">
			<div class="row left">
				<?php echo $form->labelEx($model,'category_id'); ?>
				<?php echo $form->DropDownList($model,'category_id',CHtml::listData(Variant::model()->with('attribute')->findAll(array('order'=>'varchar_value ASC','condition'=>'attribute.attribute_id=57')), 'id', 'value'),array("style"=>"width:317px;")); ?>
				<?php echo $form->error($model,'category_id'); ?>
			</div>

			<div class="row left">
				<?php echo $form->labelEx($model,'good_type_id'); ?>
				<?php echo $form->DropDownList($model,'good_type_id',CHtml::listData(GoodType::model()->findAll(array('order'=>'name ASC')), 'id', 'name'),array("style"=>"width:317px;")); ?>
				<?php echo $form->error($model,'good_type_id'); ?>
			</div>
		</div>
	<? else: ?>
		<?php echo $form->hiddenField($model,'category_id'); ?>
		<?php echo $form->hiddenField($model,'good_type_id'); ?>
		<div class="row">
			<ul class="b-add-items">
				<? foreach ($inter as $key => $value): ?>
				<li>
					<p><span><?=$value->code?> (<?=$value->interpreter->name?>)</span><a href="#" class="b-add-remove">Удалить</a></p>
					<input type="hidden" name="inter[<?=$value->interpreter_id?>]" value="<?=$value->code?>">
				</li>
		        <? endforeach; ?>
			</ul>
		</div>

		<div class="row b-add-cont clearfix">
			<input type="text" class="left" name="add-code" id="add-code">
			<select name="add" class="left" id="add-inter">
				<? foreach ($allInter as $key => $value): ?>
					<option value="<?=$value->id?>"><?=$value->name?></option>
		        <? endforeach; ?>
			</select>
			<div class="buttons left">
				<input type="button" id="add-inter-button" data-name="inter" value="Добавить">
			</div>
		</div>
	<? endif; ?>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Добавить' : 'Сохранить'); ?>
		<input type="button" onclick="$.fancybox.close(); return false;" value="Отменить">
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->