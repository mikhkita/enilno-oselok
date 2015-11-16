<h1><?=$name?></h1>


<div class="b-buttons-left">
	<a href="<?php echo $this->createUrl('/good/admincreate',array('goodTypeId'=> $_GET["goodTypeId"] ))?>" class="ajax-form ajax-create b-butt">Добавить</a>
	<a href="#" class="fancy left b-butt" data-block=".b-popup-filter">Фильтр</a>
	<div class="b-sort-cont left">
        <label for="sort">Сортировать: </label>
        <?=CHTML::dropDownList("sort[field]", $_POST["sort"]["field"], $sort_fields,array('id'=>'b-sort-1')); ?>
        <label for="sort">Порядок: </label>
        <?=CHTML::dropDownList("sort[type]", $_POST["sort"]["type"], array("ASC"=>"По возрастанию", "DESC"=>"По убыванию"),array('id'=>'b-order-1')); ?>
    </div>
</div>

<?php $this->renderPartial('_filter', array('attributes'=>$attributes, 'arr_name' => $arr_name, 'labels' => $labels, 'filter_values' => $filter_values, 'sort_fields' => $sort_fields)); ?>
<div class="b-filter-pagination">
	<?php $form=$this->beginWidget('CActiveForm'); ?>
		<table class="b-table b-good-table" border="1">
			<tr>
				<th style="min-width: 20px;">&nbsp;</th>
				<th style="min-width: 110px;">&nbsp;</th>
				<? foreach ($fields as $field): ?>
					<th <?if($field->attribute_id == 3):?>style="min-width: 55px;"<?endif;?>><? echo $field->attribute->name; ?></th>
				<? endforeach; ?>	
			</tr>
			<? if( count($data) ): ?>
				<? foreach ($data as $i => $item): ?>
					<tr>
						<td><input type="checkbox"></td>
						<td style="min-width: 125px;">
							<? if($item->advertsCount()): ?>
								<a href="<?php echo Yii::app()->createUrl('/good/adminadverts',array('id'=>$item->id))?>" class="ajax-form ajax-update b-adverts-link b-tooltip" title="Объявления"><span class="advert-info"><?=$item->advertsCount()?> (<?=$item->advertsCount(true)?>)</span></a>
							<? else: ?>
								<span class="advert-info b-tooltip" title="Нет объявлений"><?=$item->advertsCount()?> (<?=$item->advertsCount(true)?>)</span>
							<? endif; ?>
							<a href="<?php echo Yii::app()->createUrl('/good/adminupdateimages',array('id'=>$item->id))?>" class="ajax-photodoska b-tool b-tool-photo" title="Обновить фотографии"></a>
							<a href="<?php echo Yii::app()->createUrl('/good/adminupdate',array('id'=>$item->id,'goodTypeId' => $_GET['goodTypeId'],'GoodFilter_page' => ($pages->currentPage+1) ))?>" class="ajax-form ajax-update b-tool b-tool-update" title="Редактировать"></a>
							<? if($this->user->role->code == "root"): ?><a href="<?php echo Yii::app()->createUrl('/good/adminindex',array('delete'=>$item->id,'partial'=>'true','goodTypeId'=>$_GET["goodTypeId"],'GoodFilter_page'=>isset($_GET["GoodFilter_page"])?$_GET["GoodFilter_page"]:1))?>" class="ajax-form ajax-delete b-tool b-tool-delete not-ajax-delete" data-warning="Вы действительно хотите удалить товар &quot;<?=$item->fields_assoc[3]->value?>&quot;?" title="Удалить"></a><? endif; ?>
						</td>
						<? foreach ($fields as $field): ?>
							<td style="min-width: <?=$field->attribute->width?>px;">
								<? if( isset($item->fields_assoc[$field->attribute->id]) ): ?>
									<? if( is_array($item->fields_assoc[$field->attribute->id]) ): ?>
										<? foreach ($item->fields_assoc[$field->attribute->id] as $attr): ?>
											<div><?=$attr->value?></div>
										<? endforeach; ?>
									<? else: ?>
										<? if($field->attribute->id == 44 || $field->attribute->id == 53): ?>
											<div><a href="<?=$item->fields_assoc[$field->attribute->id]->value?>" target="_blank"><?=$this->cutText($item->fields_assoc[$field->attribute->id]->value,30)?></a></div>
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
</div>