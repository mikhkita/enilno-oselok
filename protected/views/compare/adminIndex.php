<h1><?=$this->adminMenu["cur"]->name?></h1>
<div class="b-compare" data-url="<?php echo Yii::app()->createUrl('/compare/adminput')?>">
	<div class="clearfix">
		<textarea class="left <?=( ($this->isRoot())?("b-other-column"):("b-main-column") )?>" name="left" id="compare1"><?=file_get_contents(Yii::app()->basePath."/data/left.txt");?></textarea>
		<textarea class="right <?=( ($this->isRoot())?("b-main-column"):("b-other-column") )?>" name="right" id="compare2"><?=file_get_contents(Yii::app()->basePath."/data/right.txt");?></textarea>
	</div>
	<button type="button" class="b-butt">Сравнить</button>
	<div class="compare-cont">
		<h2>То что есть в левом, но нет в правом</h2>
		<div class="compare1"></div>
		<h2>То что есть в правом, но нет в левом</h2>
		<div class="compare2"></div>
		<h2>Варианты, у которых нет пары</h2>
		<div class="same"></div>
	</div>
</div>

