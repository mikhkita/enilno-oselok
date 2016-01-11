<h1><?=$this->adminMenu["cur"]->name?> "<?=$category->value?>"</h1>
<? if( $category->id == 2048 ): ?>
	<a class="b-link-back" href="<?php echo $this->createUrl('/queue/adminindex',array("category_id"=>2047))?>">Дром</a>
<? else: ?>
	<a class="b-link-back" href="<?php echo $this->createUrl('/queue/adminindex',array("category_id"=>2048))?>">Авито</a>
<? endif; ?>

<?php $htmlopt = array("separator"=>"","template"=>"<div class='b-advert-checkbox'>{input}{label}</div>");
$form=$this->beginWidget('CActiveForm',array('id'=>'filter-form',"action" => $this->createUrl('/'.$this->adminMenu["cur"]->code.'/adminindex',array("partial"=>'true',"category_id"=>$category->id)),"method" => "GET",'htmlOptions' => array("data-delay"=> 3))); ?>
<div class="b-advert-filter clearfix">
	<div class="b-advert-textarea">
		<textarea name="Codes" placeholder="Список кодов"><?=$_GET['Codes']?></textarea>
	</div>
	<div class="left">
		<div class="b-advert-block">
			<h3>Площадка</h3>
			<div class="clearfix">
			<?=CHtml::checkBoxList("Place[]", $_GET['Place'], $data_filter['Place'],$htmlopt); ?>
			</div>
		</div>
		<? if( $category->id == 2047 ): ?>
			<div class="b-advert-block">
				<h3><?=$data_filter['AttrName'][37]?></h3>
				<div class="clearfix">
				<?=CHtml::checkBoxList("Attr[37]", $_GET['Attr'][37], $data_filter['Attr'][37],$htmlopt);?>
				</div>
			</div>
		<? endif; ?>
		<div class="b-advert-block">
			<h3><?=$data_filter['AttrName'][58]?></h3>
			<div class="clearfix">
			<?=CHtml::checkBoxList("Attr[58]", $_GET['Attr'][58], $data_filter['Attr'][58],$htmlopt);?>
			</div>
		</div>
		<div class="b-advert-block">
			<h3>Состояние</h3>
			<div class="clearfix">
			<?=CHtml::checkBoxList("Attr[state]", $_GET['Attr']['state'], $data_filter['Attr']['state'],$htmlopt);?>
			</div>
		</div>
		<div class="b-advert-block">
			<h3>Действие</h3>
			<div class="clearfix">
			<?=CHtml::checkBoxList("Attr[action]", $_GET['Attr']['action'], $data_filter['Attr']['action'],$htmlopt);?>
			</div>
		</div>
	</div>
</div>	
<?php $this->endWidget(); ?>
<div class="b-buttons-left-cont clearfix">
	<a href="<?php echo $this->createUrl('/queue/adminstart',array("category_id"=>$category->id))?>" class="ajax-request b-butt b-start-queue" data-id="<?=$category->id?>">Старт</a>
	<a href="<?php echo $this->createUrl('/queue/adminstop',array("category_id"=>$category->id))?>" class="ajax-request b-butt b-stop-queue" data-id="<?=$category->id?>">Стоп</a>
	<a href="<?php echo $this->createUrl('/queue/adminreturnall',array("category_id"=>$category->id))?>" class="ajax-request b-butt right">Вернуть все в очередь</a>
	<a href="<?php echo $this->createUrl('/queue/adminfreezefree',array("category_id"=>$category->id))?>" class="ajax-request b-butt right">Отложить бесплатные</a>
</div>
<div class="ajax-content">
<?php $this->renderPartial('_table', array(
	'data'=>$data,
	'filter'=>$filter,
	'labels'=>$labels,
	'category'=>$category,
	'count_filter' => $count_filter,
	'count'=>$count,
	'waiting_count'=>$waiting_count,
	'error_count'=>$error_count,
	'freeze_count'=>$freeze_count,
	'start_count'=>$start_count,
	'data_filter'=>$data_filter
)); ?>
</div>