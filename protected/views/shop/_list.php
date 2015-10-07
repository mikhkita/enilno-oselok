<? if(count($goods)): ?>
<?
    $sort_arr = array("20" => "по цене", "9" => "по диаметру");
    if ($_GET['type'] == 1) {
        $sort_arr["7"] = "по ширине";
        $sort_arr["8"] = "по профилю";
    }
    if ($_GET['type'] == 2) {
        $sort_arr["31"] = "по ширине";
        $sort_arr["32"] = "по вылету";
    }
    $sort_type = (isset($_GET['sort']) && isset($_GET['sort']['type']) && $_GET['sort']['type'] != "") ? $_GET['sort']['type'] : "DESC";
?>
<div class="sort-cont clearfix">
    <h2 class="left">Сортировать:</h2>
    <ul class="left b-items-sort clearfix">
        <? foreach ($sort_arr as $key => $value): ?>
            <? if(isset($_GET['sort']['id']) && $_GET['sort']['id']==$key): ?>
                <li class="active <? if($sort_type =='ASC') echo 'up'; ?>">
                <?=$value?>
                <input type="radio" name="sort[id]" value="<?=$key?>" checked>
            <? else: ?>
                <li>
                <?=$value?>
                <input type="radio" name="sort[id]" value="<?=$key?>">
            <? endif; ?>
            </li>
        <? endforeach;?>
    </ul>
    <input type="hidden" name="sort[type]" value="<?=$sort_type?>">
</div>
<div class="pagination">
    <ul>
    	<? foreach ($goods as $good): ?>
			<li class="clearfix good">
               <a href="<?=Yii::app()->createUrl('/shop/detail',array('type'=> $_GET['type'],"id"=>$good->fields_assoc[3]->value))?>">
                    <div class="img" style="background-image: url(<? $images = $this->getImages($good); echo $images[0];?>);"></div>
                <div class="desc">
                    <h3><?=Interpreter::generate($this->params[$_GET['type']]["TITLE_CODE"], $good);?></h3>
                    <h4><?=Interpreter::generate($this->params[$_GET['type']]["TITLE_2_CODE"], $good);?></h4>
                    <? $price = 0; $price = Interpreter::generate($this->params[$_GET['type']]["PRICE_CODE"], $good); $order = Interpreter::generate($this->params[$_GET['type']]["ORDER"], $good); ?>
                    <h5><?=$price==0 ? Yii::app()->params["zeroPrice"] : number_format( $price, 0, ',', ' ' )." руб."?> <span><? if($order) echo "(".$order.")"; ?></span></h5>
                </div>
            </a>
			</li>
		<? endforeach; ?>
    </ul>  
    <?php $this->widget('CLinkPager', array(
        'header' => '',
        'firstPageLabel' => '1', 
        'lastPageLabel' => $pages->getPageCount(), 
        'cssFile' => Yii::app()->request->baseUrl.'/css/shop.css',
        'maxButtonCount' => 5,
        'pages' => $pages,
        'htmlOptions' => array("class"=>"yiiPager hor clearfix")
    )) ?>
</div>  
<? else: ?>
    <h3 class="b-no-goods">Товаров не найдено</h3>
<? endif; ?>
