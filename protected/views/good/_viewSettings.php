<div class="b-popup b-group-popup">
    <h1>Атрибуты "<?=$good_type->name?>"</h1>
    
    <div class="form">

    <?php $form=$this->beginWidget('CActiveForm', array(
        'id'=>'faculties-form',
        'enableAjaxValidation'=>false,
    )); ?>
        <div class="b-group-vars">
            <? foreach ($attributes as $key => $attrs): ?>
                <div class="b-group-col">
                    <?=CHTML::checkBoxList("view_fields", $selected, $attrs, array("separator"=>"","baseID"=>"arr".$key,"template"=>"<div>{input}{label}</div>")); ?>
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