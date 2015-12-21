<div class="b-popup">
	<h1>Поднятие. Дром платные (<?=$advert_count?> объявлений)</h1>
	<div class="form">
	<?php $form=$this->beginWidget('CActiveForm', array(
		'id'=>'faculties-form',
		'enableAjaxValidation'=>false,
	)); ?>
		<input type="hidden" name="data" value="1">
		<div class="row">
			<label for="offset">Задержка</label>
			<input type="number" id="offset" class="offset" name="offset" placeholder="В минутах">
		</div>

		<div class="row">
			<label for="interval">Интервал</label>
			<input type="number" id="interval" class="interval" name="interval" placeholder="В минутах">
		</div>

		<div class="row buttons">
			<?php echo CHtml::submitButton("Поднять"); ?>
			<input type="button" onclick="$.fancybox.close(); return false;" value="Отменить">
		</div>
	<?php $this->endWidget(); ?>
	</div>
</div>