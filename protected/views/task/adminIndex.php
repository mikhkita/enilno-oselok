<!-- <h1><?=$this->adminMenu["cur"]->name?></h1> -->
<h1>Задачи</h1>
<!-- <a href="<?php echo $this->createUrl('/'.$this->adminMenu["cur"]->code.'/admincreate')?>" class="ajax-form ajax-create b-butt b-top-butt">Добавить</a> -->
<?php $form=$this->beginWidget('CActiveForm'); ?>
	<table class="b-table" border="1">
		<tr>
			<th style="width: 30px;"><? echo $labels['id']; ?></th>
			<th style="width: 80px;">Тип товара</th>
			<th style="width: 50px;">Код</th>
			<th><? echo $labels['action_id']; ?></th>
			<!-- <th style="width: 150px;"></th> -->
		</tr>
		<? if( count($data) ): ?>
			<? foreach ($data as $i => $item): ?>
				<tr<?if(isset($_GET["id"]) && $item->id == $_GET["id"]):?> class="b-refresh"<?endif;?>>
					<td><?=$item->id?></td>
					<td class="align-left"><?=$item->good_type?></td>
					<td class="align-left"><?=$item->good->code?></td>
					<td class="align-left">
						<? if($item->action_id == 1): ?>
							<a href="<?php echo Yii::app()->createUrl('/good/adminphoto',array('id'=>$item->good->id))?>" target="_blank"><?=$item->name?></a>
						<? elseif( in_array($item->action_id, array(2,3,4)) ): ?>
							<a href="<?php echo Yii::app()->createUrl('/good/adminupdate',array('id'=>$item->id,'good_type_id' => $item->good->good_type_id, "attributes" => $item->data ))?>" class="ajax-form ajax-update"><?=$item->name?></a>
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
<?php $this->endWidget(); ?>