<h1><?=$this->adminMenu["cur"]->name?></h1>
<div class="b-buttons-left clearfix" style="display:none;">
	<a href="<?php echo $this->createUrl('/good/updateprices')?>" class="ajax-update-prices b-butt">Обновить цены</a>
	<a href="<?php echo $this->createUrl('/good/updateauctionlinks')?>" class="ajax-update-prices b-butt">Обновить ссылки с торгом</a>
</div>
<? if( $this->access("good", "change") ): ?>
	<div class="b-top-butt clearfix">
		<a href="<?php echo $this->createUrl('/'.$this->adminMenu["cur"]->code.'/admincreate')?>" class="ajax-form ajax-create b-butt">Добавить</a>
	</div>
<? endif; ?>
<?php $form=$this->beginWidget('CActiveForm'); ?>
	<table class="b-table" border="1">
		<tr>
			<th style="width: 30px;"><? echo $labels['id']; ?></th>
			<th><? echo $labels['name']; ?></th>
			<? if( $this->access("good", "change") ): ?>
				<th style="width: 150px;">Действия</th>
			<? endif; ?>
		</tr>
		<tr class="b-filter">
			<td></td>
			<td><?php echo CHtml::activeTextField($filter, 'name'); ?></td>
			<? if( $this->access("good", "change") ): ?>
				<td><a href="#" class="b-clear-filter">Сбросить фильтр</a></td>
			<? endif; ?>
		</tr>
		<? if( count($data) ): ?>
			<? foreach ($data as $i => $item): ?>
				<tr<?if(isset($_GET["id"]) && $item->id == $_GET["id"]):?> class="b-refresh"<?endif;?>>
					<td><?=$item->id?></td>
					<td class="align-left"><a href="<?php echo $this->createUrl('/good/adminindex',array('good_type_id'=>$item->id))?>"><?=$item->name?></a></td>
					<? if( $this->access("good", "change") ): ?>
						<td class="b-tool-cont">
							<a href="<?php echo Yii::app()->createUrl('/'.$this->adminMenu["cur"]->code.'/adminupdate',array('id'=>$item->id))?>" class="ajax-form ajax-update b-tool b-tool-update" title="Редактировать <?=$this->adminMenu["cur"]->vin_name?>"></a>
							<a href="<?php echo Yii::app()->createUrl('/'.$this->adminMenu["cur"]->code.'/admincodedel',array('id'=>$item->id))?>" class="ajax-form ajax-update b-tool b-tool-list" title="Удаление по списку"></a>
							<!-- <a href="<?php echo Yii::app()->createUrl('/'.$this->adminMenu["cur"]->code.'/admindelete',array('id'=>$item->id))?>" class="ajax-form ajax-delete b-tool b-tool-delete" title="Удалить <?=$this->adminMenu["cur"]->vin_name?>"></a> -->
						</td>
					<? endif; ?>
				</tr>
			<? endforeach; ?>			
		<? else: ?>
			<tr>
				<td colspan=10>Пусто</td>
			</tr>
		<? endif; ?>
	</table>
<?php $this->endWidget(); ?>