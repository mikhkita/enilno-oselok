<h1><?=$model->name?></h1>
<a href="<?php echo $this->createUrl('/'.$this->adminMenu["cur"]->code.'/admintable')?>" class="b-link-back">Назад</a>
<?php $form=$this->beginWidget('CActiveForm', array( 'id'=>'b-matrix-form' )); ?>
<a href="#" onclick="$(this).parents('form').submit(); return false;" class="b-butt b-top-butt">Сохранить</a>
	<table class="b-table b-data" border="1">
		<tr>
			<td>Y\X</td>
			<? foreach ($x as $i => $itemX): ?>
			<th><?=$itemX->value?></th>
			<? endforeach; ?>
		</tr>
		<? $index = 0; ?>
		<? foreach ($y as $i => $itemY): ?>
			<tr>
				<th class="tleft"><?=$itemY->value?></th>
				<? foreach ($x as $j => $itemX): ?>
					<td class="b-table-td-editable">
						<input type="hidden" name="Values[<?=$index?>][attribute_1]" value="<?=$itemX->id?>"/>
						<input type="hidden" name="Values[<?=$index?>][attribute_2]" value="<?=$itemY->id?>"/>
						<textarea name="Values[<?=$index?>][value]"><?=((isset($values[$itemX->id][$itemY->id]))?$values[$itemX->id][$itemY->id]:"")?></textarea>
					</td>
					<? $index++; ?>
				<? endforeach; ?>
			</tr>
		<? endforeach; ?>
	</table>
<?php $this->endWidget(); ?>