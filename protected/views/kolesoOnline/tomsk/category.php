<?
    $sort_arr = array("20" => "цене", "9" => "диаметру");
    if ($_GET['type'] == 1) {
        $sort_arr["7"] = "ширине";
        $sort_arr["8"] = "профилю";
    }
    if ($_GET['type'] == 2) {
        $sort_arr["31"] = "ширине";
        $sort_arr["32"] = "вылету";
    }
    if ($_GET['type'] == 3) {
        $sort_arr["7"] = "ширине шины";
        $sort_arr["8"] = "профилю";
        $sort_arr["31"] = "ширине диска";
        $sort_arr["32"] = "вылету";
    }
    $sort_type = (isset($_SESSION['FILTER'][$_GET['type']]['sort']) && isset($_SESSION['FILTER'][$_GET['type']]['sort']['type']) && $_SESSION['FILTER'][$_GET['type']]['sort']['type'] != "") ? $_SESSION['FILTER'][$_GET['type']]['sort']['type'] : "ASC";
?>
<div class="b-content">
    <div class="b-category">
        <div class="b-block clearfix">
            <?php $form=$this->beginWidget('CActiveForm', array(
                'id'=>'filter',
                'action' => Yii::app()->createUrl('/kolesoOnline/category',array("type" => $_GET['type'])),
                'enableAjaxValidation'=>false,
                'method' => 'GET'
            )); ?>
            <div class="mobile-only">
                <ul class="navigation clearfix">
                    <li><a href="<?=Yii::app()->createUrl('/kolesoOnline')?>"></a></li>
                    <li><a href="#"><?=($this->params[$_GET['type']]["NAME"]." ".Yii::app()->params["city"]->in)?></a></li>
                </ul>
                <h1 class="category-title"><?=($this->params[$_GET['type']]["NAME"]." ".Yii::app()->params["city"]->in)?></h1>
            </div>
            <div class="grey-block left">
                <div>
                    <h3>Фильтр</h3>
                    <a href="#" class="b-clear-filter">Сбросить фильтр</a>
                    <div class="filter-block">
                        <div class="clearfix">
                        <? if($_GET['type'] == 1 || $_GET['type'] == 3): ?>
                            <div class="tire-type clearfix">    
                                <? foreach ($filter[$params[$_GET['type']]["SEASON"]] as $key => $col): ?>
                                    <? foreach ($col as $item): ?>     
                                        <label>
                                            <?=$item['value']?>
                                            <input type="checkbox" name="arr[<?=$params[$_GET['type']]["SEASON"]?>][]" value="<?=$item['variant_id']?>" <?=$item['checked']?>>
                                        </label>
                                    <? endforeach; ?>
                                <? endforeach; ?>
                                <!-- <input id="tire-spike" type="checkbox" name="arr[23][]" value="762">
                                <label for="tire-spike">Зимние шипованные</label>
                                <input id="tire-summer" type="checkbox" name="arr[23][]" value="463">
                                <label for="tire-summer">Летние</label> -->
                            </div>
                            <div></div>
                        <? endif; ?>
                        <? foreach ($params[$_GET['type']]["FILTER"] as $attr_id => $label): ?>
                            <div class="filter-item">
                                <h5><?=$label?></h5>
                                <div class="input">&nbsp;</div>  
                                <div class="variants clearfix">
                                    <? if($mobile): ?>
                                    <h4><?=$label?></h4>
                                    <? endif; ?>
                                    <? if( $filter[$attr_id] ) foreach ($filter[$attr_id] as $key => $col): ?>
                                        <ul class="wave">
                                            <? foreach ($col as $item): ?>
                                                <li>
                                                    <div>
                                                            <input type="checkbox" id="var_<?=$item['variant_id']?>" name="arr[<?=$attr_id?>][]" value="<?=$item['variant_id']?>" <?=$item['checked']?>>
                                                        <span onselectstart="return false;"><?=str_replace(" ", "&nbsp;", $item['value'])?></span>
                                                    </div>
                                                </li>
                                            <? endforeach; ?>
                                        </ul>
                                    <? endforeach; ?>
                                    <? if($mobile): ?>
                                    <a href="#" class="b-variants-close b-orange-butt">Выбрать</a>
                                    <? endif; ?>
                                </div>
                            </div>
                        <? endforeach; ?>
                        </div>
                        <div class="clearfix">
                            <h5>Цена (за комплект)</h5>
                            <div class="slide-type">
                                <input class="min-val price" type="text" maxlength="6" name="int[20][min]" placeholder="Мин.">
                                <span class="dash">-</span>
                                <input class="max-val price" type="text" maxlength="6" name="int[20][max]" placeholder="Макс.">
                                <div class="slider-range-cont">
                                    <div data-min-cur="<?=$_SESSION['FILTER'][$_GET['type']]['int'][20]['min']?>" data-min="<?=$params[$_GET['type']]['PRICE_MIN']?>" data-max-cur="<?=$_SESSION['FILTER'][$_GET['type']]['int'][20]['max']?>" data-max="<?=$params[$_GET['type']]['PRICE_MAX']?>" class="slider-range"></div>
                                </div>
                            </div>  
                        </div>
                        <div class="filter-butt-cont">
                            <input type="submit" class="b-black-butt" value="Найти">
                        </div>
                    </div>
                </div>
            </div>
            <div class="right good-list">
                <!-- <a href="http://vk.com/kolesoonline?page=category" target="_blank" class="b-vk-benz">
                    <h3>Вступи в нашу <?=($mobile)?"<span>группу вк</span>":"группу «<span>Колесо.Онлайн</span>»"?> и</h3>
                    <h2>Выиграй 30 литров<?=($mobile)?"":" бензина"?></h2>
                    <div class="b-benz">
                        <span></span>
                    </div>
                </a> -->
                <div class="not-mobile">
                    <ul class="navigation clearfix">
                        <li><a href="<?=Yii::app()->createUrl('/kolesoOnline')?>"></a></li>
                        <li><a href="#"><?=($this->params[$_GET['type']]["NAME"])?></a></li>
                    </ul>
                    <h1 class="category-title"><?=($this->params[$_GET['type']]["NAME"])?></h1>
                </div>
                <? if(count($goods)): ?>
                <?php $this->widget('CLinkPager', array(
                    'header' => '',
                    'firstPageLabel' => '1', 
                    'lastPageLabel' => $pages->getPageCount(), 
                    'maxButtonCount' => 100,
                    'pages' => $pages,
                    'htmlOptions' => array("class"=>"b-category-pages")
                )) ?>
                <div class="b-sort clearfix">
                    <div class="left clearfix">
                        <h4 class="left">Сортировать по:</h4>
                        <ul class="left clearfix">
                            <? foreach ($sort_arr as $key => $value): ?>
                            <? if(isset($_SESSION['FILTER'][$_GET['type']]['sort']['field']) && $_SESSION['FILTER'][$_GET['type']]['sort']['field']==$key): ?>
                                    <li class="active <? if($sort_type =='DESC') echo 'up'; ?>">
                                    <?=$value?>
                                    <input type="radio" name="sort[field]" value="<?=$key?>" checked>
                                <? else: ?>
                                    <li>
                                    <?=$value?>
                                    <input type="radio" name="sort[field]" value="<?=$key?>">
                                <? endif; ?>
                                </li>
                            <? endforeach;?>
                            <input type="hidden" name="sort[type]" value="<?=$sort_type?>">
                            <!-- <li class="active">Цене</li>
                            <li>Диаметру</li>
                            <li>Ширине</li>
                            <li>Профилю</li> -->
                        </ul>
                    </div>
                    <!-- <div class="right clearfix">
                        <h4 class="left">Вид:</h4>
                        <span class="active grid-view view"></span>
                        <span class="list-view view"></span>
                    </div> -->
                </div>
                <ul class="goods clearfix" id="goods">  
                    <?php $this->renderPartial('tomsk/_list', array('goods' => $goods,'last' => $last,'params' => $params,'type' => $_GET['type'],'dynamic'=>$dynamic)); ?>
                </ul>
                <div class="load" style="display:none;">Загрузка...</div>
                <? else: ?>
                    <h3 class="b-no-goods">Товаров не найдено</h3>
                    <h4 class="no-goods-popup"><a href="#" class="fancy" data-block="#b-popup-exit">Оставьте заявку</a> и наши менеджеры подберут вам дополнительные варианты</h4>
                <? endif; ?>
                <? if(count($similar)): ?>
                    <div id="similar" style="display:none;">
                        <h3 class="similar-title">Похожие товары</h3>
                        <ul class="goods clearfix">  
                            <?php $this->renderPartial('tomsk/_list', array('goods' => $similar,'last' => 0,'params' => $params,'type' => $_GET['type'],'dynamic'=>$dynamic)); ?>
                        </ul>
                    </div>
                <? endif; ?>
            </div>
            <?php $this->endWidget(); ?>            
        </div>
        
    </div>
</div>