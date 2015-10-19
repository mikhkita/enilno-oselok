<h1><?=$this->adminMenu["cur"]->name?></h1>
<div class="b-compare">
	<div class="clearfix">
		<textarea class="left" name="compare1"></textarea>
		<textarea class="right" name="compare2"></textarea>
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

