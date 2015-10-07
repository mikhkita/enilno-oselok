<h1>Переменные</h1>
<a href="<?php echo $this->createUrl('/'.$this->adminMenu["cur"]->code.'/adminindex')?>" class="b-link-back">Назад</a>
<a href="<?php echo $this->createUrl('/'.$this->adminMenu["cur"]->code.'/adminvarscreate')?>" class="ajax-form ajax-create b-butt b-top-butt">Добавить</a>
<?php $form=$this->beginWidget('CActiveForm'); ?>
	<table class="b-table" border="1">
		<tr>
			<th><? echo $labels['name']; ?></th>
			<th><? echo $labels['value']; ?></th>
			<th style="width: 150px;">Действия</th>
		</tr>
		<tr class="b-filter">
			<td><?php echo CHtml::activeTextField($filter, 'name'); ?></td>
			<td><?php echo CHtml::activeTextField($filter, 'value'); ?></td>
			<td><a href="#" class="b-clear-filter">Сбросить фильтр</a></td>
		</tr>
		<? if( count($data) ): ?>
			<? foreach ($data as $i => $item): ?>
				<tr<?if(isset($_GET["id"]) && $item->name == $_GET["id"]):?> class="b-refresh"<?endif;?>>
					<td class="align-left"><?=$item->name?></td>
					<td class="align-left"><?=$this->replaceToBr($this->cutText($item->value))?></td>
					<td class="b-tool-cont">
						<a href="<?php echo Yii::app()->createUrl('/'.$this->adminMenu["cur"]->code.'/adminvarsupdate',array('id'=>$item->name))?>" class="ajax-form ajax-update b-tool b-tool-update" title="Параметры списока"></a>
						<a href="<?php echo Yii::app()->createUrl('/'.$this->adminMenu["cur"]->code.'/adminvarsdelete',array('id'=>$item->name))?>" class="ajax-form ajax-delete b-tool b-tool-delete" data-warning="Вы действительно хотите удалить список &quot;<?=$item->name?>&quot;?" title="Удалить список"></a>
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