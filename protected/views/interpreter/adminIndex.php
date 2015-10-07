<h1><?=$this->adminMenu["cur"]->name?></h1>
<a href="<?php echo $this->createUrl('/'.$this->adminMenu["cur"]->code.'/admincreate')?>" class="ajax-form ajax-create b-butt b-top-butt">Добавить</a>
<?php $form=$this->beginWidget('CActiveForm'); ?>
	<table class="b-table" border="1">
		<tr>
			<th style="width: 30px;"><? echo $labels['id']; ?></th>
			<th><? echo $labels['name']; ?></th>
			<th><? echo $labels['category_id']; ?></th>
			<th><? echo $labels['good_type_id']; ?></th>
			<th><? echo $labels['template']; ?></th>
			<th style="width: 150px;">Действия</th>
		</tr>
		<tr class="b-filter">
			<td></td>
			<td><?php echo CHtml::activeTextField($filter, 'name'); ?></td>
			<td><?php echo CHtml::activeDropDownList($filter, 'category_id', array(""=>"Все категории")+CHtml::listData(Category::model()->findAll(), 'id', 'name')); ?></td>
			<td><?php echo CHtml::activeDropDownList($filter, 'good_type_id', array(""=>"Все типы товаров")+CHtml::listData(GoodType::model()->findAll(), 'id', 'name')); ?></td>
			<td><?php echo CHtml::activeTextField($filter, 'template'); ?></td>
			<td><a href="#" class="b-clear-filter">Сбросить фильтр</a></td>
		</tr>
		<? if( count($data) ): ?>
			<? foreach ($data as $i => $item): ?>
				<tr<?if(isset($_GET["id"]) && $item->id == $_GET["id"]):?> class="b-refresh"<?endif;?>>
					<td><?=$item->id?></td>
					<td class="align-left"><?=$item->name?></td>
					<td class="align-left"><?=$item->category->name?></td>
					<td class="align-left"><?=$item->goodType->name?></td>
					<td class="align-left"><? if($this->checkAccess($item,true)) echo $this->replaceToBr($this->cutText($item->template)); ?></td>
					<td class="b-tool-cont">
						<? if($this->checkAccess($item,true)): ?><a href="<?php echo Yii::app()->createUrl('/'.$this->adminMenu["cur"]->code.'/adminupdate',array('id'=>$item->id))?>" class="ajax-form ajax-update b-tool b-tool-update" title="Редактировать <?=$this->adminMenu["cur"]->vin_name?>"></a><? endif; ?>
						<a href="<?php echo Yii::app()->createUrl('/'.$this->adminMenu["cur"]->code.'/adminpreview',array('id'=>$item->id))?>" class="ajax-form ajax-update b-tool b-tool-list" title="Посмотреть примеры"></a>
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