<?
$mobile = (preg_match('/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|mobile.+firefox|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows ce|xda|xiino/i',$_SERVER['HTTP_USER_AGENT'])||preg_match('/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i',substr($_SERVER['HTTP_USER_AGENT'],0,4)));
?>
<div class="b-content">
	<div class="good-detail">
		<div class="b-block">
			<ul class="navigation clearfix">
				<li><a href="<?=Yii::app()->createUrl('/kolesoOnline')?>"></a></li>
				<!-- <li><a href="#">Каталог</a></li> -->
				<li><a href="<? echo Yii::app()->createUrl('/kolesoOnline/category',array('type' => $_GET['type']))?>"><?=($this->params[$_GET['type']]["NAME"])?></a></li>
				<li><a href="#"><?=$good_title?></a></li>
			</ul>
			<h1 class="category-title" id="buy-title"><?=$good_title?></h1>
			<div class="detail-wrap clearfix">
				<div class="detail-photo left">
					<div class="detail-slider-for">
						<?$i=0;?>
						<? foreach ($imgs as $img): ?>
							<div><a href="<?=$img["original"]?>" <?if(strpos($img["original"], "default")):?>onclick="return false;"<?endif;?> class="<?if(!strpos($img["original"], "default")):?>fancy-img <?endif;?>big<?=(($i==0)?"":" after-load-back")?>" <?=(($i==0)?"":"data-")?>style="background-image:url('<?=$img["big"]?>');" rel="gallery0"></a></div>
							<?$i++;?>
						<? endforeach; ?>
					</div>
					<ul class="detail-thumb">
						<? if (count($imgs)>1): ?>
							<? foreach ($imgs as $img): ?>
								<li class="thumb" style="background-image:url('<?=$img["small"]?>');"></li>
							<? endforeach; ?>
						<? endif; ?>
					</ul>
				</div>
				<div class="detail-price gradient-grey right">
					<div class="clearfix">
						<? $price = Interpreter::generate($this->params[$_GET['type']]["PRICE_CODE"], $good, $dynamic);?>
						<? $price = number_format($price, 0, ',', ' ' )." р."; $order = Interpreter::generate($this->params[$_GET['type']]["ORDER"], $good,$dynamic); ?>
						<? $price = ($good->archive)?(($this->user)?("Продано за ".$price):"Продано"):$price; ?>
						<h3><?=(($good->good_type_id == 3)?"Уточняйте цену":(( !$good->fields_assoc[20]->value || $good->fields_assoc[20]->value == 0 )? Yii::app()->params["zeroPrice"] : $price))?></h3>
						<? $is_available = "(".Interpreter::generate($this->params[$_GET['type']]["AVAILABLE"], $good, $dynamic).")"; if(!$good->fields_assoc[27]->value) $is_available = "";?>
						<? if(!$good->archive): ?>
							<? if($is_available != "(В наличии)"): ?>
								<?if(!$good->archive):?><?$delivery = Interpreter::generate($this->params[$_GET['type']]["SHIPPING"], $good,$dynamic);?>
								<h4 <?if($delivery=="бесплатная"): $delivery = "+ доставка бесплатно"?>class="b-free-delivery"<?endif;?>> <span> <?=$delivery?></span><?endif;?> <?=$is_available?></h4>
							<? endif; ?>
						<? endif; ?>
					</div>
					<div class="clearfix">
						<div class="left">
							<? if(isset($_SESSION["BASKET"]) && array_search($good->id, $_SESSION["BASKET"]) !== false): ?>
								<? if(!$mobile): ?>
								<a href="<?=Yii::app()->createUrl('/kolesoOnline/basket',array('id' => $good->id,'add' => true))?>" class="b-orange-butt carted">Оформить</a>
								<? else:?>
		                    		<a href="<?=Yii::app()->createUrl('/kolesoOnline/cart',array('id' => $good->id))?>" class="b-orange-butt">Купить</a>
		                    	<? endif;?>
							<? elseif(mb_strpos($price,"Продано",0,"UTF-8") === false && mb_strpos($price,Yii::app()->params["zeroPrice"],0,"UTF-8") === false): ?>
								<? if(!$mobile): ?>
		                        	<a href="<?=Yii::app()->createUrl('/kolesoOnline/basket',array('id' => $good->id,'add' => true))?>" class="b-orange-butt to-cart">в корзину</a>
		                    	<? else:?>
		                    		<a href="<?=Yii::app()->createUrl('/kolesoOnline/cart',array('id' => $good->id))?>" class="b-orange-butt">Купить</a>
		                    	<? endif;?>
		                    <? elseif(mb_strpos($price,Yii::app()->params["zeroPrice"],0,"UTF-8") !== false): ?>
		                    	<a href="#" class="fancy b-orange-butt acc" data-block="#b-popup-buy" data-aftershow="category_buy">Уточнить цену</a>
		                    <? else:?>
		                    	<a href="#" class="b-orange-butt">Продано</a>
		                    <? endif; ?>
						</div>
						<div class="left">
							<h5 class="b-go" data-block="#shipping">Доставка и оплата</h5>
							<? if( $is_available == "В наличии" ): ?>
								<h6><p>Товар: </p><span class="stock">В&nbsp;наличии</span></h6>
							<? else: ?>
								<h6><p>Товар: </p><span class="deliv">Под&nbsp;заказ</span></h6>
							<? endif; ?>
						</div>
					</div>
				</div>
				<div class="detail-desc left">
					<h3>Характеристики</h3>
					<ul>
						<? foreach ($params[$_GET['type']]["CATEGORY"] as $key => $attr): ?>
						<? if( isset($attr["TYPE"]) && $attr["TYPE"] == "INTER" ): ?>
							<li><?=$attr['LABEL']?>: <span><?=((Interpreter::generate($attr['ID'], $good, $dynamic)) ? Interpreter::generate($attr['ID'], $good, $dynamic) : "Не указано"); if(Interpreter::generate($attr['ID'], $good, $dynamic) != "Новая резина" && Interpreter::generate($attr['ID'], $good, $dynamic)) echo $attr['UNIT']?></span></li>
						<? else: ?>
							<? if(isset($good->fields_assoc[$attr['ID']])): ?>
								<li><?=$attr['LABEL']?>:<span><?=$good->fields_assoc[$attr['ID']]->value.$attr['UNIT']?></span></li>
							<? endif; ?>
						<? endif; ?>
						<? endforeach; ?>

						<? if($partner): ?>
							<li>Партнерское объявление:<span><a href="<?=$partner['link']?>" target="_blank"><?=$partner['label']?></a></span></li>
						<? endif; ?>
					</ul>
				</div>
			</div>
			<div class="main-tabs" id="shipping">
				<ul class="desc-tabs clearfix">
					<li class="active gradient-grey"><a href="#tabs-desc">Описание</a><span></span></li>
					<li class="gradient-grey"><a href="#tabs-shippping">Доставка</a><span></span></li>
					<li class="gradient-grey"><a href="#tabs-warranty">Гарантии</a><span></span></li>
				</ul>
				<div id="tabs-desc" class="desc-desc"><?=Interpreter::generate($this->params[$_GET['type']]["DESCRIPTION_CODE"], $good,$dynamic);?></div>
				<div id="tabs-shippping"><?=Interpreter::generate($this->params[$_GET['type']]["ORDER"], $good,$dynamic);?></div>
				<div id="tabs-warranty"><?=Interpreter::generate($this->params[$_GET['type']]["GARANTY_CODE"], $good,$dynamic);?></div>
			</div>
			<? if(count($similar)): ?>
                <h3 class="category-title similar">Похожие товары</h3>
                <ul class="goods clearfix" id="similar-slider">  
                    <?php $this->renderPartial('_list', array('goods' => $similar,'last' => 0,'params' => $params,'type' => $_GET['type'],'dynamic'=>$dynamic)); ?>
                </ul>
        	<? endif; ?>
		</div>
	</div>
</div>