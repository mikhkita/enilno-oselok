<h1><?=$this->adminMenu["cur"]->name?></h1>
<?php $form=$this->beginWidget('CActiveForm'); ?>
	<table class="b-table" border="1" id="b-live" data-delay="3" data-url="<?php echo Yii::app()->createUrl('/'.$this->adminMenu["cur"]->code.'/adminindex',array("partial"=>'true'))?>">
		<tr>
			<th style="width: 30px;"><? echo $labels['id']; ?></th>
			<th><? echo $labels['advert_id']; ?></th>
			<th style="width: 200px"><? echo $labels['action_id']; ?></th>
			<th style="width: 200px"><? echo $labels['state_id']; ?></th>
		</tr>
		<? if( count($data) ): ?>
			<? foreach ($data as $i => $item): ?>
				<tr<?if(isset($_GET["id"]) && $item->id == $_GET["id"]):?> class="b-refresh"<?endif;?>>
					<td><?=$item->id?></td>
					<td class="align-left">#<?=$item->advert->good->fields_assoc[3]->value?> <?=$item->advert->place->category->value?> <b><?=$item->advert->city->value?></b> (<?=$item->advert->type->value?>)</td>
					<td><?=$item->action->name?></td>
					<td><span class="live-<?=$item->state->code?>"><?=$item->state->name?></span></td>
				</tr>
			<? endforeach; ?>
		<? else: ?>
			<tr>
				<td colspan=10>Пусто</td>
			</tr>
		<? endif; ?>
	</table>
<?php $this->endWidget(); ?>