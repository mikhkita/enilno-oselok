<!-- <h1><?=$this->adminMenu["cur"]->name?></h1> -->
<h1>Задачи</h1>
<!-- <a href="<?php echo $this->createUrl('/'.$this->adminMenu["cur"]->code.'/admincreate')?>" class="ajax-form ajax-create b-butt b-top-butt">Добавить</a> -->
<?php $form=$this->beginWidget('CActiveForm'); ?>
	<table class="b-table" border="1">
		<tr>
			<th style="width: 30px;"><? echo $labels['id']; ?></th>
			<th style="width: 80px;">Тип товара</th>
			<th style="width: 55px;">Код</th>
			<th><? echo $labels['action_id']; ?></th>
			<!-- <th style="width: 150px;"></th> -->
		</tr>
		<? if( count($data) ): ?>
			<? foreach ($data as $i => $item): ?>
				<tr<?if(isset($_GET["id"]) && $item->id == $_GET["id"]):?> class="b-refresh"<?endif;?>>
					<td><?=$item->id?></td>
					<td class="align-left"><?=$item->good_type?></td>
					<td class="align-left"><? if( isset($item->good) ): ?><?=$item->good->code?><? endif; ?></td>
					<td class="align-left">
						<? if( in_array($item->action_id, array(1,5)) ): ?>
							<a href="<?php echo Yii::app()->createUrl('/good/adminphoto',array('id'=>$item->good->id))?>" target="_blank"><?=$item->name?></a>
						<? elseif( in_array($item->action_id, array(2,3,4)) ): ?>
							<a href="<?php echo Yii::app()->createUrl('/good/adminupdate',array('id'=>$item->good->id,'good_type_id' => $item->good->good_type_id, "attributes" => $item->data->ids, "to_task" => '1' ))?>" class="ajax-form ajax-update"><?=($item->name." (".$item->data->names.")")?></a>
						<? elseif( $item->action_id == 6 ): ?>
							<a href="<?=Advert::getUrl(2047, $item->data)?>" target="_blank"><?=($item->name." (".Advert::getUrl(2047, $item->data).")")?></a>
						<? elseif( $item->action_id == 7 ): ?>
							<a href="<?php echo Yii::app()->createUrl('/advert/adminindex',array('active' => 0, 'Place' => Controller::getIds(Place::model()->findAll("category_id = '2047'"), 'id')))?>" target="_blank"><?=($item->name." (".$item->data.")")?></a>
						<? endif; ?>
					</td>
					<!-- <td class="b-tool-cont">
						<a href="<?php echo Yii::app()->createUrl('/'.$this->adminMenu["cur"]->code.'/adminupdate',array('id'=>$item->id))?>" class="ajax-form ajax-update b-tool b-tool-update" title="Редактировать <?=$this->adminMenu["cur"]->vin_name?>"></a>
						<a href="<?php echo Yii::app()->createUrl('/'.$this->adminMenu["cur"]->code.'/admindelete',array('id'=>$item->id))?>" class="ajax-form ajax-delete b-tool b-tool-delete" title="Удалить <?=$this->adminMenu["cur"]->vin_name?>"></a>
					</td> -->
				</tr>
			<? endforeach; ?>
		<? else: ?>
			<tr>
				<td colspan=10>Пусто</td>
			</tr>
		<? endif; ?>
	</table>
<? if($get_next): ?>
	<script> $(".b-table .ajax-update").eq(0).click(); </script>
<? endif; ?>
<?php $this->endWidget(); ?>