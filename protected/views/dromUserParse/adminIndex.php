<? if(Yii::app()->user->hasFlash('message')): ?>
    <script>alert("<?=Yii::app()->user->getFlash('message');?>");</script>
<? endif; ?>
<h1><?=$this->adminMenu["cur"]->name?></h1>
<div class="drom-user-parse">

	<form class="clearfix" action="<?=Yii::app()->createUrl('/dromUserParse/adminindex')?>" method="POST">
		<div>
			<input type="text" name="user">
			<input id="tire_inp" type="checkbox" name="good_types[]" value="1">
			<label for="tire_inp">Шины</label>
			<input id="disc_inp" type="checkbox" name="good_types[]" value="2" checked>
			<label for="disc_inp">Диски</label>
			<input id="wheel_inp" type="checkbox" name="good_types[]" value="3">
			<label for="disc_inp">Диски</label>
			<p><br>Строка для парсинга должна быть представлена в следующем виде: ссылка на объявление на дроме + пробел + код товара.<br>Пример: "http://baza.drom.ru/petropavlovsk-kamchatskii/wheel/tire/dsaf-41562222.html 29999"</p>
			<textarea id="links" name="links"></textarea>
		</div>
		<input class="b-butt" type="submit" value="Начать парсинг">
	</form>
</div>

