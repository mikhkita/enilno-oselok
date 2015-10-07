<h1><?=$this->adminMenu["cur"]->name?></h1>
<a href="<?php echo $this->createUrl('/'.$this->adminMenu["cur"]->code.'/admincreate')?>" class="ajax-form ajax-create b-butt b-top-butt">Добавить</a>
<?php $form=$this->beginWidget('CActiveForm'); ?>
	<table class="b-table" border="1" data-warning="Все связанные с атрибутом справочники будут удалены!">
		<tr>
			<th style="width: 30px;"><? echo $labels['id']; ?></th>
			<th><? echo $labels['name']; ?></th>
			<th><? echo $labels['attribute_type_id']; ?></th>
			<th><? echo $labels['multi']; ?></th>
			<th><? echo $labels['list']; ?></th>
			<th style="width: 150px;">Действия</th>
		</tr>
		<tr class="b-filter">
			<td></td>
			<td><?php echo CHtml::activeTextField($filter, 'name'); ?></td>
			<td><?php echo CHtml::activeDropDownList($filter, 'attribute_type_id', array(""=>"Все типы данных")+CHtml::listData(AttributeType::model()->findAll(array('order'=>'id ASC')), 'id', 'name')); ?></td>
			<td></td>
			<td></td>
			<td><a href="#" class="b-clear-filter">Сбросить фильтр</a></td>
		</tr>
		<? if( count($data) ): ?>
			<? foreach ($data as $i => $item): ?>
				<tr<?if(isset($_GET["id"]) && $item->id == $_GET["id"]):?> class="b-refresh"<?endif;?>>
					<td><?=$item->id?></td>
					<td class="align-left"><?=$item->name?></td>
					<td class="align-left"><?=$item->type->name?></td>
					<td class="align-center"><?=(($item->multi)?("<span class='b-circle'></span>"):("-"))?></td>
					<td class="align-center"><?=(($item->list)?("<span class='b-circle'></span>"):("-"))?></td>
					<td class="b-tool-cont">
						<a href="<?php echo Yii::app()->createUrl('/'.$this->adminMenu["cur"]->code.'/adminupdate',array('id'=>$item->id))?>" class="ajax-form ajax-update b-tool b-tool-update" title="Редактировать <?=$this->adminMenu["cur"]->vin_name?>"></a>
						<? if( $item->list ): ?>
							<a href="<?php echo Yii::app()->createUrl('/'.$this->adminMenu["cur"]->code.'/adminedit',array('id'=>$item->id))?>" class="ajax-form ajax-update b-tool b-tool-list" title="Варианты <?=$this->adminMenu["cur"]->rod_name?>"></a>
						<? endif; ?>
						<a href="<?php echo Yii::app()->createUrl('/'.$this->adminMenu["cur"]->code.'/admindelete',array('id'=>$item->id))?>" class="ajax-form ajax-delete b-tool b-tool-delete" data-warning="Вы действительно хотите удалить <?=$this->adminMenu["cur"]->vin_name?> &quot;<?=$item->name?>&quot;?" title="Удалить <?=$this->adminMenu["cur"]->vin_name?>"></a>
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