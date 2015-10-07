<div class="b-popup b-interpreter-preview">
	<h1>Примеры</h1>
<?php $form=$this->beginWidget('CActiveForm'); ?>
	<? foreach ($dynamic as $key => $dyn): ?>
		<div class="row">
		<?php echo CHtml::dropDownList('dynamic['.$key.']', $dyn["CURRENT"], CHtml::listData($dyn["ALL"], 'id', 'value'), array('class'=>'b-select-dynamic')); ?>
		</div>
	<? endforeach; ?>
<?php $this->endWidget(); ?>
	<? foreach ($data as $item): ?>
	<div class="row">
		<p>
			Код товара: <?=$item["ID"]?>
		</p>
		<div>
			<?=$item["VALUE"]?>
		</div>
	</div>
	<?endforeach;?>
	<div class="row buttons">
		<input type="button" onclick="$.fancybox.close(); return false;" value="Закрыть">
	</div>
</div>