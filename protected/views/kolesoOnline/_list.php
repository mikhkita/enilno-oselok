<? foreach ($goods as $key => $good): ?>   
    <li  <? if($key == (count($goods)-1) ) echo "data-last='".$last."'" ?> class="gradient-grey" data-href="<?=Yii::app()->createUrl('/kolesoonline/detail',array('type'=> $type,"id"=>$good->fields_assoc[3]->value))?>">
        <div class="good-img" style="background-image: url(<? $images = $this->getImages($good); echo $images[0];?>);"></div>
        <div class="params-cont">
            <? if($type == 2): ?>
                <h4><?=$good->fields_assoc[6]->value?></h4>
                <? $price = 0; $price = ($good->fields_assoc[51])?$good->fields_assoc[51]->value:0; $order = Interpreter::generate($this->params[$type]["ORDER"], $good); ?>
                <h5><span><?=$price==0 ? Yii::app()->params["zeroPrice"] : number_format( $price, 0, ',', ' ' )." р."?></span> <? if($order) echo "(".$order.")"; ?> + 800 р.</h5>
                <h5>доставка в г. Томск</h5>
                <h6><?=Interpreter::generate($this->params[$type]["TITLE_2_CODE"], $good);?></h6>
                <h3>Состояние: <span><?=$good->fields_assoc[26]->value?></span></h3>
                <h3><?=$good->fields_assoc[$params[$type]["CATEGORY"]["AMOUNT"]['ID']]->value." ".$params[$type]["CATEGORY"]["AMOUNT"]['UNIT']?></h3>
                <h3>Страна: <span><?=$good->fields_assoc[11]->value?></span></h3>
                <!-- <h3>Износ: <span>82%</span></h3> -->
            <? elseif($type == 1): ?>
                <h4><?=$good->fields_assoc[16]->value." ".$good->fields_assoc[17]->value?></h4>
                <? $price = 0; $price = ($good->fields_assoc[51])?$good->fields_assoc[51]->value:0; $order = Interpreter::generate($this->params[$type]["ORDER"], $good); ?>
                <h5><span><?=$price==0 ? Yii::app()->params["zeroPrice"] : number_format( $price, 0, ',', ' ' )." р."?> </span><? if($order) echo "(".$order.")"; ?> + 800 р.</h5>
                <h5>доставка в г. Томск</h5>
                <h6><?=Interpreter::generate($this->params[$type]["TITLE_2_CODE"], $good);?> <?=$good->fields_assoc[$params[$type]["CATEGORY"]["AMOUNT"]['ID']]->value." ".$params[$type]["CATEGORY"]["AMOUNT"]['UNIT']?></h6>
                <h3><?=$params[$type]["CATEGORY"]["WEAR"]["LABEL"]?>: <span><?=$good->fields_assoc[$params[$type]["CATEGORY"]["WEAR"]['ID']]->value?> %</span></h3>
                <h3><?=$params[$type]["CATEGORY"]["YEAR"]["LABEL"]?>: <span><?=$good->fields_assoc[$params[$type]["CATEGORY"]["YEAR"]['ID']]->value?></h3>
            <? endif; ?>
            <a href="#" class="fancy b-orange-butt" data-block="#b-popup-buy" data-aftershow="category_buy">Купить</a>
        </div>
    </li>
<? endforeach; ?>