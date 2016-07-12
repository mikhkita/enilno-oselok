<div class="b-content">
	<div class="b-search">
		<div class="b-block">
			<ul class="navigation clearfix">
				<li><a href="<?=Yii::app()->createUrl('/kolesoOnline')?>"></a></li>
				<li><a href="#">Поиск</a></li>
			</ul>
			<h1 class="category-title">Поиск</h1>
			<form action="<?=Yii::app()->createUrl('/kolesoOnline/search')?>" method="GET">
                <input type="text" name="search" placeholder="Поиск" value="<?=$search?>" required>
                <button type="submit" class="icon b-orange-butt">Поиск</button>
            </form>
            <div class="search-tabs clearfix">
                <a class="<?if($type==2) echo 'active'; ?>" href="<?=Yii::app()->createUrl('/kolesoOnline/search',array('search' => $search,'type' => 2))?>">Диски</a>
                <a class="<?if($type==1) echo 'active'; ?>" href="<?=Yii::app()->createUrl('/kolesoOnline/search',array('search' => $search,'type' => 1))?>">Шины</a>
                <a class="<?if($type==3) echo 'active'; ?>" href="<?=Yii::app()->createUrl('/kolesoOnline/search',array('search' => $search,'type' => 3))?>">Колеса</a>
            </div>
            <? if(count($goods)): ?>
                <ul class="goods clearfix">
    	           	<?$this->renderPartial('vladivostok/_list',array(
    					'goods'=> $goods,
    					'last' => 0,
    					'type' => $type,
    					'dynamic' => $dynamic
    				)); ?>
    			</ul>
            <? else: ?>    
                <h2 class="no-search">Товаров не найдено</h2>
            <? endif; ?>
		</div>
	</div>
</div>
