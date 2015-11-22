<div class="b-popup">
	<h1><?=$good->type->name?> #<?=$good->fields_assoc[3]->value?></h1>
	<table class="b-table b-advert-table" border=1>
		<tr>
			<th><?=$labels["id"]?></th>
			<th><?=$labels["place_id"]?></th>
			<th><?=$labels["type_id"]?></th>
			<th><?=$labels["city_id"]?></th>
			<th><?=$labels["url"]?></th>
			<? if($this->isRoot() || 1): ?>
				<th></th>
			<? endif; ?>
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
				<? if($this->isRoot() || 1): ?>
					<td><a href="<?php echo Yii::app()->createUrl('/good/adminindex',array('deleteAdvert'=>$advert->id,'partial'=>'true','goodTypeId'=>$_GET["goodTypeId"],'GoodFilter_page'=>isset($_GET["GoodFilter_page"])?$_GET["GoodFilter_page"]:1))?>" class="ajax-form ajax-delete b-tool b-tool-delete not-ajax-delete" data-warning="Вы действительно хотите удалить объявление &quot;<?=$advert->id." ".$advert->place->category->value." ".$advert->type->value." ".$advert->city->value?>&quot;?" title="Удалить"></a></td>
				<? endif; ?>
			</tr>
			<? endforeach; ?>
		<? endforeach; ?>
	</table>
	<div class="row buttons">
		<input type="button" onclick="$.fancybox.close(); return false;" value="Понятно">
	</div>
</div>