<? 
$mobile = (preg_match('/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|mobile.+firefox|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows ce|xda|xiino/i',$_SERVER['HTTP_USER_AGENT'])||preg_match('/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i',substr($_SERVER['HTTP_USER_AGENT'],0,4)));
if(count($goods)): ?>
    <? foreach ($goods as $key => $good): ?>   
        <li  <? unset($_GET['partial'],$_GET['GoodFilter_page']); echo "data-last='".$last."'" ?> class="gradient-grey b-good-type-<?=$good->good_type_id?>">
            <a href="<?=Yii::app()->createUrl('/kolesoOnline/detail',array('id' => ($good->code)?$good->code:$good->fields_assoc[3]->value,'type' => $type))?>"><div class="good-img<?=((isset($partial) && $partial)?"":" after-load-back")?>" <?=((isset($partial) && $partial)?"":"data-")?>style="background-image: url(<? $images = $good->getImages(1, array("small"), NULL, NULL, true); echo $images[0]["small"];?>);"></div></a>
            <div class="params-cont">
                <a class="params-cont-a" href="<?=Yii::app()->createUrl('/kolesoOnline/detail',array('id' => ($good->code)?$good->code:$good->fields_assoc[3]->value,'type' => $type))?>">
                <? 
                    $price = Interpreter::generate($this->params[$type]["PRICE_CODE"], $good, $dynamic);
                    $amount = $good->fields_assoc[$params[$type]["CATEGORY"]["AMOUNT"]['ID']]->value." ".$params[$type]["CATEGORY"]["AMOUNT"]['UNIT'];
                    $price = ($price==0) ? Yii::app()->params["zeroPrice"] : number_format( $price, 0, ',', ' ' )." р. за ".$amount;
                    
                ?>
                <? if($type == 2): ?>
                    <h4><?=$good->fields_assoc[6]->value?></h4>
                    <h5><span><?=$price?></span> 
                    <!-- <?=Interpreter::generate($this->params[$type]["SHIPPING"], $good,$dynamic);?> -->
                    </h5>
                    <? $available = Interpreter::generate($this->params[$type]["AVAILABLE"], $good,$dynamic); if(!$good->fields_assoc[27]->value) $available = ""; $available = ($available != "В наличии") ? "Доставка ".$available : $available; ?>
                    <h5><?=$available;?></h5>
                    <h6><?=Interpreter::generate($this->params[$type]["TITLE_2_CODE"], $good,$dynamic);?></h6>
                    <h3>Состояние: <span><?=$good->fields_assoc[26]->value?></span></h3>
                    <!-- <h3><?=$amount?></h3> -->
                    <h3>Страна: <span><?=(($good->fields_assoc[11]->value) ? $good->fields_assoc[11]->value : "Не указано")?></span></h3>
                <? elseif($type == 1): ?>
                    <h4><?=$good->fields_assoc[16]->value." ".$good->fields_assoc[17]->value?></h4>
                    <h5><span><?=$price?></span> 
                    <!-- <?=Interpreter::generate($this->params[$type]["SHIPPING"], $good,$dynamic);?> -->
                    </h5>
                    <? $available = Interpreter::generate($this->params[$type]["AVAILABLE"], $good,$dynamic); if(!$good->fields_assoc[27]->value) $available = ""; $available = ($available != "В наличии") ? "Доставка ".$available : $available; ?>
                    <h5><?=$available;?></h5>
                    <h6><?=Interpreter::generate($this->params[$type]["TITLE_2_CODE"], $good,$dynamic);?></h6>
                    <h3 style="display:block;"><?=$params[$type]["CATEGORY"]["WEAR"]["LABEL"]?>: <span><?=$good->fields_assoc[$params[$type]["CATEGORY"]["WEAR"]['ID']]->value?> %</span></h3>
                    <h3><?=$params[$type]["CATEGORY"]["YEAR"]["LABEL"].": "?> 
                        <span><?=(($good->fields_assoc[$params[$type]["CATEGORY"]["YEAR"]['ID']]->value)) ? $good->fields_assoc[$params[$type]["CATEGORY"]["YEAR"]['ID']]->value : "Не указано"?></span>
                    </h3>
                <? elseif($type == 3): ?>
                    <? $price = Yii::app()->params["zeroPrice"];?>
                    <h4><?=Interpreter::generate($this->params[$type]["TITLE_CATEGORY"], $good,$dynamic);?></h4>
                    <h5><span><?=$price?></span> 
                    <!-- <?=Interpreter::generate($this->params[$type]["SHIPPING"], $good,$dynamic);?> -->
                    </h5>
                    <? $available = Interpreter::generate($this->params[$type]["AVAILABLE"], $good,$dynamic); if(!$good->fields_assoc[27]->value) $available = ""; $available = ($available != "В наличии") ? "Доставка ".$available : $available; ?>
                    <h5><?=$available;?></h5>
                    <h6><?=Interpreter::generate($this->params[$type]["TITLE_2_CODE"], $good,$dynamic);?></h6>
                    <h3>Состояние: <span><?=$good->fields_assoc[26]->value?></span></h3>
                    <!-- <h3><?=$amount?></h3> -->
                    <h3>Страна: <span><?=(($good->fields_assoc[11]->value) ? $good->fields_assoc[11]->value : "Не указано")?></span></h3>
                <? endif; ?>
                </a>
                <? if($price != Yii::app()->params["zeroPrice"]): ?>
                    <? if(!$mobile): ?>
                        <? if(isset($_SESSION["BASKET"]) && array_search($good->id, $_SESSION["BASKET"]) !== false): ?>
                            <a href="<?=Yii::app()->createUrl('/kolesoOnline/basket',array('id' => $good->id,'add' => true))?>" class="b-orange-butt carted">Оформить</a>
                        <? else: ?>
                            <a href="<?=Yii::app()->createUrl('/kolesoOnline/basket',array('id' => $good->id,'add' => true))?>" class="b-orange-butt to-cart">В корзину</a>
                        <? endif; ?>
                    <? else: ?>
                        <a href="<?=Yii::app()->createUrl('/kolesoOnline/cart',array('id' => $good->id))?>" class="b-orange-butt">Купить</a>
                    <? endif;?>
                <? else: ?>
                    <a href="#" class="fancy b-orange-butt acc" data-block="#b-popup-buy" data-aftershow="category_buy">Уточнить цену</a>
                <? endif; ?>
                
            </div>
        </li>
    <? endforeach; ?>
<? endif; ?>