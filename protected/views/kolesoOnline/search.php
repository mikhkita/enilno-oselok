<? if(count($goods)): ?>
    <? 
    	$discs = array(); 
    	$tires = array(); 
    	$wheels = array(); 
    	foreach ($goods as $i => $good) {
    		if($good['code'] == "disc") {
    			array_push($discs, $good);
    		}
    		if($good['code'] == "tire") {
    			array_push($tires, $good);
    		}
    		if($good['code'] == "wheel") {
    			array_push($wheels, $good);
    		}
    	} 
    ?>
<div class="b-content">
	<div class="b-search">
		<div class="b-block">
			<ul class="navigation clearfix">
				<li><a href="<?=Yii::app()->createUrl('/kolesoOnline')?>"></a></li>
				<li><a href="#">Поиск</a></li>
			</ul>
			<h1 class="category-title">Поиск</h1>
			<form action="<?=Yii::app()->createUrl('kolesoOnline/search')?>" method="GET" class="b-search-form">
                <input type="text" name="search" placeholder="Поиск" value="<?=$search?>" required>
                <button type="submit" class="icon b-orange-butt">Поиск</button>
            </form>
            <ul class="goods clearfix">
	           	<?$this->renderPartial('_list',array(
					'goods'=> $goods,
					'last' => 0,
					'type' => $type,
					'dynamic' => $dynamic
				)); ?>
			</ul>
		</div>
	</div>
</div>
<? else: ?>    
    <h2>Товаров не найдено</h2>
<? endif; ?>