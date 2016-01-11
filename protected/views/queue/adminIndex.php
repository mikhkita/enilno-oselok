<h1><?=$this->adminMenu["cur"]->name?> "<?=$category->value?>"</h1>
<? if( $category->id == 2048 ): ?>
	<a class="b-link-back" href="<?php echo $this->createUrl('/queue/adminindex',array("category_id"=>2047))?>">Дром</a>
<? else: ?>
	<a class="b-link-back" href="<?php echo $this->createUrl('/queue/adminindex',array("category_id"=>2048))?>">Авито</a>
<? endif; ?>
<?php $htmlopt = array("separator"=>"","template"=>"<div class='b-advert-checkbox'>{input}{label}</div>");  
$form=$this->beginWidget('CActiveForm',array('id'=>'queue-form',"action" => $this->createUrl('/queue/adminindex',array('partial' => true)),"method" => "GET")); ?>
<div class="b-advert-filter clearfix">
	<div class="b-advert-textarea">
		<textarea name="Codes" placeholder="Список кодов"><?=$_GET['Codes']?></textarea>
	</div>
	<div class="left">
		<div class="b-advert-block">
			<h3>Площадка</h3>
			<div class="clearfix">
			<?=CHtml::checkBoxList("Place[]", $_GET['Place'], $data['Place'],$htmlopt); ?>
			</div>
		</div>
		<div class="b-advert-block">
			<h3><?=$data['AttrName'][37]?></h3>
			<div class="clearfix">
			<?=CHtml::checkBoxList("Attr[37]", $_GET['Attr'][37], $data['Attr'][37],$htmlopt);?>
			</div>
		</div>
		<div class="b-advert-block">
			<h3><?=$data['AttrName'][58]?></h3>
			<div class="clearfix">
			<?=CHtml::checkBoxList("Attr[58]", $_GET['Attr'][58], $data['Attr'][58],$htmlopt);?>
			</div>
		</div>
	</div>
</div>	

<div id="adverts-form-hidden" style="display:none;"></div>
<p><br>Найдено объявлений: <?=$advert_count?></p>
<?php $this->endWidget(); ?>

<div class="b-buttons-left-cont clearfix">
	<a href="<?php echo $this->createUrl('/queue/adminstart',array("category_id"=>$category->id))?>" class="ajax-request b-butt b-start-queue" data-id="<?=$category->id?>">Старт</a>
	<a href="<?php echo $this->createUrl('/queue/adminstop',array("category_id"=>$category->id))?>" class="ajax-request b-butt b-stop-queue" data-id="<?=$category->id?>">Стоп</a>
	<a href="<?php echo $this->createUrl('/queue/adminreturnall',array("category_id"=>$category->id))?>" class="ajax-request b-butt right">Вернуть все в очередь</a>
	<a href="<?php echo $this->createUrl('/queue/adminfreezefree',array("category_id"=>$category->id))?>" class="ajax-request b-butt right">Отложить бесплатные</a>
</div>
<p align="left" style="margin-top: 30px;">Всего объявлений: <?=$count?>. В очереди: <?=$waiting_count?>. С временем: <?=$start_count?>. С ошибкой выполнения: <?=$error_count?> <span class="right">Отложено: <?=$freeze_count?></span><?if($freeze_count):?><a href="<?php echo $this->createUrl('/queue/adminunfreezeall',array("category_id"=>$category->id))?>" class="ajax-request right" style="margin-right: 20px;">Вернуть отложенное</a><?endif;?></p>
<?php $form=$this->beginWidget('CActiveForm'); ?>
	<table class="b-table" border="1" id="b-live" data-delay="3" data-url="<?php echo Yii::app()->createUrl('/'.$this->adminMenu["cur"]->code.'/adminindex',array("partial"=>'true',"category_id"=>$category->id))?>">
		<tr>
			<th style="width: 30px;"><? echo $labels['id']; ?></th>
			<th><? echo $labels['advert_id']; ?></th>
			<th style="width: 16%;"><? echo $labels['action_id']; ?></th>
			<th style="width: 16%;"><? echo $labels['start']; ?></th>
			<th style="width: 16%;"><? echo $labels['state_id']; ?></th>
			<th></th>
		</tr>
		<? if( count($data) ): ?>
			<? foreach ($data as $i => $item): ?>
				<tr<?if(isset($_GET["id"]) && $item->id == $_GET["id"]):?> class="b-refresh"<?endif;?>>
					<td><?=$item->id?></td>
					<td class="align-left">#<?=$item->advert->good->fields_assoc[3]->value?> <?=$item->advert->place->category->value?> <?=$item->advert->good->type->name?> <b><?=$item->advert->city->value?></b> <?=$item->advert->type->value?></td>
					<td><?=$item->action->name?></td>
					<td><?=(($item->start)?date('d-m-Y H:i:s', strtotime($item->start)):"")?></td>
					<td><span class="live-<?=$item->state->code?>"><?=$item->state->name?></span></td>
					<td>
						<? if($this->isRoot()): ?><a href="<?php echo Yii::app()->createUrl('/good/adminindex',array('deleteAdvert'=>$item->advert->id,'result'=>'false'))?>" class="ajax-form ajax-delete not-result" data-warning="Вы действительно хотите удалить объявление &quot;<?=$item->advert->id." ".$item->advert->place->category->value." ".$item->advert->type->value." ".$item->advert->city->value?>&quot;?">Удалить объявление</a><? endif; ?>
						<a href="<?php echo Yii::app()->createUrl('/queue/admintowaiting',array('id'=>$item->id))?>" class="ajax-request">Вернуть</a>
						<? if($this->isRoot()): ?><a href="<?php echo Yii::app()->createUrl('/queue/admindelete',array('id'=>$item->id))?>" class="ajax-request">Удалить из очереди</a><? endif; ?>
					</td>
				</tr>
			<? endforeach; ?>
		<? else: ?>
			<tr>
				<td colspan=10>Пусто</td>
			</tr>
		<? endif; ?>
	</table>
<?php $this->endWidget(); ?>