<? $good = $order_good->good; $goodType = GoodType::model()->with("fields")->findByPk($good->good_type_id); $goodType = $goodType->name;?>
<div class="order-good">
    <div class="clearfix">
        <div class="row row-half">
            <?php echo CHtml::activeLabelEx($order_good,'tk_id'); ?>
            <?php echo CHtml::activeDropDownList($order_good,'tk_id',CHtml::listData(Desktop::getList(80), 'row_id', 'value'),array("empty" => "Не задано", 'name' => 'OrderGood['.$good->id.'][tk_id]','id' => 'OrderGood_'.$good->id.'_tk_id')); ?>
            <?php echo CHtml::error($order_good,'tk_id'); ?>
        </div>
        <div class="row row-half">    
            <a target="_blank" class="good-link" href="<?=Yii::app()->createUrl('/kolesoOnline/detail',array('id' => ($good->code)?$good->code:$good->fields_assoc[3]->value,'type' => $good->good_type_id))?>"><?=$goodType." ".$good->fields_assoc[3]->value?></a>   
            <a href="#" class="delete-good">убрать товар</a> 
        </div>   
    </div>   
    <div class="row">
        <label for="OrderGood_<?=$good->id?>_price">Цена</label>    
        <input value="<?=$good->fields_assoc[20]->value?>" maxlength="6" name="OrderGood[<?=$good->id?>][price]" id="OrderGood_<?=$good->id?>_price" type="number" maxlength='6' min='0'>    
    </div>  
    <div class="row">
        <?php echo CHtml::activeLabelEx($order_good,'waybill'); ?>
        <?php echo CHtml::activeTextField($order_good,'waybill',array('maxlength'=>25,'name' => 'OrderGood['.$good->id.'][waybill]', 'id' => 'OrderGood_'.$good->id.'_waybill')); ?>
        <?php echo CHtml::error($order_good,'waybill'); ?>
    </div>
</div>