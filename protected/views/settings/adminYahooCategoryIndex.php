<h1>Категории</h1>
<? if($back_link): ?>
	<a href="<?=$back_link?>" class="b-link-back">Назад</a>
<? endif; ?>
<a href="<?php echo $this->createUrl('/'.$this->adminMenu["cur"]->code.'/adminyahoocategorycreate')?>" class="ajax-form ajax-create b-butt b-top-butt">Добавить</a>
<?php $form=$this->beginWidget('CActiveForm'); ?>
	<table class="b-table" border="1">
		<tr>
			<th style="width: 30px;"><? echo $labels['id']; ?></th>
			<th><? echo $labels['name']; ?></th>
			<th><? echo $labels['code']; ?></th>
			<th><? echo $labels['max_price']; ?></th>
			<th style="width: 150px;">Действия</th>
		</tr>
		<tr class="b-filter">
			<td></td>
			<td><?php echo CHtml::activeTextField($filter, 'name'); ?></td>
			<td></td>
			<td></td>
			<td><a href="#" class="b-clear-filter">Сбросить фильтр</a></td>
		</tr>
		<? if( count($data) ): ?>
			<? foreach ($data as $i => $item): ?>
				<tr<?if(isset($_GET["id"]) && $item->id == $_GET["id"]):?> class="b-refresh"<?endif;?>>
					<td><?=$item->id?></td>
					<td class="align-left"><?=$item->name?></td>
					<td class="align-left"><?=$item->code?></td>
					<td class="align-left"><?=$item->max_price?></td>
					<td class="b-tool-cont">
						<a href="<?php echo Yii::app()->createUrl('/'.$this->adminMenu["cur"]->code.'/adminyahoocategoryupdate',array('id'=>$item->id))?>" class="ajax-form ajax-update b-tool b-tool-update" title="Редактировать <?=$this->adminMenu["cur"]->vin_name?>"></a>
						<a href="<?php echo Yii::app()->createUrl('/'.$this->adminMenu["cur"]->code.'/adminyahoocategorydelete',array('id'=>$item->id))?>" class="ajax-form ajax-delete b-tool b-tool-delete" data-warning="Вы действительно хотите удалить <?=$this->adminMenu["cur"]->vin_name?> &quot;<?=$item->name?>&quot;?" title="Удалить <?=$this->adminMenu["cur"]->vin_name?>"></a>
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