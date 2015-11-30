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
<div class="b-category">
    <div class="b-block clearfix">
        <div class="grey-block left">
            <div class="gradient-grey">
                <h3>Фильтры</h3>
                <div class="filter-block">
                    <?php $form=$this->beginWidget('CActiveForm', array(
                        'id'=>'filter',
                        'action' => Yii::app()->createUrl('/kolesoOnline/category',array("type" => $_GET['type'])),
                        'enableAjaxValidation'=>false,
                        'method' => 'GET'
                    )); ?>
                    <!-- <h5>Сезонность</h5> -->
                    <div class="tire-type clearfix">    
                        <input id="tire-winter" type="radio" name="tire-type" value="0">
                        <label for="tire-winter">Зимние нешипованные</label>
                        <input id="tire-spike" type="radio" name="tire-type" value="1">
                        <label for="tire-spike">Зимние шипованные</label>
                        <input id="tire-summer" type="radio" name="tire-type" value="2">
                        <label for="tire-summer">Летние</label>
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
                        <div data-min="<?=$params[$_GET['type']]['PRICE_MIN']?>" data-max="<?=$params[$_GET['type']]['PRICE_MAX']?>" data-step="100" class="slider-range"></div>
                    </div>  
                    <div class="filter-butt-cont">
                        <input type="submit" class="b-black-butt" value="Принять">
                    </div>
                    <?php $this->endWidget(); ?>              
                </div>
            </div>
        </div>
        <div class="right good-list">
            <ul class="navigation clearfix">
                <li><a href="#"></a></li>
                <li><a href="#">Каталог</a></li>
                <li><a href="#">Шины</a></li>
            </ul>
            <h3 class="category-title">раздел <?=$this->params[$_GET['type']]["NAME"]?></h3>
            <div class="b-sort clearfix">
                <div class="left clearfix">
                    <h4 class="left">Сортировать по:</h4>
                    <ul class="left clearfix">
                        <li class="active">Цене</li>
                        <li>Диаметру</li>
                        <li>Ширине</li>
                        <li>Профилю</li>
                    </ul>
                </div>
                <div class="right clearfix">
                    <h4 class="left">Вид:</h4>
                    <span class="active grid-view view"></span>
                    <span class="list-view view"></span>
                </div>
            </div>
            <ul class="goods clearfix">  
                <? foreach ($goods as $good): ?>                 
                    <li class="gradient-grey" data-href="<?=Yii::app()->createUrl('/kolesoonline/detail',array('type'=> $_GET['type'],"id"=>$good->fields_assoc[3]->value))?>">
                        <div class="good-img" style="background-image: url(<? $images = $this->getImages($good); echo $images[0];?>);"></div>
                        <div class="params-cont">
                            <? if($_GET['type'] == 2): ?>
                                <h4><?=Interpreter::generate($this->params[$_GET['type']]["TITLE_CODE"], $good);?></h4>
                                <? $price = 0; $price = ($good->fields_assoc[51])?$good->fields_assoc[51]->value:0; $order = Interpreter::generate($this->params[$_GET['type']]["ORDER"], $good); ?>
                                <h5><span><?=$price==0 ? Yii::app()->params["zeroPrice"] : number_format( $price, 0, ',', ' ' )." р."?> <span><? if($order) echo "(".$order.")"; ?></span> + 800 р.</h5>
                                <h5>доставка в г. Томск</h5>
                                <h6><?=Interpreter::generate($this->params[$_GET['type']]["TITLE_2_CODE"], $good);?></h6>
                                <h3>Износ: <span>82%</span></h3>
                                <h3><?=$params[$_GET['type']]["CATEGORY"]["YEAR"]["LABEL"]?>:<span><?=$good->fields_assoc[$params[$_GET['type']]["CATEGORY"]["YEAR"]['ID']]->value." ".$params[$_GET['type']]["CATEGORY"]["YEAR"]['UNIT']?></h3>
                            <? elseif($_GET['type'] == 1): ?>

                            <? endif; ?>

                            <a href="#" class="b-orange-butt">Купить</a>
                        </div>
                    </li>
                <? endforeach; ?>
            </ul>
        </div>
    </div>
</div>
<? else: ?>
    <h3 class="b-no-goods">Товаров не найдено</h3>
<? endif; ?>