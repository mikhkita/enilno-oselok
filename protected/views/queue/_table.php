<p align="left" style="margin-top: 30px;">Всего объявлений: <?=$count?>. В очереди: <?=$waiting_count?>. С временем: <?=$start_count?>. С ошибкой выполнения: <?=$error_count?> <span class="right">Отложено: <?=$freeze_count?></span><?if($freeze_count):?><a href="<?php echo $this->createUrl('/queue/adminunfreezeall',array("category_id"=>$category->id))?>" class="ajax-request right" style="margin-right: 20px;">Вернуть отложенное</a><?endif;?></p>
<p><br>Найдено объявлений: <?=$count_filter?></p>
<?php $form=$this->beginWidget('CActiveForm'); ?>
	<table class="b-table" border="1">
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