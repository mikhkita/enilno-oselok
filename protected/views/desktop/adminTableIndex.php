<h1>Таблица "<?=$table->name?>"</h1>
<div class="b-link-back">
	<a href="<?=$this->createUrl('/'.$this->adminMenu["cur"]->code.'/adminindex',array("id"=>$table->folder_id))?>">Назад</a>
</div>
<a href="<?php echo $this->createUrl('/'.$this->adminMenu["cur"]->code.'/admintablerowcreate',array("table_id"=>$table->id))?>" class="ajax-form ajax-create b-butt b-top-butt">Добавить</a>
<?php $form=$this->beginWidget('CActiveForm'); ?>
	<table class="b-table" border="1">
		<tr>
			<? foreach ($table->cols as $col): ?>
			<th><? echo $col->name; ?></th>
			<? endforeach; ?>
			<th style="width: 150px;">Действия</th>
		</tr>
		<? if( count($table->rows) ): ?>
			<? foreach ($table->rows as $row): ?>
			<tr>
				<? $cells_assoc = $this->getAssoc($row->cells,"col_id"); ?>
				<? foreach ($table->cols as $col): ?>
					<td style="text-align: left;">
					<? if(isset($cells_assoc[$col->id])): ?>
						<? if( $col->type->class == "url" ): ?>
							<a href="<?=$cells_assoc[$col->id]->value?>" target="_blank"><?=$this->cutText($cells_assoc[$col->id]->value,50)?></a>
						<? else: ?>
							<?=$cells_assoc[$col->id]->value?>
						<? endif; ?>
					<? endif; ?>
					</td>
				<? endforeach; ?>
				<td class="b-tool-cont">
					<a href="<?php echo Yii::app()->createUrl('/'.$this->adminMenu["cur"]->code.'/admintablerowupdate',array('id'=>$row->id,'table_id'=>$table->id))?>" class="ajax-form ajax-update b-tool b-tool-update" title="Редактировать строку"></a>
					<a href="<?php echo Yii::app()->createUrl('/'.$this->adminMenu["cur"]->code.'/admintablerowdelete',array('id'=>$row->id,'table_id'=>$table->id))?>" class="ajax-form ajax-delete b-tool b-tool-delete" data-warning="Вы действительно хотите удалить строку?" title="Удалить строку"></a>
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