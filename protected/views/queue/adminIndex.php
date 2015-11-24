<h1><?=$this->adminMenu["cur"]->name?></h1>
<div class="b-buttons-left-cont clearfix">
	<a href="<?php echo $this->createUrl('/queue/adminstart')?>" class="ajax-request b-butt">Старт</a>
	<a href="<?php echo $this->createUrl('/queue/adminstop')?>" class="ajax-request b-butt">Стоп</a>
	<a href="<?php echo $this->createUrl('/queue/adminreturnall')?>" class="ajax-request b-butt right">Вернуть все в очередь</a>
	<a href="<?php echo $this->createUrl('/queue/adminfreezefree')?>" class="ajax-request b-butt right">Отложить бесплатные</a>
</div>
<p align="left" style="margin-top: 30px;">Всего объявлений: <?=$count?>. В очереди: <?=$waiting_count?>. С ошибкой выполнения: <?=$error_count?> <span class="right">Отложено: <?=$freeze_count?></span><?if($freeze_count):?><a href="<?php echo $this->createUrl('/queue/adminunfreezeall')?>" class="ajax-request right" style="margin-right: 20px;">Вернуть отложенное</a><?endif;?></p>
<?php $form=$this->beginWidget('CActiveForm'); ?>
	<table class="b-table" border="1" id="b-live" data-delay="3" data-url="<?php echo Yii::app()->createUrl('/'.$this->adminMenu["cur"]->code.'/adminindex',array("partial"=>'true'))?>">
		<tr>
			<th style="width: 30px;"><? echo $labels['id']; ?></th>
			<th><? echo $labels['advert_id']; ?></th>
			<th style="width: 200px"><? echo $labels['action_id']; ?></th>
			<th style="width: 200px"><? echo $labels['state_id']; ?></th>
			<? if($this->isRoot()): ?>
			<th></th>
			<? endif; ?>
		</tr>
		<? if( count($data) ): ?>
			<? foreach ($data as $i => $item): ?>
				<tr<?if(isset($_GET["id"]) && $item->id == $_GET["id"]):?> class="b-refresh"<?endif;?>>
					<td><?=$item->id?></td>
					<td class="align-left">#<?=$item->advert->good->fields_assoc[3]->value?> <?=$item->advert->place->category->value?> <b><?=$item->advert->city->value?></b> (<?=$item->advert->type->value?>)</td>
					<td><?=$item->action->name?></td>
					<td><span class="live-<?=$item->state->code?>"><?=$item->state->name?></span></td>
					<? if($this->isRoot()): ?>
					<td>
						<a href="<?php echo Yii::app()->createUrl('/good/adminindex',array('deleteAdvert'=>$item->advert->id,'result'=>'false'))?>" class="ajax-form ajax-delete not-result" data-warning="Вы действительно хотите удалить объявление &quot;<?=$item->advert->id." ".$item->advert->place->category->value." ".$item->advert->type->value." ".$item->advert->city->value?>&quot;?">Удалить объвление</a>
						<a href="<?php echo Yii::app()->createUrl('/queue/admintowaiting',array('id'=>$item->id))?>" class="ajax-request">Вернуть</a>
						<a href="<?php echo Yii::app()->createUrl('/queue/admindelete',array('id'=>$item->id))?>" class="ajax-request">Удалить из очереди</a>
					</td>
					<? endif; ?>
				</tr>
			<? endforeach; ?>
		<? else: ?>
			<tr>
				<td colspan=10>Пусто</td>
			</tr>
		<? endif; ?>
	</table>
<?php $this->endWidget(); ?>