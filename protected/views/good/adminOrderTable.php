<h1>Заказы</h1>
<table class="b-table b-sale-table b-good-table" border="1">
	<tr>
		<th style="min-width: 52px;">Коды товаров</th>
		<? foreach ($labels as $item): ?>
			<th style="min-width: 80px;"><?=$item?></th>
		<? endforeach; ?>
		<th style="min-width: 52px;"></th>
	</tr>
	<? if( count($data) ): ?>
		<? foreach ($data as $key => $item): ?>
			<tr>
				<td>
					<?	$goods="";
						foreach ($item->goods as $key => $order_good) {
							$goods.= $order_good->good->fields_assoc[3]->value." | ";
						}
					?>
					<div><?=$goods?></div>
				</td>
				<td>
					<div><?=$item->id?></div>		
				</td>
				<td>
					<div><? if($item->date) echo date_format(date_create($item->date), 'd.m.Y');?></div>
				</td>
				<td style="min-width: 120px;">
					<div><? if($item->contact_id) { $phone = str_split(Contact::model()->findbyPk($item->contact_id)->phones[0]->phone); echo "+".$phone[0]." (".$phone[1].$phone[2].$phone[3].") ".$phone[4].$phone[5].$phone[6]."-".$phone[7].$phone[8]."-".$phone[9].$phone[10]; }
					 ?></div>
				</td>
				<td>
					<div><? if($item->channel_id) echo DesktopTableCell::model()->find("row_id=$item->channel_id")->varchar_value;?></div>				
				</td>
				<td><div><? echo User::model()->findByPk($item->user_id)->usr_name; ?></div></td>
				<td>
					<div><?=$item->city?></div>				
				</td>
				<td>
					<div><? if($item->state_id) echo DesktopTableCell::model()->find("row_id=$item->state_id")->varchar_value;?></div>				
				</td>
				
				<td>
					<a href="<?php echo Yii::app()->createUrl('/good/adminorder',array('id'=>$item->id));?>" class="ajax-form ajax-update b-tool b-tool-update"></a>
					<a href="<?php echo Yii::app()->createUrl('/good/adminorderdelete',array('id'=>$item->id));?>" class="ajax-form ajax-delete b-tool b-tool-delete"></a>
				</td>
			</tr>
		<? endforeach; ?>
	<? else: ?>
		<tr>
			<td colspan="8">Пусто</td>
		</tr>
	<? endif; ?>
</table>