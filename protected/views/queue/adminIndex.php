<div class="b-section-nav clearfix">
	<div class="b-section-nav-back clearfix">
		<? if( $category->id != 2047 ): ?><a href="<?php echo $this->createUrl('/queue/adminindex',array("category_id"=>2047))?>" class="b-link left">Дром</a><? endif; ?>
		<? if( $category->id != 2048 ): ?><a href="<?php echo $this->createUrl('/queue/adminindex',array("category_id"=>2048))?>" class="b-link left">Авито</a><? endif; ?>
		<? if( $category->id != 3875 ): ?><a href="<?php echo $this->createUrl('/queue/adminindex',array("category_id"=>3875))?>" class="b-link left">Вконтакте</a><? endif; ?>
		<div class="left b-kit-switcher-cont clearfix">
			<span>Выкладка: </span>
			<a href="#" class="b-kit-switcher right<?if($this->place_states[$category->id] == "on") echo" checked";?>" data-on="updateQueue" data-off="updateQueue" data-on-href="<?php echo $this->createUrl('/queue/adminstart',array("category_id"=>$category->id))?>" data-off-href="<?php echo $this->createUrl('/queue/adminstop',array("category_id"=>$category->id))?>" data-id="<?=$category->id?>" >
			    <div class="b-kit-rail">
			        <div class="b-kit-state1">Вкл.</div>
			        <div class="b-kit-slider"></div>
			        <div class="b-kit-state2">Выкл.</div>
			    </div>
			</a>
		</div>
		<ul class="b-section-menu clearfix left">
			<li><a href="<?php echo $this->createUrl('/queue/adminreturnall',array("category_id"=>$category->id))?>" class="ajax-form ajax-delete not-result" data-warning="Вы действительно хотите вернуть отфильтрованные задания?">Вернуть</a></li>
			<li><a href="<?php echo $this->createUrl('/queue/adminchangestate',array("category_id"=>$category->id, "state"=>4))?>" class="ajax-form ajax-delete not-result" data-warning="Вы действительно хотите отложить отфильтрованные задания?">Отложить</a></li>
			<li><a href="<?php echo $this->createUrl('/queue/admindeleteall',array("category_id"=>$category->id))?>" class="ajax-form ajax-delete not-result" data-warning="Вы действительно хотите удалить отфильтрованные задания?">Удалить</a></li>
		</ul>
	</div>
</div>
<h1 class="b-with-nav"><?=$this->adminMenu["cur"]->name?> "<?=$category->value?>"</h1>


<?php $htmlopt = array("separator"=>"","template"=>"<div class='b-advert-checkbox'>{input}{label}</div>");
$form=$this->beginWidget('CActiveForm',array('id'=>'filter-form',"action" => $this->createUrl('/'.$this->adminMenu["cur"]->code.'/adminindex',array("partial"=>'true',"category_id"=>$category->id)),"method" => "GET",'htmlOptions' => array("data-delay"=> 5))); ?>
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
		<? if( $category->id != 3875 ): ?>
			<div class="b-advert-block">
				<h3>Город выкладки</h3>
				<div class="clearfix">
				<?=CHtml::checkBoxList("Attr[58]", $_GET['Attr'][58], $data_filter['Attr'][58],$htmlopt);?>
				</div>
			</div>
		<? endif; ?>
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
<div class="b-buttons-center-cont clearfix">
	<a href="#" class="b-butt" id="b-queue-filter">Фильтровать</a>
	<!-- <a href="<?php echo $this->createUrl('/queue/adminstop',array("category_id"=>$category->id))?>" class="ajax-request b-butt b-stop-queue" data-id="<?=$category->id?>">Стоп</a>
	<a href="<?php echo $this->createUrl('/queue/adminreturnall',array("category_id"=>$category->id))?>" class="ajax-request b-butt right">Вернуть все в очередь</a>
	<a href="<?php echo $this->createUrl('/queue/adminfreezefree',array("category_id"=>$category->id))?>" class="ajax-request b-butt right">Отложить бесплатные</a> -->
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