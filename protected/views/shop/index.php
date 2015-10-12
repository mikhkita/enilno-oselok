<?php $this->renderPartial('_menu', array()); ?>
<input type='hidden' name="int[51][min]" id="price_min" value="<?=$price_min?>">
<input type='hidden' name="int[51][max]" id="price_max" value="<?=$price_max?>">
<div class='b b-content'>
    <div class="b b-main">
        <div class="b-block clearfix">
            <?php $form=$this->beginWidget('CActiveForm', array(
                'id'=>'filter',
                'action' => Yii::app()->createUrl('/shop/index',array("type" => $_GET['type'])),
                'enableAjaxValidation'=>false,
                'method' => 'GET'
            )); ?>
            <div class="b-main-filter left">  
                <? if ($filter): ?>
                    <? if ($_GET['type'] == 1): ?>
                        <div class="filter-cont">
                            <h2>Сезонность</h2>
                            <div class="check-cont ">
                                <ul class="hor clearfix">
                                    <? foreach ($filter[23] as $item): ?>
                                    <li>
                                        <input type="checkbox" id="f<?=$item['variant_id']?>" name="arr[23][]" value="<?=$item['variant_id']?>" <?=$item['checked']?>>
                                        <label class="clearfix" for="f<?=$item['variant_id']?>">
                                            <span class="checked"></span>
                                            <span class="default"></span>   
                                            <h3><?=$item['value']?></h3>
                                        </label>
                                    </li>
                                    <? endforeach; ?>
                                </ul>
                            </div>  
                        </div>

                        <div class="filter-cont">
                            <h2>Город</h2>
                            <div class="check-cont ">
                                <ul class="hor clearfix">
                                    <? foreach ($filter[27] as $item): ?>
                                    <li>
                                        <input type="checkbox" id="f<?=$item['variant_id']?>" name="arr[27][]" value="<?=$item['variant_id']?>" <?=$item['checked']?>>
                                        <label class="clearfix" for="f<?=$item['variant_id']?>">
                                            <span class="checked"></span>
                                            <span class="default"></span>   
                                            <h3><?=$item['value']?></h3>
                                        </label>
                                    </li>
                                    <? endforeach; ?>
                                </ul>
                            </div>  
                        </div>
                    <? endif; ?>
                    <div class="filter-cont four-cols">
                        <h2>Диаметр</h2>
                        <div class="check-cont">
                            <ul class="hor clearfix">
                                <? foreach ($filter[9] as $item): ?>
                                <li>
                                    <input type="checkbox" id="f<?=$item['variant_id']?>" name="arr[9][]" value="<?=$item['variant_id']?>" <?=$item['checked']?>>
                                    <label class="clearfix" for="f<?=$item['variant_id']?>">
                                        <span class="checked"></span>
                                        <span class="default"></span>   
                                        <h3><?=$item['value']?></h3>
                                    </label>
                                </li>
                                <? endforeach; ?>
                            </ul>
                        </div>  
                    </div>

                    <? if ($_GET['type'] == 1): ?>
                        <div class="filter-cont four-cols">
                            <h2>Ширина</h2>
                            <div class="check-cont">
                                <ul class="hor clearfix">
                                    <? foreach ($filter[7] as $item): ?>
                                    <li>
                                        <input type="checkbox" id="f<?=$item['variant_id']?>" name="arr[7][]" value="<?=$item['variant_id']?>" <?=$item['checked']?>>
                                        <label class="clearfix" for="f<?=$item['variant_id']?>">
                                            <span class="checked"></span>
                                            <span class="default"></span>   
                                            <h3><?=$item['value']?></h3>
                                        </label>
                                    </li>
                                    <? endforeach; ?>
                                </ul>
                            </div>  
                        </div>

                        <div class="filter-cont four-cols">
                            <h2>Профиль</h2>
                            <div class="check-cont">
                                <ul class="hor clearfix">
                                    <? foreach ($filter[8] as $item): ?>
                                    <li>
                                        <input type="checkbox" id="f<?=$item['variant_id']?>" name="arr[8][]" value="<?=$item['variant_id']?>" <?=$item['checked']?>>
                                        <label class="clearfix" for="f<?=$item['variant_id']?>">
                                            <span class="checked"></span>
                                            <span class="default"></span>   
                                            <h3><?=$item['value']?></h3>
                                        </label>
                                    </li>
                                    <? endforeach; ?>
                                </ul>
                            </div>  
                        </div>
                    <? endif; ?>

                    <? if ($_GET['type'] == 2): ?>
                        <div class="filter-cont three-cols">
                            <h2>Сверловка</h2>
                            <div class="check-cont">
                                <ul class="hor clearfix">
                                    <? foreach ($filter[5] as $item): ?>
                                    <li>
                                        <input type="checkbox" id="f<?=$item['variant_id']?>" name="arr[5][]" value="<?=$item['variant_id']?>" <?=$item['checked']?>>
                                        <label class="clearfix" for="f<?=$item['variant_id']?>">
                                            <span class="checked"></span>
                                            <span class="default"></span>   
                                            <h3><?=$item['value']?></h3>
                                        </label>
                                    </li>
                                    <? endforeach; ?>
                                </ul>
                            </div>  
                        </div>

                    <? endif; ?>

                    <div class="filter-cont">
                        <h2>Цена (руб)</h2>
                        <div class="slider-text clearfix">
                            <h3 class="left">от <span id="amount-l"><span></h3>
                            <h3 class="right" style="margin-right:7px;">до <span id="amount-r"><span></h3>
                            <input type='hidden' name="int[51][min]" id="price-min" >
                            <input type='hidden' name="int[51][max]" id="price-max" >
                            <input type='hidden' name="type" value="<?=$_GET['type']?>">
                        </div>
                        <div id="slider-range"></div>
                    </div>
                    <? if ($_GET['type'] == 1): ?>
                        <div class="filter-cont">
                            <h2>Модель</h2>
                            <div class="check-cont">
                                <ul class="hor clearfix">
                                    <? foreach ($filter[16] as $item): ?>
                                    <li>
                                        <input type="checkbox" id="f<?=$item['variant_id']?>" name="arr[16][]" value="<?=$item['variant_id']?>" <?=$item['checked']?>>
                                        <label class="clearfix" for="f<?=$item['variant_id']?>">
                                            <span class="checked"></span>
                                            <span class="default"></span>   
                                            <h3><?=$item['value']?></h3>
                                        </label>
                                    </li>
                                    <? endforeach; ?>
                                </ul>
                            </div>  
                        </div>
                    <? endif; ?>

                    <? if ($_GET['type'] == 2): ?>
                        <div class="filter-cont four-cols">
                            <h2>Ширина</h2>
                            <div class="check-cont">
                                <ul class="hor clearfix">
                                    <? foreach ($filter[31] as $item): ?>
                                    <li>
                                        <input type="checkbox" id="f<?=$item['variant_id']?>" name="arr[31][]" value="<?=$item['variant_id']?>" <?=$item['checked']?>>
                                        <label class="clearfix" for="f<?=$item['variant_id']?>">
                                            <span class="checked"></span>
                                            <span class="default"></span>   
                                            <h3><?=$item['value']?></h3>
                                        </label>
                                    </li>
                                    <? endforeach; ?>
                                </ul>
                            </div>  
                        </div>

                        <div class="filter-cont four-cols">
                            <h2>Вылет</h2>
                            <div class="check-cont ">
                                <ul class="hor clearfix">
                                    <? foreach ($filter[32] as $item): ?>
                                    <li>
                                        <input type="checkbox" id="f<?=$item['variant_id']?>" name="arr[32][]" value="<?=$item['variant_id']?>" <?=$item['checked']?>>
                                        <label class="clearfix" for="f<?=$item['variant_id']?>">
                                            <span class="checked"></span>
                                            <span class="default"></span>   
                                            <h3><?=$item['value']?></h3>
                                        </label>
                                    </li>
                                    <? endforeach; ?>
                                </ul>
                            </div>  
                        </div>
                    <? endif; ?>
                    <div class="filter-cont four-cols">
                        <h2>Количество</h2>
                        <div class="check-cont ">
                            <ul class="hor clearfix">
                                <? foreach ($filter[28] as $item): ?>
                                <li>
                                    <input type="checkbox" id="f<?=$item['variant_id']?>" name="arr[28][]" value="<?=$item['variant_id']?>" <?=$item['checked']?>>
                                    <label class="clearfix" for="f<?=$item['variant_id']?>">
                                        <span class="checked"></span>
                                        <span class="default"></span>   
                                        <h3><?=$item['value']?></h3>
                                    </label>
                                </li>
                                <? endforeach; ?>
                            </ul>
                        </div>  
                    </div>
                <? if ($_GET['type'] == 2): ?>
                    <div class="filter-cont">
                        <h2>Город</h2>
                        <div class="check-cont ">
                            <ul class="hor clearfix">
                                <? foreach ($filter[27] as $item): ?>
                                <li>
                                    <input type="checkbox" id="f<?=$item['variant_id']?>" name="arr[27][]" value="<?=$item['variant_id']?>" <?=$item['checked']?>>
                                    <label class="clearfix" for="f<?=$item['variant_id']?>">
                                        <span class="checked"></span>
                                        <span class="default"></span>   
                                        <h3><?=$item['value']?></h3>
                                    </label>
                                </li>
                                <? endforeach; ?>
                            </ul>
                        </div>  
                    </div>
                <? endif; ?>
            <? endif; ?>   
            </div>
            <div class="b-main-items left clearfix">
                <?php $this->renderPartial('_list', array('goods'=>$goods,'pages' => $pages)); ?>
            </div>
            <?php $this->endWidget(); ?>
        </div>
    </div>
</div>
