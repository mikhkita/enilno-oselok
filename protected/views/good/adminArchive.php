<h1><?=$name?></h1>
<a href="<?=$this->createUrl('/good/adminindex',array('good_type_id'=> $_GET["good_type_id"]))?>" class="b-link-back">Назад</a>
<table class="b-table b-good-table" border="1">
	<tr>
		<th style="min-width: 110px;max-width: 110px;width: 110px;"></th>
		<th style="vertical-align:bottom; min-width: 20px;">Код</th>
		<th style="min-width: 110px;max-width: 110px;width: 110px;">Фото</th>
		<th style="min-width: 110px;max-width: 110px;width: 110px;">Дата продажи</th>
	</tr>
	<? if( count($data) ): ?>
		<? foreach ($data as $i => $item): ?>
			<tr>
				<td><a href="<?=$this->createUrl('/good/adminarchive',array('id' => $item->id,'good_type_id'=> $_GET["good_type_id"]))?>">Вернуть</a></td>
				<td style="width:55px; text-align: center;">
					<div><?=$item->fields_assoc[3]->value?></div>
				</td>
				<td style="text-align: center;">
					<? $images = $this->getImages($item);?>
					<? foreach ($images as $i => $item): ?>
						<img src="<?=$item;?>" style="max-height: 100px;" alt="">
						<? if($i == 3) break; ?>
					<? endforeach; ?>
				</td>
				<td style="min-width: 100px; text-align: center;">
					<div><? $date = date_create($item->date); echo date_format($date, 'd-m-Y H:i:s');?></div>
				</td>
			</tr>
		<? endforeach; ?>
	<? else: ?>
		<tr>
			<td colspan="4">Пусто</td>
		</tr>
	<? endif; ?>
</table>