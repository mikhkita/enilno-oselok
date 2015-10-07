<h1><?=$this->adminMenu["cur"]->name?></h1>
<? if(isset($_GET["archive"])): ?>
	<a href="<?php echo $this->createUrl('/'.$this->adminMenu["cur"]->code.'/adminindex')?>" class="b-link-back">Актуальное</a>
<? else: ?>
	<a href="<?php echo $this->createUrl('/'.$this->adminMenu["cur"]->code.'/adminindex',array('archive'=>1))?>" class="b-link-back">Архив</a>
<? endif; ?>
<a href="<?php echo $this->createUrl('/'.$this->adminMenu["cur"]->code.'/admincreate')?>" class="ajax-form ajax-create b-butt b-top-butt">Добавить</a>
<?php $form=$this->beginWidget('CActiveForm'); ?>
	<table class="b-table b-auction-table" data-url="<?php echo $this->createUrl('/'.$this->adminMenu["cur"]->code.'/adminlive')?>" border="1">
		<tr>
			<th><? echo $labels['code']; ?></th>
			<th style="min-width: 100px;"><? echo $labels['image']; ?></th>
			<th><? echo $labels['name']; ?></th>
			<th style="min-width: 145px;"><? echo $labels['date']; ?></th>
			<th><? echo $labels['current_price']; ?></th>
			<th style="min-width: 130px;"><? echo $labels['state']; ?></th>
			<th><? echo $labels['price']; ?></th>
			<th style="min-width: 150px;">Действия</th>
		</tr>
		<tr class="b-filter">
			<td><?php echo CHtml::activeTextField($filter, 'code'); ?></td>
			<td></td>
			<td><?php echo CHtml::activeTextField($filter, 'name'); ?></td>
			<td><?php echo CHtml::activeTextField($filter, 'date'); ?></td>
			<td><?php echo CHtml::activeTextField($filter, 'current_price'); ?></td>
			<td></td>
			<td><?php echo CHtml::activeTextField($filter, 'price'); ?></td>
			<td><a href="#" class="b-clear-filter">Сбросить фильтр</a></td>
		</tr>
		<? if( count($data) ): ?>
			<? foreach ($data as $i => $item): ?>
				<tr class="<?if($item->state==6):?>b-win<?endif;?><?if(isset($_GET["id"]) && $item->id == $_GET["id"]):?> b-refresh<?endif;?>" data-id="<?=$item->id?>">
					<td><a href="https://injapan.ru/auction/<?=$item->code?>.html" target="_blank"><?=$item->code?></a></td>
					<td class="align-left"><a href="<?=$item->image?>" class="fancy-img"><img src="<?=$item->image?>" class="b-index-img"></a></td>
					<td class="align-left"><?=$this->cutText($item->name,90)?></td>
					<td data-field="date"><?=$item->date?></td>
					<td class="align-left" data-field="current_price"><?=$item->current_price?></td>
					<td data-field="state"><?=Auction::model()->states[$item->state]?></td>
					<td class="align-left"><?=$item->price?></td>
					<td class="b-tool-cont">
						<? if(!isset($_GET["archive"])): ?>
							<a href="<?php echo Yii::app()->createUrl('/'.$this->adminMenu["cur"]->code.'/adminupdate',array('id'=>$item->id))?>" class="ajax-form ajax-update b-tool b-tool-update" title="Редактировать"></a>
							<a href="<?php echo Yii::app()->createUrl('/'.$this->adminMenu["cur"]->code.'/adminrefresh',array('id'=>$item->id))?>" class="ajax-form ajax-refresh b-tool b-tool-refresh" title="Обновить"></a>
							<a href="<?php echo Yii::app()->createUrl('/'.$this->adminMenu["cur"]->code.'/adminarchive',array('id'=>$item->id))?>" class="ajax-form ajax-archive b-tool b-tool-archive" title="В архив"></a>
							<a href="<?php echo Yii::app()->createUrl('/'.$this->adminMenu["cur"]->code.'/admindelete',array('id'=>$item->id))?>" class="ajax-form ajax-delete b-tool b-tool-delete" data-warning="Вы действительно хотите удалить <?=$this->adminMenu["cur"]->vin_name?> &quot;<?=$item->name?>&quot;?" title="Удалить"></a>
						<? else: ?>
							<a href="<?php echo Yii::app()->createUrl('/'.$this->adminMenu["cur"]->code.'/adminarchiveback',array('id'=>$item->id,'archive'=>1))?>" class="ajax-form ajax-archive b-tool b-tool-archive-back" title="Вернуть"></a>
							<a href="<?php echo Yii::app()->createUrl('/'.$this->adminMenu["cur"]->code.'/admindelete',array('id'=>$item->id,'archive'=>1))?>" class="ajax-form ajax-delete b-tool b-tool-delete" data-warning="Вы действительно хотите удалить <?=$this->adminMenu["cur"]->vin_name?> &quot;<?=$item->name?>&quot;?" title="Удалить"></a>
						<? endif; ?>
					</td>
				</tr>
			<? endforeach; ?>
		<? else: ?>
			<tr>
				<td colspan=10>Пусто</td>
			</tr>
		<? endif; ?>
	</table>
<a href="<?php echo $this->createUrl('/'.$this->adminMenu["cur"]->code.'/adminarchiveall')?>" class="right" style="margin-top: 20px; display: block;">Отправить все в архив</a>
<?php $this->endWidget(); ?>