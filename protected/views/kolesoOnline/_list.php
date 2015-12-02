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
                    <h4><?=Interpreter::generate($this->params[$_GET['type']]["TITLE_CODE"], $good);?></h4>
                    <? $price = 0; $price = ($good->fields_assoc[51])?$good->fields_assoc[51]->value:0; $order = Interpreter::generate($this->params[$_GET['type']]["ORDER"], $good); ?>
                    <h5><span><?=$price==0 ? Yii::app()->params["zeroPrice"] : number_format( $price, 0, ',', ' ' )." р."?> <span><? if($order) echo "(".$order.")"; ?></span> + 800 р.</h5>
                    <h5>доставка в г. Томск</h5>
                    <h6><?=Interpreter::generate($this->params[$_GET['type']]["TITLE_2_CODE"], $good);?></h6>
                    <h3>Износ: <span>82%</span></h3>
                    <h3><?=$params[$_GET['type']]["CATEGORY"]["YEAR"]["LABEL"]?>:<span><?=$good->fields_assoc[$params[$_GET['type']]["CATEGORY"]["YEAR"]['ID']]->value." ".$params[$_GET['type']]["CATEGORY"]["YEAR"]['UNIT']?></h3>
                <? endif; ?>

                <a href="#" class="fancy b-orange-butt" data-block="#b-popup-buy" data-aftershow="category_buy">Купить</a>
            </div>
        </li>
    <? endforeach; ?>
</ul>
<input type="hidden" class="last-page" value="<?=$last?>"> 