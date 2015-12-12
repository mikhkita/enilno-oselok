<div class="b-popup">
    <h1>Выделение по кодам</h1>
    <div class="form">

    <?php $form=$this->beginWidget('CActiveForm', array(
    	'action'=> $this->createUrl('/good/adminaddsomecheckbox',array('good_type_id'=> $_GET["good_type_id"])),
    	'enableAjaxValidation'=>false
    )); ?>
    	<textarea name="Good[ids]" required style="margin-top: 20px;"></textarea>
    	<div class="row buttons">
    		<input type="submit" value="Выбрать">
    		<input type="button" onclick="$.fancybox.close(); return false;" value="Отменить">
    	</div>

    <?php $this->endWidget(); ?>

    </div><!-- form -->
</div>