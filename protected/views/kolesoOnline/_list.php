<? foreach ($goods as $key => $good): ?>   
    <li  <? unset($_GET['partial'],$_GET['GoodFilter_page']); if($key == (count($goods)-1) ) echo "data-last='".$last."'" ?> class="gradient-grey b-good-type-<?=$good->good_type_id?>">
        <div class="good-img" style="background-image: url(<? $images = $this->getImages($good); echo $images[0];?>);"></div>
        <div class="params-cont">
            <a href="<?=Yii::app()->createUrl('/kolesoOnline/detail',array('id' => $good->fields_assoc[3]->value,'type' => $type))?>">
            <? if($type == 2): ?>
                <h4><?=$good->fields_assoc[6]->value?></h4>
                <? $price = ($good->fields_assoc[20])?$good->fields_assoc[20]->value:0; $order = Interpreter::generate($this->params[$type]["ORDER"], $good); ?>
                <h5><span><?=$price==0 ? Yii::app()->params["zeroPrice"] : number_format( $price, 0, ',', ' ' )." р."?></span> <?=Interpreter::generate($this->params[$type]["SHIPPING"], $good,$dynamic);?></h5>
                <h5><?=Interpreter::generate($this->params[$type]["AVAILABLE"], $good,$dynamic);?></h5>
                <h6><?=Interpreter::generate($this->params[$type]["TITLE_2_CODE"], $good,$dynamic);?></h6>
                <h3>Состояние: <span><?=$good->fields_assoc[26]->value?></span></h3>
                <h3><?=$good->fields_assoc[$params[$type]["CATEGORY"]["AMOUNT"]['ID']]->value." ".$params[$type]["CATEGORY"]["AMOUNT"]['UNIT']?></h3>
                <h3>Страна: <span><?=(($good->fields_assoc[11])?$good->fields_assoc[11]->value:"Не указано")?></span></h3>
                <!-- <h3>Износ: <span>82%</span></h3> -->
            <? elseif($type == 1): ?>
                <h4><?=$good->fields_assoc[16]->value." ".$good->fields_assoc[17]->value?></h4>
                <? $price = ($good->fields_assoc[20])?$good->fields_assoc[20]->value:0; $order = Interpreter::generate($this->params[$type]["ORDER"], $good); ?>
                <h5><span><?=$price==0 ? Yii::app()->params["zeroPrice"] : number_format( $price, 0, ',', ' ' )." р."?> </span> <?=Interpreter::generate($this->params[$type]["SHIPPING"], $good,$dynamic);?></h5>
                <h5><?=Interpreter::generate($this->params[$type]["AVAILABLE"], $good,$dynamic);?></h5>
                <h6><?=Interpreter::generate($this->params[$type]["TITLE_2_CODE"], $good,$dynamic);?> <?=$good->fields_assoc[$params[$type]["CATEGORY"]["AMOUNT"]['ID']]->value." ".$params[$type]["CATEGORY"]["AMOUNT"]['UNIT']?></h6>
                <h3><?=$params[$type]["CATEGORY"]["WEAR"]["LABEL"]?>: <span><?=$good->fields_assoc[$params[$type]["CATEGORY"]["WEAR"]['ID']]->value?> %</span></h3>
                <h3><?=$params[$type]["CATEGORY"]["YEAR"]["LABEL"]?>: <span><?=$good->fields_assoc[$params[$type]["CATEGORY"]["YEAR"]['ID']]->value?></h3>
            <? endif; ?>
            </a>
            <a href="#" class="fancy b-orange-butt" data-block="#b-popup-buy" data-aftershow="category_buy">Купить</a>
        </div>
    </li>
<? endforeach; ?>