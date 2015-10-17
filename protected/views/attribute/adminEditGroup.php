<div class="b-popup">
    <h1>Варианты атрибута "<?=$model->name?>"</h1>
    
    <div class="form">

    <?php $form=$this->beginWidget('CActiveForm', array(
        'id'=>'faculties-form',
        'enableAjaxValidation'=>false,
    )); ?>
        <input type="hidden" value="Y" name="Group">
        <div class="b-group-vars">
            <?=CHTML::checkBoxList("VariantsGroup", $selected, $variants, array("separator"=>"","template"=>"<div>{input}{label}</div>")); ?>
        </div>

        <div class="row buttons">
            <?php echo CHtml::submitButton('Сохранить'); ?>
            <input type="button" onclick="$.fancybox.close(); return false;" value="Отменить">
        </div>

    <?php $this->endWidget(); ?>

    </div><!-- form -->
</div>