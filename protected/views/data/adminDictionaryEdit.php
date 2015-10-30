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
						<input type="hidden" name="Values[<?=$i?>][attribute_1]" value="<?=$item->variant_id?>">
						<textarea name="Values[<?=$i?>][value]"><?=((isset($values[$item->variant_id]))?$values[$item->variant_id]:"")?></textarea>
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
<a href="" data-var="<?php echo Yii::app()->createUrl('/data/adminvarsupdate',array('none'=>'1','id'=>''))?>" data-table="<?=(Yii::app()->request->hostInfo."/admin/data/tableedit?id=")?>" class="ajax-form ajax-update b-tool b-tool-update hidden" id="b-update-button" ></a>