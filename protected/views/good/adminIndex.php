<div class="b-section-nav clearfix">
	<div class="b-section-nav-back clearfix">
		<? if( $this->access("good", "change") ): ?>
			<? if($archive): ?>
				<a href="<?=$this->createUrl('/good/adminindex',array('good_type_id'=> $_GET["good_type_id"]))?>" class="b-link left">Актуальные</a>
			<? else: ?>
				<a href="<?=$this->createUrl('/good/adminindex',array('good_type_id'=> $_GET["good_type_id"], 'archive' => true))?>" class="b-link left">Архив</a>
			<? endif; ?>
			<a href="<?=$this->createUrl('/good/adminsaletable',array('good_type_id'=> $_GET["good_type_id"]))?>" class="b-link left">Продажи</a>
			<a href="<?=$this->createUrl('/good/adminordertable')?>" class="b-link left">Заказы</a>
		<? endif; ?>
		<div class="left b-kit-switcher-cont clearfix">
			<span>Фото: </span>
			<a href="#" class="b-kit-switcher right<?if($with_photos) echo" checked";?>" data-on="goTo" data-off="goTo" data-on-href="<?=$this->createUrl('/good/adminindex',array('good_type_id'=> $_GET["good_type_id"],'partial'=>'true','with_photos'=>1,'archive'=>$archive))?>" data-off-href="<?=$this->createUrl('/good/adminindex',array('good_type_id'=> $_GET["good_type_id"],'partial'=>'true','with_photos'=>0,'archive'=>$archive))?>" >
			    <div class="b-kit-rail">
			        <div class="b-kit-state1">Вкл.</div>
			        <div class="b-kit-slider"></div>
			        <div class="b-kit-state2">Выкл.</div>
			    </div>
			</a>
		</div>
		<ul class="b-section-menu clearfix left">
			<? if(!$archive && $this->access("good", "change")): ?>
				<li><a href="<?php echo $this->createUrl('/good/admincreate',array('good_type_id'=> $_GET["good_type_id"] ))?>" class="ajax-form ajax-create">+</a></li>
			<? endif; ?>
			<li><span href="<?php echo Yii::app()->createUrl('/good/adminviewsettings',array('good_type_id'=>$_GET["good_type_id"],'goodFilter' => true))?>" class="ajax-form ajax-update b-tool b-tool-settings" title="Настройки отображения"></span><a href="#" class="fancy" data-block=".b-popup-filter">Фильтр</a></li>
			<? if(!$archive && $this->access("good", "change")): ?>
				<li><a>Выделить</a>
					<ul class="b-section-submenu">
						<li><a href="<?php echo $this->createUrl('/good/adminaddallcheckbox',array('good_type_id'=> $_GET["good_type_id"]))?>" class="b-sess-allcheckbox">Все</a></li>
						<li><a href="<?php echo $this->createUrl('/good/adminaddsomecheckbox',array('good_type_id'=> $_GET["good_type_id"]))?>" class="ajax-form ajax-update">По&nbsp;кодам</a></li>
						<li><a href="<?php echo $this->createUrl('/good/adminremoveallcheckbox',array('good_type_id'=> $_GET["good_type_id"]))?>" class="b-sess-allcheckbox">Сбросить&nbsp;выделение</a></li>
					</ul>
				</li>
				<li><a href="<?php echo $this->createUrl('/good/adminupdateall',array('good_type_id'=> $_GET["good_type_id"],'GoodFilter_page' => ($pages->currentPage+1) ))?>" class="ajax-form ajax-create" data-block=".b-popup-filter">Города</a></li>
				<li><a href="<?php echo $this->createUrl('/advert/adminindex',array('good_type_id'=> $_GET["good_type_id"]))?>">Объявления</a></li>
				<? if($_GET["good_type_id"] == 1 || $_GET["good_type_id"] == 2 && 0): ?>
					<li><a href="<?php echo $this->createUrl('/good/adminjoin')?>">Склеить</a></li>
				<? endif; ?>
				<li><a>Экспорт</a>
					<ul class="b-section-submenu">
						<? foreach ($export as $i => $item): ?>
							<li><a href="<?php echo $this->createUrl('/export/admindynamic',array('id'=> $item->id))?>"><?=str_replace(" ", "&nbsp;", $item->name)?></a></li>
						<? endforeach; ?>
					</ul>
				</li>
				<?if( Yii::app()->params["host"] != "koleso.online" && 0 ):?>
				<li><a href="<?php echo $this->createUrl('/good/admingetnextphoto')?>">Модерация</a></li>
				<?endif;?>
				<li><a>Убрать</a>
					<ul class="b-section-submenu">
						<li><a href="<?php echo $this->createUrl('/good/adminarchiveall',array('good_type_id'=> $_GET["good_type_id"]))?>" class="ajax-form ajax-delete" data-warning="Вы действительно хотите убрать в архив выделенные товары?">В архив</a></li></li>
						<li><a href="<?php echo $this->createUrl('/good/admindeleteall',array('good_type_id'=> $_GET["good_type_id"]))?>" class="ajax-form ajax-delete" data-warning="Вы действительно хотите удалить выделенные товары?">Удалить</a></li></li>
					</ul>
				</li>
			<? endif; ?>
		</ul>
	</div>
</div>
<h1 class="b-with-nav"><?=($archive)?"Архив ":""?><?=$name?></h1>

<?php $this->renderPartial('_filter', array('attributes'=>$attributes, 'arr_name' => $arr_name, 'arr_name_int' => $arr_name_int, 'labels' => $labels, 'filter_values' => $filter_values, 'filter_values_int' => $filter_values_int, 'filter_new_only' => $filter_new_only)); ?>
<div class="b-filter-pagination">
	<?php $form=$this->beginWidget('CActiveForm'); ?>
		<table class="b-table b-good-table b-sess-checkbox-info" data-add-url="<?=Yii::app()->createUrl('/good/adminaddcheckbox')?>" data-add-many-url="<?=Yii::app()->createUrl('/good/adminaddmanycheckbox',array('good_type_id' => $_GET["good_type_id"]))?>" data-remove-many-url="<?=Yii::app()->createUrl('/good/adminremovemanycheckbox',array('good_type_id' => $_GET["good_type_id"]))?>" data-remove-url="<?=Yii::app()->createUrl('/good/adminremovecheckbox')?>" border="1"><tr><th><?if($sort_field=="id"):?><a class="good-sort active <? if($sort_type=='DESC') echo ' up'; ?>" href="<?=$this->createUrl('/good/adminindex',array('sort_type' => $sort_type,'sort_field' => 'id','good_type_id'=> $_GET["good_type_id"]))?>">ID</a><? else:?><a href="<?=$this->createUrl('/good/adminindex',array('sort_field' => 'id', 'archive' => $archive, 'sort_type' => 'DESC','good_type_id'=> $_GET["good_type_id"]))?>">ID</a><? endif;?></th><? $ids = array(); if( count($data) ) foreach ($data as $i => $item) array_push($ids, $item->id); ?><? if( $this->access("good", "change") ): ?><th style="vertical-align:bottom; min-width: 20px;"><!-- <input type="checkbox" name="goods_id" class="b-sess-checkbox check-page" data-block="#b-sess-checkbox-list" value="<?=implode(',',$ids)?>"> --></th><? endif; ?><? if($with_photos): ?><th>Фотографии</th><? endif; ?><? if($filter_new_only): ?><th style="min-width: 160px;">Отсмотр</th><? endif; ?><th><span href="<?php echo Yii::app()->createUrl('/good/adminviewsettings',array('id'=>$item->id,'good_type_id'=>$_GET["good_type_id"]))?>" class="ajax-form ajax-update b-tool b-tool-settings" title="Настройки отображения"></span></th><? foreach ($fields as $field): ?><th <?if($field->attribute_id == 3):?>style="min-width: 55px;"<?endif;?> <? if($field->attribute->alias): ?>class="b-tooltip" title="<?=$field->attribute->name?>"<?endif;?>><?if($sort_field==$field->attribute_id):?><a class="good-sort active <? if($sort_type=='DESC') echo ' up'; ?>" href="<?=$this->createUrl('/good/adminindex',array('sort_type' => $sort_type,'sort_field' => $field->attribute_id, 'archive' => $archive,'good_type_id'=> $_GET["good_type_id"]))?>"><?=($field->attribute->alias)?$field->attribute->alias:$field->attribute->name; ?></a><? else:?><a href="<?=$this->createUrl('/good/adminindex',array('sort_field' => $field->attribute_id, 'archive' => $archive,'good_type_id'=> $_GET["good_type_id"]))?>"><?=($field->attribute->alias)?$field->attribute->alias:$field->attribute->name; ?></a><? endif;?></th><? endforeach; ?></tr><? if( count($data) ): ?><?$tog = true;?><? foreach ($data as $i => $item): ?><tr id="id-<?=$item->id?>"><td class="align-left"><?=$item->id?></td><? if( $this->access("good", "change") ): ?><td><input type="checkbox" name="good_id" class="b-sess-checkbox" data-block="#b-sess-checkbox-list" <? if($item->isChecked()): ?>checked="checked"<? endif; ?> value="<?=$item->id?>"></td><? endif; ?><? if($with_photos): ?><? $images = $item->getImages(1, array("small"), "all", NULL, true);?><td class="b-photo-td"><a href="<?=$images[0]["original"]?>" class="fancy-img" rel="<?=$item->id?>"><img src="<?=$images[0]["small"]?>" alt=""></a><? foreach ($images as $key => $img): ?><!-- <a href="<?=$img["original"]?>" class="fancy-img" rel="<?=$item->id?>"></a> --><? endforeach; ?></td><? endif; ?><? if($filter_new_only): ?><td class="b-tool-nav"><span href="<?php echo Yii::app()->createUrl('/good/admintoarchive',array('id' => $item->id))?>" class="ajax-request b-adverts-link"><p class="avert-info">-1</p></span><? foreach ($type_variants as $key => $var): ?><span href="<?php echo Yii::app()->createUrl('/good/adminchangetype',array('id' => $item->id, 'type' => $var->variant_id))?>" class="ajax-request b-adverts-link"><p class="advert-info"><?=$var->variant->value?></p></span><? endforeach; ?></td><? endif; ?><td style="<? if( $this->access("adverts") ): ?>min-width: 135px<? endif; ?>" class="b-tool-nav"><? if( $this->access("adverts") ): ?><? if($item->count_all_adverts): ?><span href="<?php echo Yii::app()->createUrl('/good/adminadverts',array('id'=>$item->id, 'archive' => $archive,'good_type_id'=> $_GET["good_type_id"],'GoodFilter_page' => ($pages->currentPage+1)))?>" class="ajax-form ajax-update b-adverts-link b-tooltip" title="Объявления"><p class="avert-info"><?=$item->count_all_adverts?> (<?=(!$item->count_url_adverts)?0:$item->count_url_adverts?>)</p></span><? else: ?><p class="avert-info b-tooltip" title="Нет объявлений">0 (0)</p><? endif; ?><? endif; ?><? if( $this->access("photo") ): ?><a href="<?php echo Yii::app()->createUrl('/good/adminphoto',array('id'=>$item->id, 'archive' => $archive))?>" class="b-tool b-tool-photo"></a><? endif; ?><? if( $this->access("sales") ): ?><? if(!$archive): ?><span href="<?php echo Yii::app()->createUrl('/good/adminsold',array('id'=>$item->id, 'archive' => $archive,'good_type_id' => $_GET['good_type_id']))?>" class="ajax-form ajax-create b-tool b-tool-sale" data-warning="Вы действительно хотите перенести товар &quot;<?=$item->fields_assoc[3]->value?>&quot; в архив?" title="Продано"></span><span href="<?php echo Yii::app()->createUrl('/good/adminorder',array('good_id'=>$item->id, 'archive' => $archive,'good_type_id' => $_GET['good_type_id']))?>" class="ajax-form ajax-create b-tool b-tool-sale" data-warning="" title="В заказ"></span><? endif; ?><? endif; ?><!-- <span href="<?php echo Yii::app()->createUrl('/good/adminupdateimages',array('id'=>$item->id))?>" class="ajax-form ajax-update ajax-photodoska b-tool b-tool-photo" title="Обновить фотографии"></span> --><? if( $this->access("good", "change") ): ?><span href="<?php echo Yii::app()->createUrl('/good/adminupdate',array('id'=>$item->id, 'archive' => $archive,'good_type_id' => $_GET['good_type_id'],'GoodFilter_page' => ($pages->currentPage+1) ))?>" class="ajax-form ajax-update b-tool b-tool-update" title="Редактировать"></span><? if($archive): ?><a href="<?=$this->createUrl('/good/adminarchive',array('id' => $item->id, 'archive' => $archive))?>" class="ajax-form ajax-delete not-ajax-delete" data-warning="Вы действительно хотите вернуть товар &quot;<?=$item->fields_assoc[3]->value?>&quot; из?">Вернуть</span><? else: ?><? if($this->user->role->code == "root"): ?><span href="<?php echo Yii::app()->createUrl('/good/admindelete',array('id'=>$item->id, 'archive' => $archive))?>" class="ajax-form ajax-delete b-tool b-tool-delete not-ajax-delete" data-warning="Вы действительно хотите убрать в архив товар &quot;<?=$item->fields_assoc[3]->value?>&quot;?" title="В архив"></span><? endif; ?><? endif; ?><? endif; ?></td><? foreach ($fields as $field): ?><td<?if($tog):?> style="min-width: <?=$field->attribute->width?>px;"<?endif;?> class="<?=((($field->attribute->required && !isset($item->fields_assoc[$field->attribute->id])) || ($field->attribute->id == 20 && !$item->fields_assoc[$field->attribute->id]->value ) )?('empty'):(""))?> <?=( ($field->attribute->id == 20)?"price":"" )?>"><? if( isset($item->fields_assoc[$field->attribute->id]) ): ?><? if( is_array($item->fields_assoc[$field->attribute->id]) ): ?><? foreach ($item->fields_assoc[$field->attribute->id] as $attr): ?><div><?=$attr->value?></div><? endforeach; ?><? else: ?><? if($field->attribute->id == 44 || $field->attribute->id == 53 || substr($item->fields_assoc[$field->attribute->id]->value, 0, 7) == "http://"): ?><div><a href="<?=$item->fields_assoc[$field->attribute->id]->value?>" target="_blank"><?=$this->cutText($item->fields_assoc[$field->attribute->id]->value,30)?></a></div><? elseif( $field->attribute->id == 69 ): ?><div><a href="https://injapan.ru/auction/<?=$item->fields_assoc[$field->attribute->id]->value?>.html" target="_blank"><?=$item->fields_assoc[$field->attribute->id]->value?></a></div><? elseif( $field->attribute->id == 20 ): ?><div><?=number_format( $item->fields_assoc[$field->attribute->id]->value, 0, ',', ' ' )?></div><? else: ?><div><?=$item->fields_assoc[$field->attribute->id]->value?></div><? endif; ?><? endif; ?><? endif; ?></td><? endforeach; ?><?$tog = false;?></tr><? endforeach; ?><? else: ?><tr><td colspan=<?=(count($fields)+5)?>>Пусто</td></tr><? endif; ?></table>
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
    <div style="text-align:left;" id="b-sess-checkbox-list">Выделено всего - <? $checked_codes = Good::getCheckboxes($_GET["good_type_id"]); echo count($checked_codes).":";?> <?=implode(", ",$checked_codes)?></div>
</div>