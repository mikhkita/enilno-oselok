<div style="display:none;">
	<div class="b-popup-filter b-popup">
		<h1>Фильтр</h1>
	<?=CHTML::beginForm(substr($_SERVER["REQUEST_URI"], 0, strpos($_SERVER["REQUEST_URI"], "?")),'POST',array('id'=>'b-filter-form'))?>
		<? foreach ($filter as $field => $item): ?>
		<div class="b-filter-block">
			<h3><?=$labels[$field]?></h3>
			<? if( $item["VIEW"] == "CHECKBOX" ): ?>	
				<?=CHTML::checkBoxList($arr_name."[$field]", $filter_values[$field], $item["FROM"], array("separator"=>"")); ?>
			<? elseif( $item["VIEW"] == "FROMTO" ): ?>
				<?=CHTML::numberField($arr_name."[$field][FROM]", $filter_values[$field]["FROM"]); ?> - 
				<?=CHTML::numberField($arr_name."[$field][TO]", $filter_values[$field]["TO"]); ?>
			<? endif; ?>
		</div>
		<? endforeach; ?>
		<?=CHTML::dropDownList("sort", $_POST["sort"], $sort_fields,array('id'=>'b-sort-2','class'=>'hidden')); ?>
		<?=CHTML::dropDownList("order", $_POST["order"], array("ASC"=>"По возрастанию", "DESC"=>"По убыванию"),array('id'=>'b-order-2','class'=>'hidden')); ?>
		<div class="row buttons">
			<?=CHTML::submitButton('Применить')?>
			<input type="button" onclick="$.fancybox.close(); return false;" value="Закрыть">
		</div>
	<?=CHTML::endForm()?>
	</div>
</div>