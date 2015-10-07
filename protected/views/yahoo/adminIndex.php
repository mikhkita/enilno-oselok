<h1 class="b-hide-title"><?=$this->adminMenu["cur"]->name?></h1>
<div class="b-link-back">
    <a href="#" class="b-select-all">Выделить все</a>
    <a href="<?php echo Yii::app()->createUrl('/'.$this->adminMenu["cur"]->code.'/adminindex')?>" class="b-delete-selected">Удалить выбранное</a>
</div>
<div class="b-top-butt clearfix">
    <div class="b-sort-cont left">
        <label for="sort">Сортировать: </label>
        <?=CHTML::dropDownList("sort", $_POST["sort"], $sort_fields,array('id'=>'b-sort-1')); ?>
        <label for="sort">Порядок: </label>
        <?=CHTML::dropDownList("order", $_POST["order"], array("ASC"=>"По возрастанию", "DESC"=>"По убыванию"),array('id'=>'b-order-1')); ?>
    </div>
    <a href="#" class="fancy left b-butt" data-block=".b-popup-filter">Фильтр</a>
</div>

<?php $this->renderPartial('_filter', array('arr_name'=>$arr_name, 'filter'=>$filter, 'filter_values'=>$filter_values, 'labels'=>$labels, 'sort_fields'=>$sort_fields)); ?>

<?php $this->renderPartial('_filterList', array('filter_list'=>$filter_list)); ?>

<? if(count($model)): ?>
<div class="pagination b-filter-pagination">
    <ul class="yahoo-list">
    	<? foreach ($model as $item): ?>
			<li class="" data-id="<?=$item->id?>">
                <div class="image-cont" style="background-image:url('<?=$item->image?>');">
                    <a href="https://injapan.ru/auction/<?=$item->id?>.html" target="_blank"></a>
                    <div class="b-nav clearfix">
                        <span class="b-nav-delete b-tooltip" title="Не показывать лот"></span>
                        <a href="<?php echo Yii::app()->createUrl('/'.$this->adminMenu["cur"]->code.'/adminauctioncreate',array('id'=> $item->id ))?>" class="ajax-form ajax-create b-nav-sniper b-tooltip" title="Добавить в снайпер"></a>
                    </div>
                </div>
                <!-- <h3><?=$item->title?></h3> -->
                <div class="clearfix">
                    <h4 class="left">Цена: <b><?=$item->cur_price?></b></h4>
                    <h5 class="right">Ст.: <b><?=$item->bids?></b></h5>
                </div>
                <div class="clearfix">
                    <h4 class="left"><?=$item->category->name?></b></h4>
                    <h5 class="right"><?=$this->cutText($item->seller->name,16)?></h5>
                </div>
                <div class="clearfix">
                    <h4 class="left">Осталось:</h4>
                    <h5 class="right<?if(!mb_strpos($item->end_time, "д",0,"UTF-8") && !mb_strpos($item->end_time, "ч",0,"UTF-8")):?> red<?endif;?>"><?=$item->end_time?></h5>
                </div>
			</li>
		<? endforeach; ?>
    </ul>  
    <div class="b-pagination-cont clearfix">
        <?php $this->widget('CLinkPager', array(
            'header' => '',
            'lastPageLabel' => 'последняя &raquo;',
            'firstPageLabel' => '&laquo; первая', 
            'pages' => $pages,
            'prevPageLabel' => '< назад',
            'nextPageLabel' => 'далее >'
        )) ?>
        <div class="b-lot-count">Всего лотов: <?=$lot_count?></div>
    </div>
</div>  
<? else: ?>
    <h3 class="b-no-goods">Лотов не найдено</h3>
<? endif; ?>
