<div class="row">
    <? $good = $order_good->good; $goodType = GoodType::model()->with("fields")->findByPk($good->good_type_id); $goodType = $goodType->name;?>
    <a target="_blank" href="<?=Yii::app()->createUrl('/kolesoOnline/detail',array('id' => ($good->code)?$good->code:$good->fields_assoc[3]->value,'type' => $good->good_type_id))?>"><?=$goodType." ".$good->fields_assoc[3]->value?></a>    
</div>    
<div class="row">
    <?php echo CHtml::activeLabelEx($order_good,'price'); ?>
    <?php echo CHtml::activeNumberField($order_good,'price',array('maxlength'=>6,'required'=>true,"min"=>0)); ?>
    <?php echo CHtml::error($order_good,'price'); ?>
</div>  
<div class="row">
    <?php echo CHtml::activeLabelEx($order_good,'waybill'); ?>
    <?php echo CHtml::activeTextField($order_good,'waybill',array('maxlength'=>25)); ?>
    <?php echo CHtml::error($order_good,'waybill'); ?>
</div>
<div class="row">
    <?php echo CHtml::activeLabelEx($order_good,'tk_id'); ?>
    <?php echo CHtml::activeDropDownList($order_good,'tk_id',CHtml::listData(Desktop::getList(80), 'row_id', 'value'),array("empty" => "Не задано")); ?>
    <?php echo CHtml::error($order_good,'tk_id'); ?>
</div>