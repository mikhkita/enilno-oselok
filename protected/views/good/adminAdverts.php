<div class="b-popup">
	<h1><?=$good->type->name?> #<?=$good->fields_assoc[3]->value?></h1>
	<table class="b-table b-advert-table" border=1>
		<tr>
			<th><?=$labels["id"]?></th>
			<th><?=$labels["place_id"]?></th>
			<th><?=$labels["type_id"]?></th>
			<th><?=$labels["city_id"]?></th>
			<th><?=$labels["url"]?></th>
		</tr>
		<? foreach ($adverts as $name => $place): ?>
			<? foreach ($place as $id => $advert): ?>
			<tr>
				<td><?=$advert->id?></td>
				<td><?=$advert->place->category->value?></td>
				<td><?=$advert->type->value?></td>
				<td><?=$advert->city->value?></td>
				<td>
					<? if($advert->url): ?>
					<a href="<?=$advert->getUrl();?>" target="_blank"><?=$advert->getUrl();?></a>
					<? endif; ?>
				</td>
			</tr>
			<? endforeach; ?>
		<? endforeach; ?>
	</table>
	<div class="row buttons">
		<input type="button" onclick="$.fancybox.close(); return false;" value="Понятно">
	</div>
</div>