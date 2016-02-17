<h1>Продажи (<?=$name?>)</h1>
<a href="<?=$this->createUrl('/good/adminindex',array('good_type_id'=> $_GET["good_type_id"]))?>" class="b-link-back">Назад</a>
<table class="b-table b-good-table" border="1">
	<tr>
		<? foreach ($labels as $item): ?>
			<th style="min-width: 80px;"><?=$item?></th>
		<? endforeach; ?>
		<th></th>
		<th></th>
	</tr>
	<? if( count($data) ): ?>
		<? foreach ($data as $key => $item): ?>
			<tr>
				<td>
					<div><?=$item->good->fields_assoc[3]->value?></div>
				</td>
				<td >
					<div><?=$item->summ." р."?></div>
				</td>
				<td>
					<div><?=$item->extra." р."?></div>
				</td>
				<td>
					<div><? if($item->date) echo date_format(date_create($item->date), 'd.m.Y');?></div>
				</td>
				<td>
					<div><? if($item->channel_id) echo DesktopTableCell::model()->find("row_id=$item->channel_id")->varchar_value;?></div>				
				</td>
				<td>
					<div><?=$item->city?></div>				
				</td>
				<td>
					<div><?=$item->order_number?></div>				
				</td>
				<td>
					<div><? if($item->tk_id) echo DesktopTableCell::model()->find("row_id=$item->tk_id")->varchar_value;?></div>		
				</td>
				<td>
					<div style="text-align: left;"><?=$item->comment?></div>			
				</td>
				<td>
					<div>&nbsp;</div>			
				</td>
				<td style="min-width: 120px;">
					<div><? if($item->customer_id) echo Customer::model()->findbyPk($item->customer_id)->phone; ?></div>		
				</td>
				<td>
					<a href="<?php echo Yii::app()->createUrl('/good/adminsold',array('id'=>$item->good_id,'good_type_id' => $_GET['good_type_id'],'update' => true));?>" class="ajax-form ajax-update b-tool b-tool-update"></a>
				</td>
				<td>
					<a href="<?php echo Yii::app()->createUrl('/good/adminsaledelete',array('id'=>$item->good_id,'good_type_id' => $_GET['good_type_id']));?>" class="ajax-form ajax-delete b-tool b-tool-delete"></a>
				</td>
			</tr>
		<? endforeach; ?>
	<? else: ?>
		<tr>
			<td colspan="4">Пусто</td>
		</tr>
	<? endif; ?>
</table>