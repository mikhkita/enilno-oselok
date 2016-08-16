<div class="b-popup b-popup-title-edit">
	<h1>Редактирование заголовка</h1>
	<div class="clearfix">
		<div class="left">
			<div class="form">
			<?php $form=$this->beginWidget('CActiveForm', array(
				'id'=>'faculties-form',
				'enableAjaxValidation'=>false,
			)); ?>
				<input type="hidden" name="data" value="1">
				<div class="row">
					<label for="advert-title">Заголовок</label>
					<span class="to-error"></span>
					<input type="text" id="advert-title" class="title" name="title" placeholder="Заголовок" value="<?=$advert->title?>">
					<div class="clearfix" style="margin-top: 10px;">
						<p class="b-char-count left">Количество символов: <span><?=mb_strlen($advert->title, "UTF-8")?></span></p>
						<a href="<?=$url?>" class="right b-good-url" target="_blank">Товар в магазине</a>
					</div>
				</div>

				<div class="row">
					<br>
					<label for="advert-title">Похожие:</label>
					<div class="b-text-cont">
						<?foreach ($similar as $i => $title):?>
						<?=$title?><br>
						<?endforeach;?>
					</div>
				</div>

				<div class="row buttons">
					<input type="button" onclick="$.fancybox.close(); return false;" value="Закрыть">
				</div>
			<?php $this->endWidget(); ?>
			</div>
		</div>
		<div class="left b-iframe-cont">
			<iframe src="<?=$url?>#task" frameborder="0"></iframe>
		</div>
	</div>
</div>