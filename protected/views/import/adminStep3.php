<div class="b-import">
	<h1>Импорт</h1>
	<div class="progress">
	    <div class="progress-bar progress-bar-striped active" role="progressbar" style="width:3%">3%</div>
	</div>
	<ul class="b-log"></ul>
</div>
<div class="b-preview" data-url="<?=Yii::app()->createUrl('/'.$this->adminMenu["cur"]->code.'/adminimport')?>">
	<h1>Предпросмотр импорта</h1>	
	<table class="b-table b-import-preview-table" border="1">
		<tr>
			<? foreach ($arResult["TITLES"] as $i => $row): ?>
				<th><?=$row?></th>
			<? endforeach ?>
		</tr>
		<? foreach ($arResult["ROWS"] as $i => $row): ?>
			<tr<?if($row["HIGHLIGHT"] != NULL):?> class="b-<?=$row["HIGHLIGHT"]?>"<?endif;?>>
				<? foreach ($row["COLS"] as $j => $cell): ?>
					<td<?if($cell["HIGHLIGHT"] != NULL):?> class="b-<?=$cell["HIGHLIGHT"]?>"<?endif;?>>
						<? if($cell["VALUE"] != NULL): ?>
							<? foreach ($cell["VALUE"] as $g => $value): ?>
								<div>
									<?=$value?>
									<? if( $cell["HIGHLIGHT"] != "equal" ): ?>
										<input type="hidden" name="IMPORT[ITEMS][][<?=$cell["ID"]?>]" value="<?=$value?>">
									<? endif; ?>
								</div>
							<? endforeach ?>	
						<? endif; ?>
						<? if(intval($cell["ID"]) == $this->codeId): ?>
							<input type="hidden" name="IMPORT[GOODTYPEID]" value="<?=$_POST['GoodTypeId']?>">
							<? if(isset($row["ID"])): ?>
								<input type="hidden" name="IMPORT[ID]" value="<?=$row["ID"]?>">
							<? endif; ?>
						<? endif; ?>
					</td>
				<? endforeach ?>
			</tr>
		<? endforeach ?>
	</table>
	<a href="#" class="b-butt b-import-butt">Импортировать</a>
</div>