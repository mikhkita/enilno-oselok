<div class="b-popup">
    <h1>Варианты атрибута "<?=$model->name?>"</h1>
    
    <div class="form">

        <div class="row b-variant-cont">
            <label for="new-variant" class="required">Тип: <?=mb_strtolower($model->type->name, 'UTF-8')?></label>
            <div class="clearfix">
                <span class="b-set-input b-set-list b-title" title="Вставить списком"></span>
                <span class="b-set-input b-set-single b-title" title="Вставлять по одному варианту"></span>
                <input maxlength="255" required="required" data-type="<?=$model->type->code?>" name="new" id="new-variant" type="text">
                <textarea id="new-variant-list" name="new-list"></textarea>
                <a href="#" class="b-variant-add" id="add-variant">Добавить</a>
            </div>
            <label for="new-variant" class="error error-single hidden">Такой вариант уже присутствует</label>
            <label for="new-variant" class="error error-list hidden">Варианты не валидны или уже присутствуют</label>
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
                        <input type="hidden" data-name="<?=mb_strtolower($variant->value, 'UTF-8')?>" name="Variants[<?=$variant->id?>]" value="<?=$variant->sort?>">
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