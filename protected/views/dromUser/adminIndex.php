<h1><?=$this->adminMenu["cur"]->name?></h1>
<!-- <a href="<?php echo $this->createUrl('/'.$this->adminMenu["cur"]->code.'/admincreate',array("Interpreter[good_type_id]"=>(isset($_GET["Interpreter"]) && isset($_GET["Interpreter"]["good_type_id"]) )?$_GET["Interpreter"]["good_type_id"]:NULL ))?>" class="ajax-form ajax-create b-butt b-top-butt">Добавить</a> -->
<?php $form=$this->beginWidget('CActiveForm'); ?>
	<table class="b-table" border="1">
		<tr>
			<th style="width: 30px;"><?if($sort_field=="id"):?>
				<a class="b-filter-sort good-sort active <? if($sort_type=='ASC') echo ' up'; ?>" href="#" data-type="<?=(($sort_type == "ASC") ? "DESC" : "ASC")?>" data-field="id"><? echo $labels['id']; ?></a>
			<? else:?>
				<a class="b-filter-sort" href="#" data-field="id" data-type="ASC"><? echo $labels['id']; ?></a>
			<? endif;?></th>
			<th><?if($sort_field=="name"):?>
				<a class="b-filter-sort good-sort active <? if($sort_type=='ASC') echo ' up'; ?>" href="#" data-type="<?=(($sort_type == "ASC") ? "DESC" : "ASC")?>" data-field="name"><? echo $labels['name']; ?></a>
			<? else:?>
				<a class="b-filter-sort" href="#" data-field="name" data-type="ASC"><? echo $labels['name']; ?></a>
			<? endif;?></th>
			<th><?if($sort_field=="count"):?>
				<a class="b-filter-sort good-sort active <? if($sort_type=='ASC') echo ' up'; ?>" href="#" data-type="<?=(($sort_type == "ASC") ? "DESC" : "ASC")?>" data-field="count"><? echo $labels['count']; ?></a>
			<? else:?>
				<a class="b-filter-sort" href="#" data-field="count" data-type="DESC"><? echo $labels['count']; ?></a>
			<? endif;?></th>
			<th><?if($sort_field=="city"):?>
				<a class="b-filter-sort good-sort active <? if($sort_type=='ASC') echo ' up'; ?>" href="#" data-type="<?=(($sort_type == "ASC") ? "DESC" : "ASC")?>" data-field="city"><? echo $labels['city']; ?></a>
			<? else:?>
				<a class="b-filter-sort" href="#" data-field="city" data-type="ASC"><? echo $labels['city']; ?></a>
			<? endif;?></th>
			<th style="width: 150px;">Действия</th>
		</tr>
		<tr class="b-filter">
			<td>
				<input type="hidden" name="sort_field" value="<?=$sort_field?>">
				<input type="hidden" name="sort_type" value="<?=$sort_type?>">
			</td>
			<td><?php echo CHtml::activeTextField($filter, 'name'); ?></td>
			<td><?php echo CHtml::activeTextField($filter, 'count'); ?></td>
			<td><?php echo CHtml::activeTextField($filter, 'city'); ?></td>
			<td><a href="#" class="b-clear-filter">Сбросить фильтр</a></td>
		</tr>
		<? if( count($inter) || count($service) ): ?>
			<? foreach ($inter as $i => $item): ?>
				<tr<?if(isset($_GET["id"]) && $item->id == $_GET["id"]):?> class="b-refresh"<?endif;?>>
					<td><?=$item->id?></td>
					<td class="align-left"><a href="http://baza.drom.ru/user/<?=$item->id?>/" target="_blank"><?=$item->name?></a></td>
					<td class="align-left"><?=$item->count?></td>
					<td class="align-left"><?=$item->city?></td>
					<td class="b-tool-cont">
						<!-- <a href="<?php echo Yii::app()->createUrl('/'.$this->adminMenu["cur"]->code.'/adminupdate',array('id'=>$item->id))?>" class="ajax-form ajax-update b-tool b-tool-update" title="Редактировать <?=$this->adminMenu["cur"]->vin_name?>"></a> -->
						<a href="<?php echo Yii::app()->createUrl('/'.$this->adminMenu["cur"]->code.'/adminindex',array('partial'=>true,'delete'=>$item->id))?>" class="ajax-form ajax-delete b-tool b-tool-delete" data-warning="Вы действительно хотите удалить <?=$this->adminMenu["cur"]->vin_name?> &quot;<?=$item->name?>&quot;?" title="Удалить <?=$this->adminMenu["cur"]->vin_name?>"></a>
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