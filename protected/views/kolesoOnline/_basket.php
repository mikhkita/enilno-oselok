<? 
if(!$partial) {
    foreach ($_SESSION["BASKET"] as $key => $value) {
        if(Good::model()->findByPk($value,"archive <> 0")) unset($_SESSION["BASKET"][$key]);
    }
    $goods = Good::model()->with("type","fields.variant","fields.attribute")->findAllByPk($_SESSION["BASKET"]);
    $array = array();
    foreach ($_SESSION["BASKET"] as $key => $value) {
        foreach ($goods as $good) {
            if($good->id == $value)
                $array[$value] = $good;
        }
       
    }
    
    $goods = $array;
} 
$dynamic = $this->getDynObjects(array(
    38 => Yii::app()->params["city"]->id
));
foreach ($goods as $good) : ?>
    <? $type = $good->good_type_id; $href = Yii::app()->createUrl('/kolesoOnline/detail',array('id' => ($good->code)?$good->code:$good->fields_assoc[3]->value,'type' => $type));?>
    <li class="clearfix">
        <a class="img left" target="_blank" style="background-image: url(<? $images = $good->getImages(1, array("small"), NULL, NULL, true); echo $images[0]["small"];?>);" href="<?=$href?>"></a>
        <div class="b-desc left">
            <? 
                if($type == 1) $title = $good->fields_assoc[16]->value." ".$good->fields_assoc[17]->value;
                if($type == 2) $title = $good->fields_assoc[6]->value;
                if($type == 3) $title = Interpreter::generate($this->params[$type]["TITLE_CATEGORY"], $good,$dynamic);
            ?>
            <a href="<?=$href?>" target="_blank"><?=$title?></a>
            <h3><?=Interpreter::generate($this->params[$type]["TITLE_2_CODE"], $good,$dynamic);?> <?if($type == 1) echo $good->fields_assoc[$this->params[$type]["CATEGORY"]["AMOUNT"]['ID']]->value." ".$this->params[$type]["CATEGORY"]["AMOUNT"]['UNIT']?></h3>
            <h4><?=number_format( Interpreter::generate($this->params[$type]["PRICE_CODE"], $good, $dynamic), 0, ',', ' ' )." р."?> <?=Interpreter::generate($this->params[$type]["SHIPPING"], $good,$dynamic)?></h4>
        </div>
        <a href="<?=Yii::app()->createUrl('/kolesoOnline/basket',array('id' => $good->id,'type' => $type))?>" data-id="<?=$good->id?>" class="cart-close-btn"></a>
    </li>
<? endforeach; ?>
