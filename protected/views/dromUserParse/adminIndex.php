<? if(Yii::app()->user->hasFlash('message')): ?>
    <script>alert("<?=Yii::app()->user->getFlash('message');?>");</script>
<? endif; ?>
<h1><?=$this->adminMenu["cur"]->name?></h1>
<div class="drom-user-parse">

	<form class="clearfix" action="<?=Yii::app()->createUrl('/dromuserparse/adminindex')?>" method="POST">
		<div>
			<input type="text" name="user" required>
			<input id="tire_inp" type="checkbox" name="good_types[]" value="1" checked>
			<label for="tire_inp">Шины</label>
			<input id="disc_inp" type="checkbox" name="good_types[]" value="2" checked>
			<label for="disc_inp">Диски</label>
			<input id="wheel_inp" type="checkbox" name="good_types[]" value="3" checked>
			<label for="wheel_inp">Колеса</label>
		</div>
		<input class="b-butt" type="submit" value="Начать парсинг">
	</form>
</div>

