<h1>Списки</h1>
<a href="<?php echo $this->createUrl('/'.$this->adminMenu["cur"]->code.'/adminindex')?>" class="b-link-back">Назад</a>
<a href="<?php echo $this->createUrl('/'.$this->adminMenu["cur"]->code.'/admindictionarycreate')?>" class="ajax-form ajax-create b-butt b-top-butt">Добавить</a>
<?php $form=$this->beginWidget('CActiveForm'); ?>
	<table class="b-table" border="1">
		<tr>
			<th style="width: 30px;"><? echo $labels['id']; ?></th>
			<th><? echo $labels['name']; ?></th>
			<th><? echo $labels['attribute_id_1']; ?></th>
			<th style="width: 150px;">Действия</th>
		</tr>
		<tr class="b-filter">
			<td></td>
			<td><?php echo CHtml::activeTextField($filter, 'name'); ?></td>
			<td><?php echo CHtml::activeDropDownList($filter, 'attribute_id_1', array(""=>"Все атрибуты")+CHtml::listData(Attribute::model()->findAll(array('order'=>'name ASC','condition'=>'list=1')), 'id', 'name')); ?></td>
			<td><a href="#" class="b-clear-filter">Сбросить фильтр</a></td>
		</tr>
		<? if( count($data) ): ?>
			<? foreach ($data as $i => $item): ?>
				<tr<?if(isset($_GET["id"]) && $item->id == $_GET["id"]):?> class="b-refresh"<?endif;?>>
					<td><?=$item->id?></td>
					<td class="align-left"><a href="<?php echo Yii::app()->createUrl('/'.$this->adminMenu["cur"]->code.'/admindictionaryedit',array('id'=>$item->id))?>"><?=$item->name?></a></td>
					<td><?=isset($item->attribute_1->name)?$item->attribute_1->name:""?></td>
					<td class="b-tool-cont">
						<a href="<?php echo Yii::app()->createUrl('/'.$this->adminMenu["cur"]->code.'/admindictionaryupdate',array('id'=>$item->id))?>" class="ajax-form ajax-update b-tool b-tool-update" title="Параметры списока"></a>
						<a href="<?php echo Yii::app()->createUrl('/'.$this->adminMenu["cur"]->code.'/admindictionaryedit',array('id'=>$item->id))?>" class="b-tool b-tool-list" title="Редактировать список"></a>
						<a href="<?php echo Yii::app()->createUrl('/'.$this->adminMenu["cur"]->code.'/admindictionarydelete',array('id'=>$item->id))?>" class="ajax-form ajax-delete b-tool b-tool-delete" data-warning="Вы действительно хотите удалить список &quot;<?=$item->name?>&quot;?" title="Удалить список"></a>
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