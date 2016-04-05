<? if(count($goods)): ?>
    <? foreach ($goods as $key => $good): ?>   
        <li  <? unset($_GET['partial'],$_GET['GoodFilter_page']); echo "data-last='".$last."'" ?> class="gradient-grey b-good-type-<?=$good->good_type_id?>">
            <a href="#"><div class="good-img<?=((isset($partial) && $partial)?"":" after-load-back")?>" <?=((isset($partial) && $partial)?"":"data-")?>style="background-image: url(<? $images = $good->getImages(1, array("small"), NULL, NULL, true); echo $images[0]["small"];?>);"></div></a>
            <div class="params-cont">
                <a class="params-cont-a" href="<?=Yii::app()->createUrl('/kolesoOnline/detail',array('id' => ($good->code)?$good->code:$good->fields_assoc[3]->value,'type' => $type))?>">
                <? $price = Interpreter::generate($this->params[$type]["PRICE_CODE"], $good, $dynamic);?>
                <? if($type == 2): ?>
                    <h4><?=$good->fields_assoc[6]->value?></h4>
                    <h5><span><?=$price==0 ? Yii::app()->params["zeroPrice"] : number_format( $price, 0, ',', ' ' )." р."?></span> <?=Interpreter::generate($this->params[$type]["SHIPPING"], $good,$dynamic);?></h5>
                    <h5><?=Interpreter::generate($this->params[$type]["AVAILABLE"], $good,$dynamic);?></h5>
                    <h6><?=Interpreter::generate($this->params[$type]["TITLE_2_CODE"], $good,$dynamic);?></h6>
                    <h3>Состояние: <span><?=$good->fields_assoc[26]->value?></span></h3>
                    <h3><?=$good->fields_assoc[$params[$type]["CATEGORY"]["AMOUNT"]['ID']]->value." ".$params[$type]["CATEGORY"]["AMOUNT"]['UNIT']?></h3>
                    <h3>Страна: <span><?=(($good->fields_assoc[11])?$good->fields_assoc[11]->value:"Не указано")?></span></h3>
                <? elseif($type == 1): ?>
                    <h4><?=$good->fields_assoc[16]->value." ".$good->fields_assoc[17]->value?></h4>
                    <h5><span><?=$price==0 ? Yii::app()->params["zeroPrice"] : number_format( $price, 0, ',', ' ' )." р."?> </span> <?=Interpreter::generate($this->params[$type]["SHIPPING"], $good,$dynamic);?></h5>
                    <h5><?=Interpreter::generate($this->params[$type]["AVAILABLE"], $good,$dynamic);?></h5>
                    <h6><?=Interpreter::generate($this->params[$type]["TITLE_2_CODE"], $good,$dynamic);?> <?=$good->fields_assoc[$params[$type]["CATEGORY"]["AMOUNT"]['ID']]->value." ".$params[$type]["CATEGORY"]["AMOUNT"]['UNIT']?></h6>
                    <h3 style="display:block;"><?=$params[$type]["CATEGORY"]["WEAR"]["LABEL"]?>: <span><?=$good->fields_assoc[$params[$type]["CATEGORY"]["WEAR"]['ID']]->value?> %</span></h3>
                    <h3> 
                        <?if($good->fields_assoc[$params[$type]["CATEGORY"]["YEAR"]['ID']]->value): ?> 
                            <?=$params[$type]["CATEGORY"]["YEAR"]["LABEL"].": "?> 
                            <span><?=$good->fields_assoc[$params[$type]["CATEGORY"]["YEAR"]['ID']]->value?></span>
                        <? else: ?>
                            <span>&nbsp;</span>
                        <? endif; ?>
                    </h3>
                <? elseif($type == 3): ?>
                    <h4><?=Interpreter::generate($this->params[$type]["TITLE_CATEGORY"], $good,$dynamic);?></h4>
                    <h5><span><?=$price==0 ? Yii::app()->params["zeroPrice"] : number_format( $price, 0, ',', ' ' )." р."?></span> <?=Interpreter::generate($this->params[$type]["SHIPPING"], $good,$dynamic);?></h5>
                    <h5><?=Interpreter::generate($this->params[$type]["AVAILABLE"], $good,$dynamic);?></h5>
                    <h6><?=Interpreter::generate($this->params[$type]["TITLE_2_CODE"], $good,$dynamic);?></h6>
                    <h3>Состояние: <span><?=$good->fields_assoc[26]->value?></span></h3>
                    <h3><?=$good->fields_assoc[$params[$type]["CATEGORY"]["AMOUNT"]['ID']]->value." ".$params[$type]["CATEGORY"]["AMOUNT"]['UNIT']?></h3>
                    <h3>Страна: <span><?=(($good->fields_assoc[11])?$good->fields_assoc[11]->value:"Не указано")?></span></h3>
                <? endif; ?>
                </a>
                <? if($price): ?>
                    <? if(isset($_SESSION["BASKET"]) && array_search($good->id, $_SESSION["BASKET"]) !== false): ?>
                        <a href="#" class="b-orange-butt carted" onclick="return false;">добавлено</a>
                    <? else: ?>
                        <a href="<?=Yii::app()->createUrl('/kolesoOnline/basket',array('id' => $good->id,'type' => $type,'add' => true))?>" class="b-orange-butt to-cart" onclick="return false;">в корзину</a>
                    <? endif; ?>
                <? else: ?>
                    <a href="#" class="fancy b-orange-butt acc" data-block="#b-popup-buy" data-aftershow="category_buy">Уточнить цену</a>
                <? endif; ?>
                
            </div>
        </li>
    <? endforeach; ?>
<? endif; ?>