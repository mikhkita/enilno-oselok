<div class="b-popup">
    <h1>Удаление по списку</h1>
    <div class="form">

    <?php $form=$this->beginWidget('CActiveForm', array(
    	'id'=>'faculties-form',
    	'enableAjaxValidation'=>false,
    	'htmlOptions' => array("data-beforeAjax"=>"attributesAjax"),
    )); ?>


    	<textarea name="GoodType[CodeDel]" required style="margin-top: 20px;"></textarea>


    	<div class="row buttons">
    		<?php echo CHtml::submitButton('Удалить'); ?>
    		<input type="button" onclick="$.fancybox.close(); return false;" value="Отменить">
    	</div>

    <?php $this->endWidget(); ?>

    </div><!-- form -->
</div>