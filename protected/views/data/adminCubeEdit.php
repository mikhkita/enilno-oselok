<h1><?=$model->name?></h1>
<div class="b-link-back">
	<a href="<?php echo $this->createUrl('/'.$this->adminMenu["cur"]->code.'/admincube')?>">Назад</a>
	Кол-во строк: <?php echo CHtml::dropDownList('b-textarea-rows', 2, array(2=>2,3=>3,4=>4,5=>5,6=>6,7=>7)); ?>
</div>
<?php $form=$this->beginWidget('CActiveForm', array( 'id'=>'b-matrix-form' )); ?>
<a href="#" onclick="$(this).parents('form').submit(); return false;" class="b-butt b-top-butt">Сохранить</a>
<? $index = 0; ?>
<?php echo CHtml::dropDownList('b-select-z', $z[0]->id, CHtml::listData($z, 'id', 'value'), array('class'=>'b-select-z')); ?>
<div class="b-tables">
<? foreach ($z as $k => $itemZ): ?>
	<table class="b-table b-data" data-id="<?=$itemZ->id?>" border="1">
		<tr>
			<td>Y\X</td>
			<? foreach ($x as $i => $itemX): ?>
			<th><?=$itemX->value?></th>
			<? endforeach; ?>
		</tr>
		<? foreach ($y as $i => $itemY): ?>
			<tr>
				<th class="tleft"><?=$itemY->value?></th>
				<? foreach ($x as $j => $itemX): ?>
					<td class="b-table-td-editable">
						<input type="hidden" name="Values[<?=$index?>][attribute_1]" value="<?=$itemX->id?>">
						<input type="hidden" name="Values[<?=$index?>][attribute_2]" value="<?=$itemY->id?>">
						<input type="hidden" name="Values[<?=$index?>][attribute_3]" value="<?=$itemZ->id?>">
						<textarea name="Values[<?=$index?>][value]"><?=((isset($values[$itemX->id][$itemY->id][$itemZ->id]))?$values[$itemX->id][$itemY->id][$itemZ->id]:"")?></textarea>
					</td>
					<? $index++; ?>
				<? endforeach; ?>
			</tr>
		<? endforeach; ?>
	</table>
<? endforeach; ?>
</div>
<?php $this->endWidget(); ?>