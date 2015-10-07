<h1><?=$this->adminMenu["cur"]->name?></h1>
<a href="<?php echo $this->createUrl('/'.$this->adminMenu["cur"]->code.'/admincreate')?>" class="ajax-form ajax-create b-butt b-top-butt">Добавить</a>
<?php $form=$this->beginWidget('CActiveForm'); ?>
	<table class="b-table" border="1">
		<tr>
			<th><? echo $labels['name']; ?></th>
			<th><? echo $labels['value']; ?></th>
			<th style="width: 150px;">Действия</th>
		</tr>
		<tr class="b-filter">
			<td><?php echo CHtml::activeTextField($filter, 'name'); ?></td>
			<td></td>
			<td><a href="#" class="b-clear-filter">Сбросить фильтр</a></td>
		</tr>
		<? if( count($data) ): ?>
			<? foreach ($data as $i => $item): ?>
				<tr<?if(isset($_GET["id"]) && $item->id == $_GET["id"]):?> class="b-refresh"<?endif;?>>
					<td class="align-left"><?=$item->name?></td>
					<td class="align-left"><? if($item->link): ?><a href="<?=$item->value?>" target="_blank"><?endif;?><?=$item->value?><? if($item->link): ?></a><?endif;?></td>
					<td class="b-tool-cont">
						<? if($this->checkAccess($item,true)): ?><a href="<?php echo Yii::app()->createUrl('/'.$this->adminMenu["cur"]->code.'/adminupdate',array('id'=>$item->id))?>" class="ajax-form ajax-update b-tool b-tool-update" title="Редактировать <?=$this->adminMenu["cur"]->vin_name?>"></a><? endif; ?>
						<? if($this->checkAccess($item,true)): ?><a href="<?php echo Yii::app()->createUrl('/'.$this->adminMenu["cur"]->code.'/admindelete',array('id'=>$item->id))?>" class="ajax-form ajax-delete b-tool b-tool-delete" data-warning="Вы действительно хотите удалить <?=$this->adminMenu["cur"]->vin_name?> &quot;<?=$item->name?>&quot;?" title="Удалить <?=$this->adminMenu["cur"]->vin_name?>"></a><? endif; ?>
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