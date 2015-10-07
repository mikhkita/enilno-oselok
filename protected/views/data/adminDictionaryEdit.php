<h1><?=$model->name?></h1>
<a href="<?php echo $this->createUrl('/'.$this->adminMenu["cur"]->code.'/admindictionary')?>" class="b-link-back">Назад</a>
<?php $form=$this->beginWidget('CActiveForm', array( 'id'=>'b-matrix-form' )); ?>
<a href="#" onclick="$(this).parents('form').submit(); return false;" class="b-butt b-top-butt">Сохранить</a>
	<table class="b-table" border="1">
		<? if( count($data) ): ?>
			<? foreach ($data as $i => $item): ?>
				<tr>
					<td class="tleft"><?=$item->value?></td>
					<td class="b-table-td-editable">
						<input type="hidden" name="Values[<?=$i?>][attribute_1]" value="<?=$item->id?>">
						<input type="text" name="Values[<?=$i?>][value]" value="<?=((isset($values[$item->id]))?$values[$item->id]:"")?>">
					</td>
				</tr>
			<? endforeach; ?>
		<? else: ?>
			<tr>
				<td colspan=10>Пусто</td>
			</tr>
		<? endif; ?>
	</table>
<?php $this->endWidget(); ?>