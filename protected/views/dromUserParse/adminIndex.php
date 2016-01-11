<? if(Yii::app()->user->hasFlash('message')): ?>
    <script>alert("<?=Yii::app()->user->getFlash('message');?>");</script>
<? endif; ?>
<h1><?=$this->adminMenu["cur"]->name?></h1>
<div class="drom-user-parse">

	<form class="clearfix" action="<?=Yii::app()->createUrl('/dromuserparse/adminindex')?>" method="POST">
		<div>
			<input type="text" name="user" required>
			<input	type="checkbox" name="good_types[]" value="1" checked>
			<label>Шины</label>
			<input	type="checkbox" name="good_types[]" value="2" checked>
			<label>Диски</label>
			<input	type="checkbox" name="good_types[]" value="3" checked>
			<label>Колеса</label>
		</div>
		<input class="b-butt" type="submit" value="Начать парсинг">
	</form>
</div>

