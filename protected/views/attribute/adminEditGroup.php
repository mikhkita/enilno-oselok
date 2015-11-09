<div class="b-popup b-group-popup">
    <h1>Варианты атрибута "<?=$model->name?>"</h1>
    
    <div class="form">

    <?php $form=$this->beginWidget('CActiveForm', array(
        'id'=>'faculties-form',
        'enableAjaxValidation'=>false,
    )); ?>
        <input type="hidden" value="Y" name="Group">
        <div class="b-group-vars">
            <? foreach ($variants as $key => $vars): ?>
                <div class="b-group-col">
                    <?=CHTML::checkBoxList("VariantsGroup", $selected, $vars, array("separator"=>"","baseID"=>"arr".$key,"template"=>"<div>{input}{label}</div>")); ?>
                </div>
            <? endforeach; ?>
        </div>

        <div class="b-checkbox-nav">
            <a href="#" class="select-all">Выделить все</a>
            <a href="#" class="select-none">Сбросить выделение</a>
        </div>

        <div class="row buttons">
            <?php echo CHtml::submitButton('Сохранить'); ?>
            <input type="button" onclick="$.fancybox.close(); return false;" value="Отменить">
        </div>

    <?php $this->endWidget(); ?>

    </div><!-- form -->
</div>