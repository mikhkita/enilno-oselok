<div class="b-popup">
    <h1>Заказ</h1>
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
            <div class="row">
                <?php echo $form->labelEx($model,'city'); ?>
                <?php echo $form->textField($model,'city',array('class' => 'autocomplete-input')); ?>
                <?php echo $form->error($model,'city'); ?>
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
                    <?php echo $form->labelEx($model,'state_id'); ?>
                    <?php echo $form->dropDownList($model,'state_id',CHtml::listData(Desktop::getList(83), 'row_id', 'value'),array("empty" => "Не задано",'required' => true)); ?>
                    <?php echo $form->error($model,'state_id'); ?>
                </div>
            </div>
            <? foreach ($order_goods as $key => $order_good) {
                $this->renderPartial('_orderGood', array('order_good' => $order_good)); 
            }?>  
            <div class="row">
                <label for="Contact_phone">Телефон клиента</label>
                <? if(isset($model->contact)) {
                    $phone = str_split($model->contact->phones[0]->phone); $phone = "+".$phone[0]." (".$phone[1].$phone[2].$phone[3].") ".$phone[4].$phone[5].$phone[6]."-".$phone[7].$phone[8]."-".$phone[9].$phone[10];
                    echo Chtml::textField("Contact[phone]",$phone,array('maxlength'=>25,'class' => 'phone','id' => 'Contact_phone','disabled'=> 'disabled')); 
                    } else {
                        echo Chtml::textField("Contact[phone]","",array('maxlength'=>25,'class' => 'phone','id' => 'Contact_phone')); 
                    }?>
            </div>
            <div id="Contact-form" data-url="<?=$this->createUrl('/good/admincontact')?>"></div>
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