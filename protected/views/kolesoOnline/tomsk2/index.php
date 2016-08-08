<div class="b-content">
    <div class="b-block">
        <div class="b b-main-slider">
            <div class="slide" style="background-image: url('<?php echo Yii::app()->request->baseUrl; ?>/html/i/back-main.jpg');">
                <? if(!$mobile): ?>
                    <h2>Самый большой в Томске выбор БУ шин и<br>дисков R16-R18</h2>
                    <p>Шиномонтаж, правка дисков, комиссия.<br>Качество с гарантией.</p>
                <? else: ?>
                    <h2 style="text-align:center; margin-top: 50px;">Самый большой в Томске выбор б/у шин и дисков R16-R18</h2>
                <? endif; ?>
            </div>
        </div>
    </div>
    <div class="b b-filters">
        <div class="b-block main-tabs">
            <ul class="tabs clearfix">
                <li class="black"><a href="#tabs-tire"><span class="tire-icon icon">Шины</span></a></li>
                <li class="black"><a href="#tabs-disc"><span class="disc-icon icon">Диски</span></a></li>
                <li class="black"><a href="#tabs-wheel"><span class="wheel-icon icon">Колеса</span></a></li>
            </ul>
            <div id="tabs-tire" class="b-subtabs gradient-grey">
                <?php $form=$this->beginWidget('CActiveForm', array(
                    'action' => Yii::app()->createUrl('/kolesoOnline/category',array("type" => 1)),
                    'method' => 'GET'
                )); ?>
                <ul class="subtabs clearfix">
                    <li class="left"><a href="#subtabs-tire-1">Параметры шины</a></li>
                    <li class="left"><a href="#subtabs-tire-2">Марка</a></li>
                    <!-- <li class="left"><a href="#subtabs-tire-3">Цена</a></li> -->
                </ul>
                <div id="subtabs-tire-1" class="clearfix subtab">
                    <div class="left b-subtab-col b-subtab-size">
                        <h3>Размер</h3>
                        <div class="b-subtab-block clearfix">
                            <div class="left">
                                <label for="width">Ширина</label>
                                <select name="arr[7][]" id="width">
                                    <option></option>
                                    <? foreach ($tire_filter[7] as $key => $col): ?>
                                        <? foreach ($col as $item): ?>
                                            <option value="<?=$item['variant_id']?>"<?if($item['checked']):?> selected<?endif;?>><?=$item['value']?></option>
                                        <? endforeach; ?>
                                    <? endforeach; ?>
                                </select>
                            </div>
                            <div class="left">
                                <label for="height">Высота профиля</label>
                                <select name="arr[8][]" id="height">
                                    <option></option>
                                    <? foreach ($tire_filter[8] as $key => $col): ?>
                                        <? foreach ($col as $item): ?>
                                            <option value="<?=$item['variant_id']?>"<?if($item['checked']):?> selected<?endif;?>><?=$item['value']?></option>
                                        <? endforeach; ?>
                                    <? endforeach; ?>
                                </select>
                            </div>
                            <div class="left">
                                <label for="diameter">Посадочный диаметр</label>
                                <select name="arr[9][]" id="diameter">
                                    <option></option>
                                    <? foreach ($tire_filter[9] as $key => $col): ?>
                                        <? foreach ($col as $item): ?>
                                            <option value="<?=$item['variant_id']?>"<?if($item['checked']):?> selected<?endif;?>><?=$item['value']?></option>
                                        <? endforeach; ?>
                                    <? endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <h3>Цена</h3>
                        <div class="b-subtab-block b-subtab-price clearfix">
                            <div class="left">
                                <input class="min-val price" type="text" maxlength="6" name="int[20][min]" placeholder="Мин.">
                            </div>
                            <div class="left">
                                <span>до</span>
                            </div>
                            <div class="left">
                                <input class="max-val price" type="text" maxlength="6" name="int[20][max]" placeholder="Макс.">
                            </div>
                        </div>
                    </div>
                    <div class="left b-subtab-col b-subtab-type">
                        <h3>Вид шины</h3>
                        <div class="b-subtab-block">
                            <? foreach ($tire_filter[23] as $key => $col): ?>
                                <? foreach ($col as $item): ?>     
                                    <div class="b-subtab-check">
                                        <input type="checkbox" name="arr[23][]" id="type-<?=$item['variant_id']?>" value="<?=$item['variant_id']?>"<?if($item['checked']):?> checked<?endif;?>>
                                        <label for="type-<?=$item['variant_id']?>"><?=$item['value']?></label>
                                    </div>
                                <? endforeach; ?>
                            <? endforeach; ?>
                        </div>
                    </div>
                    <div class="left b-subtab-col b-subtab-count">
                        <h3>Количество</h3>
                        <div class="b-subtab-block clearfix">
                            <? foreach ($tire_filter[28] as $key => $col): ?>
                                <div class="left">
                                <? foreach ($col as $item): ?>
                                    <div class="b-subtab-check">
                                        <input type="checkbox" name="arr[28][]" id="count-<?=$item['variant_id']?>" value="<?=$item['variant_id']?>"<?if($item['checked']):?> checked<?endif;?>>
                                        <label for="count-<?=$item['variant_id']?>"><?=$item['value']?> шт.</label>
                                    </div>
                                <? endforeach; ?>
                                </div>
                            <? endforeach; ?>
                        </div>
                    </div>
                </div>
                <div id="subtabs-tire-2" class="clearfix subtab">
                    <div class="left b-subtab-col b-subtab-mark">
                        <h3>Марка</h3>
                        <div class="b-subtab-block clearfix">
                            <? foreach ($tire_filter[16] as $key => $col): ?>
                                <div class="left">
                                <? foreach ($col as $item): ?>
                                    <div class="b-subtab-check">
                                        <input type="checkbox" name="arr[16][]" id="model-<?=$item['variant_id']?>" value="<?=$item['variant_id']?>"<?if($item['checked']):?> checked<?endif;?>>
                                        <label for="model-<?=$item['variant_id']?>"><?=$item['value']?></label>
                                    </div>
                                <? endforeach; ?>
                                </div>
                            <? endforeach; ?>
                        </div>
                    </div>
                </div>
                <!-- <div id="subtabs-tire-3" class="clearfix subtab">
                    <div class="left b-subtab-col b-subtab-price">
                        
                    </div>
                    <div class="left b-subtab-col b-subtab-year">
                        <h3>Год выпуска шины</h3>
                        <div class="b-subtab-block clearfix">
                            <div class="left">
                                <select name="int[10][min]" id="year-min">
                                    <option></option>
                                    <? foreach ($tire_filter[10] as $key => $col): ?>
                                        <? foreach ($col as $item): ?>
                                            <option value="<?=$item['variant_id']?>"<?if($item['checked']):?> selected<?endif;?>><?=$item['value']?></option>
                                        <? endforeach; ?>
                                    <? endforeach; ?>
                                </select>
                            </div>
                            <div class="left">
                                <span>до</span>
                            </div>
                            <div class="left">
                                <select name="int[10][max]" id="year-max">
                                    <option></option>
                                    <? foreach ($tire_filter[10] as $key => $col): ?>
                                        <? foreach ($col as $item): ?>
                                            <option value="<?=$item['variant_id']?>"<?if($item['checked']):?> selected<?endif;?>><?=$item['value']?></option>
                                        <? endforeach; ?>
                                    <? endforeach; ?>
                                </select>
                            </div>
                        </div>
                    </div>
                </div> -->
                <div class="filter-butt-cont">
                    <input type="submit" class="b-black-butt" value="Найти">
                </div>
                <?php $this->endWidget(); ?>
            </div>
            <div id="tabs-disc" class="gradient-grey">
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
                            <? if( $attr_id != 28 && $attr_id != 27): ?>   
                            <div class="filter-item">
                                <h5><?=$label?></h5>
                                <div class="input"></div>   
                                    <div class="variants clearfix">
                                        <? if($mobile): ?>
                                        <h4><?=$label?></h4>
                                        <? endif; ?>
                                        <? if(count($disc_filter[$attr_id])) foreach ($disc_filter[$attr_id] as $key => $col): ?>
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
                            <div class="slide-type clearfix">
                                <div class="left">
                                    <h3>Цена (за комплект) <span>от</span></h3>
                                   <input class="min-val price" type="text" maxlength="6" name="int[20][min]" placeholder="Мин.">
                                    <span class="dash">до</span>
                                    <input class="max-val price" type="text" maxlength="6" name="int[20][max]" placeholder="Макс.">
                                </div>
                                <div class="slider-range-cont">
                                    <div data-min-cur="<?=$_SESSION['FILTER'][2]['int'][20]['min']?>" data-min="<?=$params[2]['PRICE_MIN']?>" data-max-cur="<?=$_SESSION['FILTER'][2]['int'][20]['max']?>" data-max="<?=$params[2]['PRICE_MAX']?>" data-step="100" class="slider-range left"></div>
                                </div>
                            </div>  
                        <? endif; ?>
                    </div>
                    <? $ind++; endforeach; ?>
                    <div class="filter-butt-cont">
                        <input type="submit" class="b-black-butt" value="Найти">
                    </div>
                <?php $this->endWidget(); ?>
            </div>
            <div id="tabs-wheel" class="load-tabs gradient-grey">
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
                                    <? if(count($wheel_filter[$attr_id])) foreach ($wheel_filter[$attr_id] as $key => $col): ?>
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
                                <h3>Цена (за комплект) <span>от</span></h3>
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
               <!--  <div class="gradient-grey">
                    <h3>Категории товаров</h3>
                    <ul>
                        <li><a href="<?=Yii::app()->createUrl('/kolesoOnline/category',array('type' => 2))?>"><span class="disc-icon icon">Диски</span></a></li>
                        <li><a href="<?=Yii::app()->createUrl('/kolesoOnline/category',array('type' => 1))?>"><span class="tire-icon icon">Шины</span></a></li>
                        <li><a href="<?=Yii::app()->createUrl('/kolesoOnline/category',array('type' => 3))?>"><span class="wheel-icon icon">Колеса</span></a></li>
                    </ul>
                </div> -->
                <div  style="box-shadow: none; border-radius: 0px;">
                <!-- <h3>О нас</h3> -->
                <div id="vk_groups"></div>
                <script type="text/javascript">
                VK.Widgets.Group("vk_groups", {mode: 0, width: "245", height: "671", color1: 'F2F2F2', color2: '222222', color3: '222222'}, 118079986);
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
                        <?php $this->renderPartial('tomsk/_list', array('goods' => $discs,'last' => 1,'params' => $params,'type' => 2,'dynamic'=>$dynamic)); ?>
                    </ul>
                </div>
                <div id="popular-tire">
                    <ul class="goods clearfix">
                       <?php $this->renderPartial('tomsk/_list', array('goods' => $tires,'last' => 1,'params' => $params,'type' => 1,'dynamic'=>$dynamic)); ?>
                    </ul>
                </div>
                <div id="popular-wheel">
                    <ul class="goods clearfix">
                       <?php $this->renderPartial('tomsk/_list', array('goods' => $wheels,'last' => 1,'params' => $params,'type' => 3,'dynamic'=>$dynamic)); ?>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <?if($thanks):?>
        <p style="display:none;" id="view-thanks" class="fancy" data-block="#b-popup-2">
    <? endif; ?> 
</div>