<div class="b-popup">
	<h1><?=$action_name?> (<?=$advert_count?> объявлений)</h1>
	<div class="form">
	<?php $form=$this->beginWidget('CActiveForm', array(
		'id'=>'faculties-form',
		'enableAjaxValidation'=>false,
	)); ?>
		<input type="hidden" name="data" value="1">
		<div class="row">
			<label for="offset">Задержка для авито (в часах)</label>
			<input type="number" id="offset" class="offset" name="offset" placeholder="В часах" value="0">
		</div>

		<div class="row">
			<label for="interval">Разброс для авито (в часах)</label>
			<input type="number" id="interval" class="interval" name="random_offset" placeholder="В часах" value="24">
		</div>

		<div class="row buttons">
			<?php echo CHtml::submitButton("Выполнить"); ?>
			<input type="button" onclick="$.fancybox.close(); return false;" value="Отменить">
		</div>
	<?php $this->endWidget(); ?>
	</div>
</div>