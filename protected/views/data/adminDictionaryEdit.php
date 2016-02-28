<h1><?=$model->name?></h1>
<a href="<?php echo $this->createUrl('/'.$this->adminMenu["cur"]->code.'/admindictionary')?>" class="b-link-back">Назад</a>
<?php $form=$this->beginWidget('CActiveForm', array( 'id'=>'b-matrix-form' )); ?>
<a href="#" onclick="$(this).parents('form').submit(); return false;" class="b-butt b-top-butt">Сохранить</a>
	<table class="b-table" border="1">
		<? if( count($data) ): ?>
			<? foreach ($data as $i => $item): ?>
				<tr>
					<td class="tleft"><?=$item->value?></td>
					<td class="b-table-td-editable" style="width: 40%">
						<input type="hidden" name="Values[<?=$i?>][attribute_1]" value="<?=$item->variant_id?>">
						<textarea class="visual-inter" name="Values[<?=$i?>][value]" data-href="<?=$this->createUrl('/interpreter/adminvisual')?>" data-block="#b-inter-visual-<?=$i?>"><?=((isset($values[$item->variant_id]))?$values[$item->variant_id]:"")?></textarea>
					</td>
					<td class="b-inter-visual-td">
						<div class="b-inter-visual" id="b-inter-visual-<?=$i?>">
							<?=((isset($values[$item->variant_id]))?$this->visualInter($values[$item->variant_id]):"")?>
						</div>
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