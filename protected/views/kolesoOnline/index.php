<div class="b-content">
    <div class="b b-main-slider">
        <div class="slide" style="background-image: url('<?php echo Yii::app()->request->baseUrl; ?>/html/i/back-main.jpg');">
            <div class="b-block">
                <? if(!$mobile): ?>
                    <h2>Самый большой в России выбор б/у шин и дисков</h2>
                    <p>Отправка во все регионы. Широкая сеть представительств. Единая гарантийная политика и стандарты проверки товара. Профессионалы своего дела.</p>
                <? else: ?>
                    <h2 style="text-align:center; margin-top: 50px;">Самый большой в России<br>выбор б/у шин и дисков</h2>
                <? endif; ?>
            </div>
        </div>
        <div class="slide" style="background-image: url('<?php echo Yii::app()->request->baseUrl; ?>/html/i/back-main-2.jpg');">
            <div class="b-block">
                <h2>Получи 30 литров бензина за репост!</h2>
                <p>Все подробности в нашей группе вконтакте:<br><a href="http://vk.com/kolesoonline" target="_blank">http://vk.com/kolesoonline</a></p>
            </div>
        </div>
    </div>
    <div class="b b-filters">
        <div class="b-block gradient-grey main-tabs">
            <ul class="tabs clearfix">
                <li class="gradient-lightBlack"><a href="#tabs-disc"><span class="disc-icon icon">Подбор дисков</span></a></li>
                <li class="gradient-lightBlack"><a href="#tabs-tire"><span class="tire-icon icon">Подбор шин</span></a></li>
                <li class="gradient-lightBlack"><a href="#tabs-wheel"><span class="wheel-icon icon">Подбор Колес</span></a></li>
            </ul>
            <div id="tabs-disc">
                <?php $form=$this->beginWidget('CActiveForm', array(
                    'action' => Yii::app()->createUrl('/kolesoOnline/category',array("type" => 2)),
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
                    <? $ind = 0; foreach ($params[2]["FILTER"] as $filters): ?>
                    <div class="filter-cont clearfix">
                        <? foreach ($filters as $attr_id => $label): ?>  
                            <? if( $attr_id != 28 ): ?>   
                            <div class="filter-item">
                                <h5><?=$label?></h5>
                                <div class="input"></div>   
                                    <div class="variants clearfix">
                                        <? if($mobile): ?>
                                        <h4><?=$label?></h4>
                                        <? endif; ?>
                                        <? foreach ($disc_filter[$attr_id] as $key => $col): ?>
                                            <ul class="wave">
                                                <? foreach ($col as $item): ?>
                                                <li>
                                                    <div>
                                                        <input type="checkbox" id="disc_<?=$item['variant_id']?>" name="arr[<?=$attr_id?>][]" value="<?=$item['variant_id']?>">
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
                            <? endif; ?>
                        <? endforeach; ?>
                        <? if($ind == 1): ?>
                            <div class="slide-type b-third clearfix">
                                <div class="left">
                                    <h5>Ценовой диапазон</h5>
                                    <input class="min-val price" type="text" maxlength="6" name="int[20][min]" placeholder="Мин.">
                                    <span class="dash">-</span>
                                    <input class="max-val price" type="text" maxlength="6" name="int[20][max]" placeholder="Макс.">
                                </div>
                                <div class="slider-range-cont">
                                    <div data-min-cur="<?=$_SESSION['FILTER'][2]['int'][20]['min']?>" data-min="<?=$params[2]['PRICE_MIN']?>" data-max-cur="<?=$_SESSION['FILTER'][2]['int'][20]['max']?>" data-max="<?=$params[2]['PRICE_MAX']?>" class="slider-range left"></div>
                                </div>
                            </div>  
                        <? endif; ?>
                    </div>
                    <? $ind++; endforeach; ?>
                    <!-- <div class="filter-cont">
                        <div class="slide-type clearfix">
                            <div class="left">
                                <h3>Ценовой диапазон <span>от</span></h3>
                               <input class="min-val price" type="text" maxlength="6" name="int[20][min]" placeholder="Мин.">
                                <span class="dash">до</span>
                                <input class="max-val price" type="text" maxlength="6" name="int[20][max]" placeholder="Макс.">
                            </div>
                            <div class="slider-range-cont">
                                <div data-min-cur="<?=$_SESSION['FILTER'][2]['int'][20]['min']?>" data-min="<?=$params[2]['PRICE_MIN']?>" data-max-cur="<?=$_SESSION['FILTER'][2]['int'][20]['max']?>" data-max="<?=$params[2]['PRICE_MAX']?>" data-step="100" class="slider-range left"></div>
                            </div>
                        </div>  
                    </div> -->
                    <div class="filter-butt-cont">
                        <input type="submit" class="b-black-butt" value="Найти">
                    </div>
                <?php $this->endWidget(); ?>
            </div>
            <div id="tabs-tire" class="load-tabs">
                <?php $form=$this->beginWidget('CActiveForm', array(
                    'action' => Yii::app()->createUrl('/kolesoOnline/category',array("type" => 1)),
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
                        </div>
                    </div>
                    <? $ind = 0; foreach ($params[1]["FILTER"] as $filters): ?>
                    <div class="filter-cont clearfix">
                        <? foreach ($filters as $attr_id => $label): ?>
                            <div class="filter-item">
                                <h5><?=$label?></h5>
                                <div class="input"></div>             
                                <div class="variants clearfix">
                                    <? if($mobile): ?>
                                    <h4><?=$label?></h4>
                                    <? endif; ?>
                                    <? foreach ($tire_filter[$attr_id] as $key => $col): ?>
                                        <ul class="wave">
                                            <? foreach ($col as $item): ?>
                                                <li>
                                                    <div>
                                                        <input type="checkbox" id="tire_<?=$item['variant_id']?>" type="checkbox" name="arr[<?=$attr_id?>][]" value="<?=$item['variant_id']?>">
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
                        <? if($ind == 1): ?>
                        <div class="slide-type b-third clearfix">
                            <div class="left">
                                <h5>Ценовой диапазон</h5>
                                <input class="min-val price" type="text" maxlength="6" name="int[20][min]" placeholder="Мин.">
                                <span class="dash">-</span>
                                <input class="max-val price" type="text" maxlength="6" name="int[20][max]" placeholder="Макс.">
                            </div>
                            <div class="slider-range-cont">
                                <div data-min-cur="<?=$_SESSION['FILTER'][1]['int'][20]['min']?>" data-min="<?=$params[1]['PRICE_MIN']?>" data-max-cur="<?=$_SESSION['FILTER'][1]['int'][20]['max']?>" data-max="<?=$params[1]['PRICE_MAX']?>" class="slider-range left"></div>
                            </div>
                        </div> 
                        <? endif; ?>
                    </div>
                    <? $ind++; endforeach; ?>
                    <!-- <div class="filter-cont">
                        <div class="slide-type clearfix">
                            <div class="left">
                                <h3>Ценовой диапазон <span>от</span></h3>
                                <input class="min-val price" type="text" maxlength="6" name="int[20][min]" placeholder="Мин.">
                                <span class="dash">до</span>
                                <input class="max-val price" type="text" maxlength="6" name="int[20][max]" placeholder="Макс.">
                            </div>
                            <div class="slider-range-cont">
                                <div data-min-cur="<?=$_SESSION['FILTER'][1]['int'][20]['min']?>" data-min="<?=$params[1]['PRICE_MIN']?>" data-max-cur="<?=$_SESSION['FILTER'][1]['int'][20]['max']?>" data-max="<?=$params[1]['PRICE_MAX']?>" data-step="100" class="slider-range left"></div>
                            </div>
                        </div>  
                    </div> -->
                    <div class="filter-butt-cont">
                        <input type="submit" class="b-black-butt" value="Найти">
                    </div>
                <?php $this->endWidget(); ?>
            </div>
            <div id="tabs-wheel" class="load-tabs">
                <?php $form=$this->beginWidget('CActiveForm', array(
                    'action' => Yii::app()->createUrl('/kolesoOnline/category',array("type" => 3)),
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
                        </div>
                    </div>
                    <? foreach ($params[3]["FILTER"] as $filters): ?>
                    <div class="filter-cont clearfix">
                        <? foreach ($filters as $attr_id => $label): ?>
                            <div class="filter-item">
                                <h5><?=$label?></h5>
                                <div class="input"></div>    
                                <div class="variants clearfix">
                                    <? if($mobile): ?>
                                    <h4><?=$label?></h4>
                                    <? endif; ?>
                                    <? foreach ($wheel_filter[$attr_id] as $key => $col): ?>
                                        <ul class="wave">
                                            <? foreach ($col as $item): ?>
                                                <li>
                                                    <div>
                                                        <input type="checkbox" id="wheel_<?=$item['variant_id']?>" type="checkbox" name="arr[<?=$attr_id?>][]" value="<?=$item['variant_id']?>">
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
                    <? endforeach; ?>
                    <div class="filter-cont">  
                        <div class="slide-type clearfix">
                            <div class="left">
                                <h3>Ценовой диапазон <span>от</span></h3>
                               <input class="min-val price" type="text" maxlength="6" name="int[20][min]" placeholder="Мин.">
                                <span class="dash">до</span>
                                <input class="max-val price" type="text" maxlength="6" name="int[20][max]" placeholder="Макс.">
                            </div>
                            <div class="slider-range-cont">
                                <div data-min-cur="<?=$_SESSION['FILTER'][3]['int'][20]['min']?>" data-min="<?=$params[3]['PRICE_MIN']?>" data-max-cur="<?=$_SESSION['FILTER'][3]['int'][20]['max']?>" data-max="<?=$params[3]['PRICE_MAX']?>" class="slider-range left"></div>
                            </div>
                        </div> 
                    </div>
                    <div class="filter-butt-cont">
                        <input type="submit" class="b-black-butt" value="Найти">
                    </div>
                <?php $this->endWidget(); ?>
            </div>
        </div>
    </div>
    <div class="b b-popular">
        <div class="b-block clearfix">
            <div class="grey-block main-category left">
                <div class="gradient-grey">
                    <h3>Категории товаров</h3>
                    <ul>
                        <li><a href="<?=Yii::app()->createUrl('/kolesoOnline/category',array('type' => 2))?>"><span class="disc-icon icon">Диски</span></a></li>
                        <li><a href="<?=Yii::app()->createUrl('/kolesoOnline/category',array('type' => 1))?>"><span class="tire-icon icon">Шины</span></a></li>
                        <li><a href="<?=Yii::app()->createUrl('/kolesoOnline/category',array('type' => 3))?>"><span class="wheel-icon icon">Колеса</span></a></li>
                    </ul>
                </div>
                <div  style="box-shadow: none; border-radius: 0px;" class="gradient-grey">
                <!-- <h3>О нас</h3> -->
                <div id="vk_groups"></div>
                <script type="text/javascript">
                VK.Widgets.Group("vk_groups", {mode: 0, width: "245", height: "361", color1: 'F2F2F2', color2: '3c3c3c', color3: '534F59'}, 118079986);
                </script>
                <!-- <p>Лучший выбор автомобильных б/у шин и дисков из Японии в России.<br>Удобный поиск, доступные цены, честное описание и подробные фото.<br>Мы постоянно работаем над расширением географии наших представительств на территории РФ, что бы доставить товар в короткие сроки и предоставить возможность оплатить покупку удобным для Вас способом.</p> -->
                </div>
            </div>
            <div class="popular-good right main-tabs after-load">
                <h3 class="category-title">Популярные товары</h3>
                <ul class="popular-category clearfix">
                    <li><a href="#popular-disc"><span class="disc-icon icon">Диски</span></a></li>
                    <li><a href="#popular-tire"><span class="tire-icon icon">Шины</span></a></li>
                    <li><a href="#popular-wheel"><span class="wheel-icon icon">Колеса</span></a></li>
                </ul>
                <div id="popular-disc">
                    <ul class="goods clearfix">
                        <?php $this->renderPartial('_list', array('goods' => $discs,'last' => 1,'params' => $params,'type' => 2,'dynamic'=>$dynamic)); ?>
                    </ul>
                </div>
                <div id="popular-tire">
                    <ul class="goods clearfix">
                       <?php $this->renderPartial('_list', array('goods' => $tires,'last' => 1,'params' => $params,'type' => 1,'dynamic'=>$dynamic)); ?>
                    </ul>
                </div>
                <div id="popular-wheel">
                    <ul class="goods clearfix">
                       <?php $this->renderPartial('_list', array('goods' => $wheels,'last' => 1,'params' => $params,'type' => 3,'dynamic'=>$dynamic)); ?>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <?if($thanks):?>
        <p style="display:none;" id="view-thanks" class="fancy" data-block="#b-popup-2">
    <? endif; ?> 
</div>
