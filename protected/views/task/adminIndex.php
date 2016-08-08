<!-- <h1><?=$this->adminMenu["cur"]->name?></h1> -->
<h1>Задачи</h1>
<? if($this->user->usr_id != 10 && Yii::app()->params["site"] == "koleso"): ?>
	<? if( !isset($_GET["user_id"]) ): ?>
		<a href="<?php echo $this->createUrl('/'.$this->adminMenu["cur"]->code.'/adminindex', array("user_id" => 10))?>" class="b-butt b-top-butt">Задания Сергея</a>
	<? else: ?>
		<a href="<?php echo $this->createUrl('/'.$this->adminMenu["cur"]->code.'/adminindex')?>" class="b-butt b-top-butt">Мои задания</a>
	<? endif; ?>
<? endif; ?>
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
		<? $prevId = 0; ?>
			<? foreach ($data as $i => $item): ?>
				<tr id="id-<?=$item->id?>" class="<?if( in_array($item->action_id, array(2,3,4,8)) ):?>b-ajax-tr<?endif;?><?if(isset($_GET["id"]) && $item->id == $_GET["id"]):?> b-refresh<?endif;?>">
					<td><?=$item->id?></td>
					<td class="align-left"><?=$item->good_type?></td>
					<td class="align-left"><? if( isset($item->good) ): ?><?=$item->good->code?><? endif; ?></td>
					<td class="align-left">
						<? if( in_array($item->action_id, array(1,5)) ): ?>
							<a href="<?php echo Yii::app()->createUrl('/good/adminphoto',array('id'=>$item->good->id))?>" target="_blank"><?=$item->name?></a>
						<? elseif( in_array($item->action_id, array(2,3,4)) ): ?>
							<a href="<?php echo Yii::app()->createUrl('/good/adminupdate',array('id'=>$item->good->id,'good_type_id' => $item->good->good_type_id, "attributes" => $item->data->ids, "to_task" => $prevId ))?>" class="ajax-form ajax-update"><?=($item->name." (".$item->data->names.")")?></a>
						<? elseif( $item->action_id == 6 ): ?>
							<a href="<?=Advert::getUrl(2047, $item->data)?>" target="_blank"><?=($item->name." (".Advert::getUrl(2047, $item->data).")")?></a>
						<? elseif( $item->action_id == 7 ): ?>
							<a href="<?php echo Yii::app()->createUrl('/advert/adminindex',array('active' => 0, 'Place' => Controller::getIds(Place::model()->findAll("category_id = '2047'"), 'id')))?>" target="_blank"><?=($item->name." (".$item->data.")")?></a>
						<? elseif( $item->action_id == 8 ): ?>
							<a href="<?php echo $this->createUrl('/advert/admintitleedit', array('advert_id'=> $item->data, 'to_task' => $prevId))?>" class="ajax-form ajax-update"><?=$item->name?></a>
						<? endif; ?>
					</td>
					<!-- <td class="b-tool-cont">
						<a href="<?php echo Yii::app()->createUrl('/'.$this->adminMenu["cur"]->code.'/adminupdate',array('id'=>$item->id))?>" class="ajax-form ajax-update b-tool b-tool-update" title="Редактировать <?=$this->adminMenu["cur"]->vin_name?>"></a>
						<a href="<?php echo Yii::app()->createUrl('/'.$this->adminMenu["cur"]->code.'/admindelete',array('id'=>$item->id))?>" class="ajax-form ajax-delete b-tool b-tool-delete" title="Удалить <?=$this->adminMenu["cur"]->vin_name?>"></a>
					</td> -->
				</tr>
				<? $prevId = $item->id; ?>
			<? endforeach; ?>
		<? else: ?>
			<tr>
				<td colspan=10>Пусто</td>
			</tr>
		<? endif; ?>
	</table>
<? if($get_next !== false): ?>
	<script> clickNextTask(<?=$get_next?>); </script>
<? endif; ?>
<?php $this->endWidget(); ?>