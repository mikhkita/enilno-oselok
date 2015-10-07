<div class="b-popup">
    <h1>Варианты атрибута</h1>
    
    <div class="form">

        <div class="row b-variant-cont">
            <label for="new-variant" class="required">Добавление варианта (тип: <?=strtolower($model->type->name)?>)</label>
            <input maxlength="255" required="required" data-type="<?=$model->type->code?>" name="new" id="new-variant" type="text">
            <label for="new-variant" class="error hidden">Такой вариант уже присутствует</label>
            <a href="#" class="b-variant-add" id="add-variant">Добавить</a>
        </div>
    <?php $form=$this->beginWidget('CActiveForm', array(
        'id'=>'faculties-form',
        'enableAjaxValidation'=>false,
    )); ?>
        <input type="hidden" name="Edit" value="1">
        <div class="row" id="b-variants">
            <ul class="b-variants sortable">
                <? foreach ($model->variants as $variant): ?>
                    <? if( $variant->value !== false ): ?>
                    <li>
                        <p><?=$variant->value?></p>
                        <span></span>
                        <input type="hidden" data-name="<?=strtolower($variant->value)?>" name="Variants[<?=$variant->id?>]" value="<?=$variant->sort?>">
                    </li>
                    <? endif; ?>
                <? endforeach; ?>
            </ul>
        </div>

        <div class="row buttons">
            <?php echo CHtml::submitButton('Сохранить'); ?>
            <input type="button" onclick="$.fancybox.close(); return false;" value="Отменить">
        </div>

    <?php $this->endWidget(); ?>

    </div><!-- form -->
</div>