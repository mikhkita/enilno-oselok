<h1><?=$name?></h1>
<div class="b-buttons-left-cont">
	<div class="clearfix">
		<a href="<?php echo $this->createUrl('/good/admincreate',array('good_type_id'=> $_GET["good_type_id"] ))?>" class="ajax-form ajax-create b-butt">Добавить</a>
		<a href="#" class="fancy left b-butt" data-block=".b-popup-filter">Фильтр</a>
		<div class="b-sort-cont left">
	        <label for="sort">Сортировать: </label>
	        <?=CHTML::dropDownList("sort[field]", $_POST["sort"]["field"], $sort_fields,array('id'=>'b-sort-1')); ?>
	        <label for="sort">Порядок: </label>
	        <?=CHTML::dropDownList("sort[type]", $_POST["sort"]["type"], array("ASC"=>"По возрастанию", "DESC"=>"По убыванию"),array('id'=>'b-order-1')); ?>
	    </div>
	    <a href="<?php echo $this->createUrl('/good/adminupdateall',array('good_type_id'=> $_GET["good_type_id"],'GoodFilter_page' => ($pages->currentPage+1) ))?>" class="ajax-form ajax-create b-butt" data-block=".b-popup-filter">Редактировать города</a>
		<a href="<?php echo $this->createUrl('/good/adminaddsomecheckbox',array('good_type_id'=> $_GET["good_type_id"]))?>" class="ajax-form ajax-update right b-butt">Выделить по кодам</a>
		<a href="<?php echo $this->createUrl('/good/adminremoveallcheckbox',array('good_type_id'=> $_GET["good_type_id"]))?>" class="right b-sess-allcheckbox b-butt">Сбросить все выделение</a>
		<a href="<?php echo $this->createUrl('/good/adminaddallcheckbox',array('good_type_id'=> $_GET["good_type_id"]))?>" class="right b-sess-allcheckbox b-butt">Выделить все <?=$name?></a>
	<!-- </div> -->
	<!-- <div class="clearfix"> -->
		<a href="<?php echo $this->createUrl('/good/adminupdateadverts',array('good_type_id'=> $_GET["good_type_id"]))?>" class="ajax-update-prices b-butt">Обновить объявления</a>
		<a href="<?php echo $this->createUrl('/good/adminupdateadverts',array('good_type_id'=> $_GET["good_type_id"], 'images' => '1'))?>" class="ajax-update-prices b-butt">Обновить фотографии</a>
	</div>
</div>

<?php $this->renderPartial('_filter', array('attributes'=>$attributes, 'arr_name' => $arr_name, 'labels' => $labels, 'filter_values' => $filter_values, 'sort_fields' => $sort_fields)); ?>
<div class="b-filter-pagination">
	<?php $form=$this->beginWidget('CActiveForm'); ?>
		<table class="b-table b-good-table b-sess-checkbox-info" data-add-url="<?=Yii::app()->createUrl('/good/adminaddcheckbox')?>" data-remove-url="<?=Yii::app()->createUrl('/good/adminremovecheckbox')?>" border="1">
			<tr>
				<? $ids = array(); foreach ($data as $i => $item) array_push($ids, $item->id); ?>
				<th style="vertical-align:bottom; min-width: 20px;"><input type="checkbox" name="goods_id" class="b-sess-checkbox check-page" data-block="#b-sess-checkbox-list" value="<?=implode(',',$ids)?>"></th>
				<th style="min-width: 110px;max-width: 110px;width: 110px;"><a href="<?php echo Yii::app()->createUrl('/good/adminviewsettings',array('id'=>$item->id,'good_type_id'=>$_GET["good_type_id"]))?>" class="ajax-form ajax-update b-tool b-tool-settings" title="Настройки отображения"></a></th>
				<? foreach ($fields as $field): ?>
					<th <?if($field->attribute_id == 3):?>style="min-width: 55px;"<?endif;?> <? if($field->attribute->alias): ?>class="b-tooltip" title="<?=$field->attribute->name?>"<?endif;?>><?=($field->attribute->alias)?$field->attribute->alias:$field->attribute->name; ?></th>
				<? endforeach; ?>	
			</tr>
			<? if( count($data) ): ?>
				<? foreach ($data as $i => $item): ?>
					<tr>
						<td><input type="checkbox" name="good_id" class="b-sess-checkbox" data-block="#b-sess-checkbox-list" <? if($item->isChecked()): ?>checked="checked"<? endif; ?> value="<?=$item->id?>"></td>
						<td style="min-width: 161px;text-align: right;">
							<? if($item->count_all_adverts): ?>
								<a href="<?php echo Yii::app()->createUrl('/good/adminadverts',array('id'=>$item->id,'good_type_id'=> $_GET["good_type_id"],'GoodFilter_page' => ($pages->currentPage+1)))?>" class="ajax-form ajax-update b-adverts-link b-tooltip" title="Объявления"><span class="advert-info"><?=$item->count_all_adverts?> (<?=(!$item->count_url_adverts)?0:$item->count_url_adverts?>)</span></a>
							<? else: ?>
								<span class="advert-info b-tooltip" title="Нет объявлений">0 (0)</span>
							<? endif; ?>
							<a href="<?php echo Yii::app()->createUrl('/good/adminsold',array('id'=>$item->id,'good_type_id' => $_GET['good_type_id']))?>" class="b-tool b-tool-sale" title="Продано"></a>
							<a href="<?php echo Yii::app()->createUrl('/good/adminupdateimages',array('id'=>$item->id))?>" class="ajax-photodoska b-tool b-tool-photo" title="Обновить фотографии"></a>
							<a href="<?php echo Yii::app()->createUrl('/good/adminupdate',array('id'=>$item->id,'good_type_id' => $_GET['good_type_id'],'GoodFilter_page' => ($pages->currentPage+1) ))?>" class="ajax-form ajax-update b-tool b-tool-update" title="Редактировать"></a>
							<? if($this->user->role->code == "root"): ?><a href="<?php echo Yii::app()->createUrl('/good/adminindex',array('delete'=>$item->id,'partial'=>'true','good_type_id'=>$_GET["good_type_id"],'GoodFilter_page'=>isset($_GET["GoodFilter_page"])?$_GET["GoodFilter_page"]:1))?>" class="ajax-form ajax-delete b-tool b-tool-delete not-ajax-delete" data-warning="Вы действительно хотите удалить товар &quot;<?=$item->fields_assoc[3]->value?>&quot;?" title="Удалить"></a><? endif; ?>
						</td>
						<? foreach ($fields as $field): ?>
							<td style="min-width: <?=$field->attribute->width?>px; text-align: left;">
								<? if( isset($item->fields_assoc[$field->attribute->id]) ): ?>
									<? if( is_array($item->fields_assoc[$field->attribute->id]) ): ?>
										<? foreach ($item->fields_assoc[$field->attribute->id] as $attr): ?>
											<div><?=$attr->value?></div>
										<? endforeach; ?>
									<? else: ?>
										<? if($field->attribute->id == 44 || $field->attribute->id == 53): ?>
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
					</tr>
				<? endforeach; ?>
			<? else: ?>
				<tr>
					<td colspan=<?=(count($fields)+1)?>>Пусто</td>
				</tr>
			<? endif; ?>
		</table>

	<?php $this->endWidget(); ?>
	<div class="b-pagination-cont clearfix">
        <?php $this->widget('CLinkPager', array(
	        'header' => '',
	        'lastPageLabel' => 'последняя &raquo;',
	        'firstPageLabel' => '&laquo; первая', 
	        'pages' => $pages,
	        'prevPageLabel' => '< назад',
	        'nextPageLabel' => 'далее >'
	    )) ?>
        <div class="b-lot-count">Всего товаров: <?=$good_count?></div>
    </div>
    <div style="text-align:left;" id="b-sess-checkbox-list">Выделено всего - <? $codes = Good::getCheckboxes($_GET["good_type_id"]); echo count($codes).":";?> <?=implode(", ",$codes)?></div>
</div>