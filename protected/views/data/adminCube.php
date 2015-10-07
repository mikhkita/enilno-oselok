<h1>Трехмерные массивы</h1>
<a href="<?php echo $this->createUrl('/'.$this->adminMenu["cur"]->code.'/adminindex')?>" class="b-link-back">Назад</a>
<a href="<?php echo $this->createUrl('/'.$this->adminMenu["cur"]->code.'/admincubecreate')?>" class="ajax-form ajax-create b-butt b-top-butt">Добавить</a>
<?php $form=$this->beginWidget('CActiveForm'); ?>
	<table class="b-table" border="1">
		<tr>
			<th style="width: 30px;"><? echo $labels['id']; ?></th>
			<th><? echo $labels['name']; ?></th>
			<th><? echo $labels['attribute_id_1']; ?></th>
			<th><? echo $labels['attribute_id_2']; ?></th>
			<th><? echo $labels['attribute_id_3']; ?></th>
			<th style="width: 150px;">Действия</th>
		</tr>
		<tr class="b-filter">
			<td></td>
			<td><?php echo CHtml::activeTextField($filter, 'name'); ?></td>
			<td><?php echo CHtml::activeDropDownList($filter, 'attribute_id_1', array(""=>"Все атрибуты")+CHtml::listData(Attribute::model()->findAll(array('order'=>'name ASC','condition'=>'list=1')), 'id', 'name')); ?></td>
			<td><?php echo CHtml::activeDropDownList($filter, 'attribute_id_2', array(""=>"Все атрибуты")+CHtml::listData(Attribute::model()->findAll(array('order'=>'name ASC','condition'=>'list=1')), 'id', 'name')); ?></td>
			<td><?php echo CHtml::activeDropDownList($filter, 'attribute_id_3', array(""=>"Все атрибуты")+CHtml::listData(Attribute::model()->findAll(array('order'=>'name ASC','condition'=>'list=1')), 'id', 'name')); ?></td>
			<td><a href="#" class="b-clear-filter">Сбросить фильтр</a></td>
		</tr>
		<? if( count($data) ): ?>
			<? foreach ($data as $i => $item): ?>
				<tr<?if(isset($_GET["id"]) && $item->id == $_GET["id"]):?> class="b-refresh"<?endif;?>>
					<td><?=$item->id?></td>
					<td class="align-left"><a href="<?php echo Yii::app()->createUrl('/'.$this->adminMenu["cur"]->code.'/admincubeedit',array('id'=>$item->id))?>"><?=$item->name?></a></td>
					<td><?=(isset($item->attribute_1->name))?$item->attribute_1->name:""?></td>
					<td><?=(isset($item->attribute_2->name))?$item->attribute_2->name:""?></td>
					<td><?=(isset($item->attribute_3->name))?$item->attribute_3->name:""?></td>
					<td class="b-tool-cont">
						<a href="<?php echo Yii::app()->createUrl('/'.$this->adminMenu["cur"]->code.'/admincubeupdate',array('id'=>$item->id))?>" class="ajax-form ajax-update b-tool b-tool-update" title="Параметры массива"></a>
						<a href="<?php echo Yii::app()->createUrl('/'.$this->adminMenu["cur"]->code.'/admincubeedit',array('id'=>$item->id))?>" class="b-tool b-tool-list" title="Редактировать массив"></a>
						<a href="<?php echo Yii::app()->createUrl('/'.$this->adminMenu["cur"]->code.'/admincubedelete',array('id'=>$item->id))?>" class="ajax-form ajax-delete b-tool b-tool-delete" data-warning="Вы действительно хотите удалить массив &quot;<?=$item->name?>&quot;?" title="Удалить массив"></a>
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