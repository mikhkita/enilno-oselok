<div class="b-popup">
    <h1>Продажа (<?=$good->type->name?> <?=$good->fields_assoc[3]->value?>)</h1>
    <div class="form">
        <?php $form=$this->beginWidget('CActiveForm', array(
        	'id'=>'faculties-form',
        	'enableAjaxValidation'=>false,
        	'htmlOptions' => array(
                "data-beforeAjax" => "attributesAjax",
                "data-type" => "json"
            ),
        )); ?>

        	<?php echo $form->errorSummary($model); ?>
            <div class="clearfix">
            	<div class="row row-half">
            		<?php echo $form->labelEx($model,'summ'); ?>
            		<?php echo $form->numberField($model,'summ',array('maxlength'=>6,'required'=>true,"min"=>0)); ?>
            		<?php echo $form->error($model,'summ'); ?>
            	</div>
                <div class="row row-half">
                    <?php echo $form->labelEx($model,'extra'); ?>
                    <?php echo $form->numberField($model,'extra',array('maxlength'=>6,"min"=>0)); ?>
                    <?php echo $form->error($model,'extra'); ?>
                </div>
            </div>
            <div class="row">
                <?php echo $form->labelEx($model,'date'); ?>
                <?php echo $form->textField($model,'date',array("id" => 'datepicker','required' => true)); ?>
                <?php echo $form->error($model,'date'); ?>
            </div>
            <div class="clearfix">
                <div class="row row-half">
                    <?php echo $form->labelEx($model,'channel_id'); ?>
                    <?php echo $form->dropDownList($model,'channel_id',CHtml::listData(Desktop::getList(86), 'row_id', 'value'),array("empty" => "Не задано")); ?>
                    <?php echo $form->error($model,'channel_id'); ?>
                </div>
                <div class="row row-half">
                    <?php echo $form->labelEx($model,'tk_id'); ?>
                    <?php echo $form->dropDownList($model,'tk_id',CHtml::listData(Desktop::getList(80), 'row_id', 'value'),array("empty" => "Не задано")); ?>
                    <?php echo $form->error($model,'tk_id'); ?>
                </div>
            </div>
            <div class="row">
                <?php echo $form->labelEx($model,'order_number'); ?>
                <?php echo $form->textField($model,'order_number',array('class' => 'autocomplete-input')); ?>
                <?php echo $form->error($model,'order_number'); ?>
            </div>
            <div class="row">
                <?php echo $form->labelEx($model,'city'); ?>
                <?php echo $form->textField($model,'city',array('class' => 'autocomplete-input')); ?>
                <?php echo $form->error($model,'city'); ?>
            </div>
            <div class="row">
                <?php echo $form->labelEx($model,'comment'); ?>
                <?php echo $form->textArea($model,'comment',array('class' => 'small-textarea')); ?>
                <?php echo $form->error($model,'comment'); ?>
            </div>
            <div class="row">
                <label for="Customer_phone">Телефон клиента</label>

                <? $customer=""; if(isset($model->customer) && $model->customer->phone) $customer = $model->customer->phone; echo Chtml::textField("Customer[phone]",$customer,array('maxlength'=>25,'class' => 'phone','id' => 'Customer_phone')); ?>
            </div>
            <div id="Customer-form" data-url="<?=$this->createUrl('/good/admincustomer')?>"></div>
        	<div class="row buttons">
        		<?php echo CHtml::submitButton($model->isNewRecord ? 'Добавить' : 'Сохранить'); ?>
        		<input type="button" onclick="$.fancybox.close(); return false;" value="Отменить">
        	</div>

        <?php $this->endWidget(); ?>
        <div style="display:none;" id="cities">
            <? foreach ($cities as $city):?>
                <p><?=$city?></p>
            <? endforeach; ?>
        </div>
    </div>
</div>