<table class="b-table b-good-table b-sess-checkbox-info" data-add-url="<?=Yii::app()->createUrl('/good/adminaddcheckbox')?>" data-add-many-url="<?=Yii::app()->createUrl('/good/adminaddmanycheckbox',array('good_type_id' => $_GET["good_type_id"]))?>" data-remove-many-url="<?=Yii::app()->createUrl('/good/adminremovemanycheckbox',array('good_type_id' => $_GET["good_type_id"]))?>" data-remove-url="<?=Yii::app()->createUrl('/good/adminremovecheckbox')?>" border="1">
		<tr>
			<th>
				<?if($sort_field=="id"):?>
					<a class="good-sort active <? if($sort_type=='DESC') echo ' up'; ?>" href="<?=$this->createUrl('/good/adminindex',array('sort_type' => $sort_type,'sort_field' => 'id','good_type_id'=> $_GET["good_type_id"]))?>">ID</a>
				<? else:?>
					<a href="<?=$this->createUrl('/good/adminindex',array('sort_field' => 'id', 'sort_type' => 'DESC','good_type_id'=> $_GET["good_type_id"]))?>">ID</a>
				<? endif;?>
			</th>
			<? $ids = array(); if( count($data) ) foreach ($data as $i => $item) array_push($ids, $item->id); ?>
			<th style="vertical-align:bottom; min-width: 20px;">
				<!-- <input type="checkbox" name="goods_id" class="b-sess-checkbox check-page" data-block="#b-sess-checkbox-list" value="<?=implode(',',$ids)?>"> -->
			</th>
			<? if($with_photos): ?>
				<th>Фотографии</th>
			<? endif; ?>
			<? if($filter_new_only): ?>
				<th style="min-width: 160px;">Отсмотр</th>
			<? endif; ?>
			<th style="min-width: 110px;max-width: 110px;width: 110px;"><span href="<?php echo Yii::app()->createUrl('/good/adminviewsettings',array('id'=>$item->id,'good_type_id'=>$_GET["good_type_id"]))?>" class="ajax-form ajax-update b-tool b-tool-settings" title="Настройки отображения"></span></th>
			<? foreach ($fields as $field): ?>
				<th <?if($field->attribute_id == 3):?>style="min-width: 55px;"<?endif;?> <? if($field->attribute->alias): ?>class="b-tooltip" title="<?=$field->attribute->name?>"<?endif;?>>
					<?if($sort_field==$field->attribute_id):?>
						<a class="good-sort active <? if($sort_type=='DESC') echo ' up'; ?>" href="<?=$this->createUrl('/good/adminindex',array('sort_type' => $sort_type,'sort_field' => $field->attribute_id,'good_type_id'=> $_GET["good_type_id"]))?>"><?=($field->attribute->alias)?$field->attribute->alias:$field->attribute->name; ?></a>
					<? else:?>
						<a href="<?=$this->createUrl('/good/adminindex',array('sort_field' => $field->attribute_id,'good_type_id'=> $_GET["good_type_id"]))?>"><?=($field->attribute->alias)?$field->attribute->alias:$field->attribute->name; ?></a>
					<? endif;?>
					
				</th>
			<? endforeach; ?>
		</tr>
		<? if( count($data) ): ?>
			<?$tog = true;?>
			<? foreach ($data as $i => $item): ?>
				<tr id="id-<?=$item->id?>">
					<td class="align-left"><?=$item->id?></td>
					<td><input type="checkbox" name="good_id" class="b-sess-checkbox" data-block="#b-sess-checkbox-list" <? if($item->isChecked()): ?>checked="checked"<? endif; ?> value="<?=$item->id?>"></td>
					<? if($with_photos): ?>
						<? $images = $item->getImages(1, array("small"), "all", NULL, true);?>
						<td class="b-photo-td">
							<a href="<?=$images[0]["original"]?>" class="fancy-img" rel="<?=$item->id?>"><img src="<?=$images[0]["small"]?>" alt=""></a>
							<? foreach ($images as $key => $img): ?>
								<!-- <a href="<?=$img["original"]?>" class="fancy-img" rel="<?=$item->id?>"></a> -->
							<? endforeach; ?>
						</td>
					<? endif; ?>
					<? if($filter_new_only): ?>
						<td class="b-tool-nav">
							<span href="<?php echo Yii::app()->createUrl('/good/admintoarchive',array('id' => $item->id))?>" class="ajax-request b-adverts-link"><p class="avert-info">-1</p></span>
							<? foreach ($type_variants as $key => $var): ?>
								<span href="<?php echo Yii::app()->createUrl('/good/adminchangetype',array('id' => $item->id, 'type' => $var->variant_id))?>" class="ajax-request b-adverts-link"><p class="advert-info"><?=$var->variant->value?></p></span>
							<? endforeach; ?>
						</td>
					<? endif; ?>
					<td style="min-width: 161px" class="b-tool-nav">
						<? if($item->count_all_adverts): ?>
							<span href="<?php echo Yii::app()->createUrl('/good/adminadverts',array('id'=>$item->id,'good_type_id'=> $_GET["good_type_id"],'GoodFilter_page' => ($pages->currentPage+1)))?>" class="ajax-form ajax-update b-adverts-link b-tooltip" title="Объявления"><p class="avert-info"><?=$item->count_all_adverts?> (<?=(!$item->count_url_adverts)?0:$item->count_url_adverts?>)</p></span>
						<? else: ?>
							<p class="avert-info b-tooltip" title="Нет объявлений">0 (0)</p>
						<? endif; ?>
						<a href="<?php echo Yii::app()->createUrl('/good/adminphoto',array('id'=>$item->id))?>" class="b-tool b-tool-photo"></a>
						<span href="<?php echo Yii::app()->createUrl('/good/adminsold',array('id'=>$item->id,'good_type_id' => $_GET['good_type_id']))?>" class="ajax-form ajax-create b-tool b-tool-sale" data-warning="Вы действительно хотите перенести товар &quot;<?=$item->fields_assoc[3]->value?>&quot; в архив?" title="Продано"></span>
						<!-- <span href="<?php echo Yii::app()->createUrl('/good/adminupdateimages',array('id'=>$item->id))?>" class="ajax-form ajax-update ajax-photodoska b-tool b-tool-photo" title="Обновить фотографии"></span> -->
						<span href="<?php echo Yii::app()->createUrl('/good/adminupdate',array('id'=>$item->id,'good_type_id' => $_GET['good_type_id'],'GoodFilter_page' => ($pages->currentPage+1) ))?>" class="ajax-form ajax-update b-tool b-tool-update" title="Редактировать"></span>
						<? if($this->user->role->code == "root"): ?><span href="<?php echo Yii::app()->createUrl('/good/admindelete',array('id'=>$item->id))?>" class="ajax-form ajax-delete b-tool b-tool-delete not-ajax-delete" data-warning="Вы действительно хотите убрать в архив товар &quot;<?=$item->fields_assoc[3]->value?>&quot;?" title="В архив"></span><? endif; ?>
					</td>
					<? foreach ($fields as $field): ?>
						<td<?if($tog):?> style="min-width: <?=$field->attribute->width?>px;"<?endif;?> <?=(($field->attribute->required && !isset($item->fields_assoc[$field->attribute->id]))?('class="empty"'):(""))?>>
							<? if( isset($item->fields_assoc[$field->attribute->id]) ): ?>
								<? if( is_array($item->fields_assoc[$field->attribute->id]) ): ?>
									<? foreach ($item->fields_assoc[$field->attribute->id] as $attr): ?>
										<div><?=$attr->value?></div>
									<? endforeach; ?>
								<? else: ?>
									<? if($field->attribute->id == 44 || $field->attribute->id == 53 || substr($item->fields_assoc[$field->attribute->id]->value, 0, 7) == "http://"): ?>
										<div><a href="<?=$item->fields_assoc[$field->attribute->id]->value?>" target="_blank"><?=$this->cutText($item->fields_assoc[$field->attribute->id]->value,30)?></a></div>
									<? elseif( $field->attribute->id == 69 ): ?>
										<div><a href="https://injapan.ru/auction/<?=$item->fields_assoc[$field->attribute->id]->value?>.html" target="_blank"><?=$item->fields_assoc[$field->attribute->id]->value?></a></div>
									<? else: ?>
										<div><?=$item->fields_assoc[$field->attribute->id]->value?></div>
									<? endif; ?>
								<? endif; ?>
							<? endif; ?>
						</td>
					<? endforeach; ?>
					<?$tog = false;?>
				</tr>
			<? endforeach; ?>
		<? else: ?>
			<tr>
				<td colspan=<?=(count($fields)+5)?>>Пусто</td>
			</tr>
		<? endif; ?>
	</table>