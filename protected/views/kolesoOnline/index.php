<?php $this->renderPartial('_header', array('cities' => $cities)); ?>
<div class="b-content">
    <div class="b b-main-slider">
        <div class="slide" style="background-image: url('<?php echo Yii::app()->request->baseUrl; ?>/html/i/back-main.jpg');">
            <div class="b-block">
                <h2>Широкий ассортимент</h2>
                <p>Только в нашем магазине вы найдете именно те колеса, которые вам нужны.</p>
            </div>
        </div>
        <div class="slide" style="background-image: url('<?php echo Yii::app()->request->baseUrl; ?>/html/i/back-main.jpg');">
            <div class="b-block">
                <h2>Широкий ассортимент</h2>
                <p>Только в нашем магазине вы найдете именно те колеса, которые вам нужны.</p>
            </div>
        </div>
    </div>
    <div class="b b-filters">
        <div class="b-block gradient-grey main-tabs">
            <ul class="tabs clearfix">
                <li class="gradient-lightBlack"><a href="#tabs-disc"><span class="disc-icon icon">Подбор дисков</span></a></li>
                <li class="gradient-lightBlack"><a href="#tabs-tire"><span class="tire-icon icon">Подбор шин</span></a></li>
                <!-- <li class="gradient-lightBlack"><a href="#tabs-wheel"><span class="wheel-icon icon">Подбор Колес</span></a></li> -->
            </ul>
            <div id="tabs-disc">
                <?php $form=$this->beginWidget('CActiveForm', array(
                    'id'=>'filter',
                    'action' => Yii::app()->createUrl('/kolesoOnline/category',array("type" => 2)),
                    'enableAjaxValidation'=>false,
                    'method' => 'GET'
                )); ?>
                    <!-- <div class="filter-cont">
                        <div class="tire-type clearfix">    
                            <input id="tire-winter" type="radio" name="tire-type" value="0">
                            <label for="tire-winter">Зимние нешaafsипованные</label>
                            <input id="tire-spike" type="radio" name="tire-type" value="1">
                            <label for="tire-spike">Зимние шипованные</label>
                            <input id="tire-summer" type="radio" name="tire-type" value="2">
                            <label for="tire-summer">Летние</label>
                        </div>
                    </div> -->
                    <? foreach ($params[2]["FILTER"] as $filters): ?>
                    <div class="filter-cont clearfix">
                        <? foreach ($filters as $attr_id => $label): ?>
                            <div class="filter-item">
                                <h5><?=$label?></h5>
                                <div class="input"></div>   
                                <div class="variants clearfix">
                                    <? foreach ($disc_filter[$attr_id] as $key => $col): ?>
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
                    </div>
                    <? endforeach; ?>
                    <div class="filter-cont">
                        <div class="slide-type clearfix">
                            <div class="left">
                                <h3>Ценовой диапазон <span>от</span></h3>
                               <input class="min-val price" type="text" maxlength="6" name="int[51][min]" placeholder="Мин.">
                                <span class="dash">до</span>
                                <input class="max-val price" type="text" maxlength="6" name="int[51][max]" placeholder="Макс.">
                            </div>
                            <div class="slider-range-cont">
                                <div data-min="<?=$params[2]['PRICE_MIN']?>" data-max="<?=$params[2]['PRICE_MAX']?>" data-step="100" class="slider-range left"></div>
                            </div>
                        </div>  
                    </div>
                    <div class="filter-butt-cont">
                        <input type="submit" class="b-black-butt" value="Найти">
                    </div>
                <?php $this->endWidget(); ?>
            </div>
            <div id="tabs-tire">
                <?php $form=$this->beginWidget('CActiveForm', array(
                    'id'=>'filter',
                    'action' => Yii::app()->createUrl('/kolesoOnline/category',array("type" => 1)),
                    'enableAjaxValidation'=>false,
                    'method' => 'GET'
                )); ?>
                    <div class="filter-cont">
                        <div class="tire-type clearfix">    
                            <? foreach ($tire_filter[$params[1]["SEASON"]] as $key => $col): ?>
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
                    </div>
                    <? foreach ($params[1]["FILTER"] as $filters): ?>
                    <div class="filter-cont clearfix">
                        <? foreach ($filters as $attr_id => $label): ?>
                            <div class="filter-item">
                                <h5><?=$label?></h5>
                                <div class="input"></div>   
                                <div class="variants clearfix">
                                    <? foreach ($tire_filter[$attr_id] as $key => $col): ?>
                                        <div>
                                            <? foreach ($col as $item): ?>
                                                <label>
                                                    <input type="checkbox" name="arr[<?=$attr_id?>][]" value="<?=$item['variant_id']?>">
                                                    <span onselectstart="return false;"><?=str_replace(" ", "&nbsp;", $item['value'])?></span>
                                                </label>
                                            <? endforeach; ?>
                                        </div>
                                    <? endforeach; ?>
                                </div>
                            </div>
                        <? endforeach; ?>
                    </div>
                    <? endforeach; ?>
                    <div class="filter-cont">
                        <div class="slide-type clearfix">
                            <div class="left">
                                <h3>Ценовой диапазон <span>от</span></h3>
                                <input class="min-val price" type="text" maxlength="6" name="int[51][min]" placeholder="Мин.">
                                <span class="dash">до</span>
                                <input class="max-val price" type="text" maxlength="6" name="int[51][max]" placeholder="Макс.">
                            </div>
                            <div class="slider-range-cont">
                                <div data-min-cur="<?=$params[1]['PRICE_MIN']?>" data-min="<?=$params[1]['PRICE_MIN']?>" data-max-cur="<?=$params[1]['PRICE_MAX']?>" data-max="<?=$params[1]['PRICE_MAX']?>" data-step="100" class="slider-range left"></div>
                            </div>
                        </div>  
                    </div>
                    <div class="filter-butt-cont">
                        <input type="submit" class="b-black-butt" value="Найти">
                    </div>
                <?php $this->endWidget(); ?>
            </div>
            <!-- <div id="tabs-wheel">
                <form action="#" method="GET">
                    <div class="filter-cont">
                        <div class="tire-type clearfix">    
                            <input id="tire-winter" type="radio" name="tire-type" value="0">
                            <label for="tire-winter">Зимние нешипованные</label>
                            <input id="tire-spike" type="radio" name="tire-type" value="1">
                            <label for="tire-spike">Зимние шипованные</label>
                            <input id="tire-summer" type="radio" name="tire-type" value="2">
                            <label for="tire-summer">Летние</label>
                        </div>
                    </div>
                    <div class="filter-cont clearfix">
                        <div class="filter-item">
                            <h5>Производитель</h5>
                            <div class="input"></div>   
                        </div>
                        <div class="filter-item">
                            <h5>Тип</h5>
                            <div class="input"></div>   
                        </div>
                        <div class="filter-item">
                            <h5>Состояние</h5>
                            <div class="input"></div>   
                        </div>
                        <div class="filter-item">
                            <h5>Посадочный диаметр</h5>
                            <div class="input"></div>   
                        </div>
                    </div>
                    <div class="filter-cont clearfix">
                        <div class="filter-item">
                            <h5>Сверловка</h5>
                            <div class="input"></div>   
                        </div>
                        <div class="filter-item">
                            <h5>Ширина диска</h5>
                            <div class="input"></div>   
                        </div>
                        <div class="filter-item">
                            <h5>Вылет</h5>
                            <div class="input"></div>   
                        </div>
                    </div>
                </form>
            </div> -->
        </div>
    </div>
    <div class="b b-popular">
        <div class="b-block clearfix">
            <div class="grey-block main-category left">
                <div class="gradient-grey">
                    <h3>Категории товаров</h3>
                    <ul>
                        <li><a href="<?=Yii::app()->createUrl('/kolesoonline/category',array('type' => 2))?>"><span class="disc-icon icon">Диски</span></a></li>
                        <li><a href="<?=Yii::app()->createUrl('/kolesoonline/category',array('type' => 1))?>"><span class="tire-icon icon">Шины</span></a></li>
                        <!-- <li><a href="#"><span class="wheel-icon icon">Колеса</span></a></li> -->
                    </ul>
                </div>
                <div class="gradient-grey">
                    <h3>О нас</h3>
                    <p>Этот магазин сделан специально для оптимального выбора шин, дисков и других аксессуаров для вашего автомобиля.
                    <p>Удобство выбора и простота оформления покупки - вот два простых принципа, которые делают наш магазин лучшим.</p>
                </div>
            </div>
            <div class="popular-good right main-tabs">
                <h3 class="category-title">Популярные товары</h3>
                <ul class="popular-category clearfix">
                    <li><a href="#popular-disc"><span class="disc-icon icon">Диски</span></a></li>
                    <li><a href="#popular-tire"><span class="tire-icon icon">Шины</span></a></li>
                    <!-- <li><a href="#popular-wheel"><span class="wheel-icon icon">Колеса</span></a></li> -->
                </ul>
                <div id="popular-disc">
                    <ul class="goods clearfix">
                        <?php $this->renderPartial('_list', array('goods' => $discs,'last' => 1,'params' => $params,'type' => 2)); ?>
                    </ul>
                </div>
                <div id="popular-tire">
                    <ul class="goods clearfix">
                       <?php $this->renderPartial('_list', array('goods' => $tires,'last' => 1,'params' => $params,'type' => 1)); ?>
                    </ul>
                </div>
                <!-- <div id="popular-wheel">
                    <ul class="goods clearfix">
                        <li class="gradient-grey">
                            <div class="good-img" style="background-image: url('<?php echo Yii::app()->request->baseUrl; ?>/html/i/tire.jpg');"></div>
                            <div class="params-cont">
                                <h4>Yokohama DNA</h4>
                                <h5><span>8900 р.</span> + 800 р.</h5>
                                <h5>доставка в г. Томск</h5>
                                <h6>225/45/17  2 шт.</h6>
                                <h3>Износ: <span>82%</span></h3>
                                <h3>Год выпуска: <span>2013</span></h3>
                                <a href="#" class="b-orange-butt">Купить</a>
                            </div>
                        </li>
                    </ul>
                </div> -->
            </div>
        </div>
    </div>
</div>
