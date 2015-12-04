<? if(count($goods)): ?>
<?
    $sort_arr = array("51" => "по цене", "9" => "по диаметру");
    if ($_GET['type'] == 1) {
        $sort_arr["7"] = "по ширине";
        $sort_arr["8"] = "по профилю";
    }
    if ($_GET['type'] == 2) {
        $sort_arr["31"] = "по ширине";
        $sort_arr["32"] = "по вылету";
    }
    $sort_type = (isset($_GET['sort']) && isset($_GET['sort']['type']) && $_GET['sort']['type'] != "") ? $_GET['sort']['type'] : "DESC";
?>
<?php $this->renderPartial('_header', array('cities' => $cities)); ?>
<div class="b-content">
    <div class="b-category">
        <div class="b-block clearfix">
            <?php $form=$this->beginWidget('CActiveForm', array(
                'id'=>'filter',
                'action' => Yii::app()->createUrl('/kolesoOnline/category',array("type" => $_GET['type'])),
                'enableAjaxValidation'=>false,
                'method' => 'GET'
            )); ?>
            <div class="grey-block left">
                <div class="gradient-grey">
                    <h3>Фильтры</h3>
                    <div class="filter-block">
                        <div class="tire-type clearfix">    
                            <? foreach ($filter[$params[1]["SEASON"]] as $key => $col): ?>
                                <? foreach ($col as $item): ?>     
                                    <label>
                                        <?=$item['value']?>
                                        <input type="checkbox" name="arr[<?=$params[1]["SEASON"]?>][]" value="<?=$item['variant_id']?>">
                                    </label>
                                <? endforeach; ?>
                            <? endforeach; ?>
                            <!-- <input id="tire-spike" type="checkbox" name="arr[23][]" value="762">
                            <label for="tire-spike">Зимние шипованные</label>
                            <input id="tire-summer" type="checkbox" name="arr[23][]" value="463">
                            <label for="tire-summer">Летние</label> -->
                        </div>
                        <? foreach ($params[$_GET['type']]["FILTER"] as $attr_id => $label): ?>
                            <div class="filter-item">
                                <h5><?=$label?></h5>
                                <div class="input"></div>   
                                <div class="variants clearfix">
                                    <? foreach ($filter[$attr_id] as $key => $col): ?>
                                        <div>
                                            <? foreach ($col as $item): ?>
                                                <label>
                                                    <input type="checkbox" name="arr[<?=$attr_id?>][]" value="<?=$item['variant_id']?>" <?=$item['checked']?>>
                                                    <span onselectstart="return false;"><?=$item['value']?></span>
                                                </label>
                                            <? endforeach; ?>
                                        </div>
                                    <? endforeach; ?>
                                </div>
                            </div>
                        <? endforeach; ?>
                        <h5>Ценовой диапазон</h5>
                        <div class="slide-type">
                            <input class="min-val price" type="text" maxlength="6" name="int[51][min]" placeholder="Мин.">
                            <span class="dash">-</span>
                            <input class="max-val price" type="text" maxlength="6" name="int[51][max]" placeholder="Макс.">
                            <div data-min-cur="<?=$_GET['int'][51]['min']?>" data-min="<?=$params[$_GET['type']]['PRICE_MIN']?>" data-max-cur="<?=$_GET['int'][51]['max']?>" data-max="<?=$params[$_GET['type']]['PRICE_MAX']?>" data-step="100" class="slider-range"></div>
                        </div>  
                        <div class="filter-butt-cont">
                            <input type="submit" class="b-black-butt" value="Принять">
                        </div>
                          
                    </div>
                </div>
            </div>
            <div class="right good-list">
                <ul class="navigation clearfix">
                    <li><a href="<?=Yii::app()->createUrl('/kolesoonline')?>"></a></li>
                    <!-- <li><a href="#">Каталог</a></li> -->
                    <li><a href="#"><?=$this->params[$_GET['type']]["NAME"]?></a></li>
                </ul>
                <h3 class="category-title">раздел <?=$this->params[$_GET['type']]["NAME"]?></h3>
                <div class="b-sort clearfix">
                    <div class="left clearfix">
                        <h4 class="left">Сортировать по:</h4>
                        <ul class="left clearfix">
                            <? foreach ($sort_arr as $key => $value): ?>
                            <? if(isset($_GET['sort']['field']) && $_GET['sort']['field']==$key): ?>
                                    <li class="active <? if($sort_type =='ASC') echo 'up'; ?>">
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
                <ul class="goods clearfix">  
                    <?php $this->renderPartial('_list', array('goods' => $goods,'last' => $last,'params' => $params,'type' => $_GET['type'])); ?>
                </ul>
                <div class="load" style="width:100px; height:100px; background-color: red; display:inline-block;"></div>
            </div>
            <?php $this->endWidget(); ?>            
        </div>
    </div>
    <? else: ?>
        <h3 class="b-no-goods">Товаров не найдено</h3>
    <? endif; ?>
</div>