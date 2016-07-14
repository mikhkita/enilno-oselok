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
<div class="pagination b-filter-pagination" onselectstart="return false;">
    <ul class="yahoo-list">
    	<? foreach ($model as $item): ?>
			<li class="" data-id="<?=$item->id?>" title="<?=$item->title?>">
                <div class="image-cont track" style="background-image:url('<?=$item->img?>');">
                    <span><b><?=$this->wheel_type[$item->type]?></b></span>
                    <a href="http://baza.drom.ru/<?=$item->id?>" target="_blank" title="Посмотреть объявление"></a>
                    <div class="b-nav clearfix">
                        <span class="b-nav-delete b-tooltip b-delete-<?=$item->id?>" style="width: 100%;" title="В архив"></span>
                        
                    </div>
                </div>
                <div class="clearfix">
                    <h4 class="left">Цена: <b><?=($item->price) ? $item->price : "?"; ?></b></h4>
                    <h5 class="right"><b><?=($item->amount) ? $item->amount : "?"; ?> шт.</b></h5>
                </div>
                <div class="clearfix text-overflow">
                    <h4><?=$item->params?></h4>

                </div>
                <div class="clearfix">
                    <h4 class="left"><?=date_format(date_create($item->date), 'd.m.Y');?></h4>
                    <h5 class="right"><?=$item->views?> прос.</h5>
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
