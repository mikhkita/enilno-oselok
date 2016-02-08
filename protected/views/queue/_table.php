<p align="left" style="margin-top: 30px;">Всего объявлений: <?=$count?>. В очереди: <?=$waiting_count?>. С временем: <?=$start_count?>. С ошибкой выполнения: <?=$error_count?> <span class="right">Отложено: <?=$freeze_count?></span></p>
<p><br>Найдено объявлений: <?=$count_filter?></p>
<?php $form=$this->beginWidget('CActiveForm'); ?>
	<table class="b-table" border="1">
		<tr>
			<th style="width: 30px;">ID&nbsp;объяв.</th>
			<th>Товар</th>
			<th>Город</th>
			<th style="min-width: 22%;"><? echo $labels['action_id']; ?></th>
			<th><? echo $labels['start']; ?></th>
			<th><? echo $labels['state_id']; ?></th>
		</tr>
		<? if( count($data) ): ?>
			<? foreach ($data as $i => $item): ?>
				<tr<?if(isset($_GET["id"]) && $item->id == $_GET["id"]):?> class="b-refresh"<?endif;?>>
					<td class="align-left"><?=$item->advert_id?></td>
					<td class="align-left">#<?=$item->advert->good->fields_assoc[3]->value?> <?=$item->advert->good->type->name?></td>
					<td class="align-left"><b><?=$item->advert->city->value?></b><?if($category->id == 2047):?> <?=$item->advert->type->value?><?endif;?></td>
					<td class="align-left"><?=$item->action->name?></td>
					<td class="align-left"><?=(($item->start)?date('d-m-Y H:i:s', strtotime($item->start)):"")?></td>
					<td class="align-left"><span class="live-<?=$item->state->code?>"><?=$item->state->name?></span></td>
				</tr>
			<? endforeach; ?>
		<? else: ?>
			<tr>
				<td colspan=10>Пусто</td>
			</tr>
		<? endif; ?>
	</table>
<?php $this->endWidget(); ?>