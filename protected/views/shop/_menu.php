<div class="b b-menu">
    <div class="b-block clearfix">
        <ul class="hor left clearfix">
            <li <? if( isset($_GET['type']) && $_GET['type']==1 ) echo 'class="active"'; ?> ><a href="<?=Yii::app()->createUrl('/shop/index',array("type"=>"1"))?>">Шины</a></li>
            <li <? if( isset($_GET['type']) && $_GET['type']==2 ) echo 'class="active"'; ?> ><a href="<?=Yii::app()->createUrl('/shop/index',array("type"=>"2"))?>">Диски</a></li>    
            <li <? if( isset($page) && $page=='contacts' ) echo 'class="active"'; ?>><a href="<?=Yii::app()->createUrl('/shop/contacts')?>">Контакты</a></li>
        </ul>
       <!--  <form class="right" action="#" method="POST" novalidate="novalidate">
            <input type="text" name="search" placeholder="Поиск">
            <img src="<?php echo Yii::app()->request->baseUrl; ?>/i/search-shop.png" alt="">
        </form> -->
    </div>
</div>