<div style="display:none;">
	<div class="b-popup-filter b-popup-good-filter b-popup">
		<h1>Фильтр</h1>
	<?=CHTML::beginForm(Yii::app()->createUrl('/good/adminindex',array('goodTypeId' => $_GET['goodTypeId'],'GoodFilter_page' => 1)),'POST',array('id'=>'b-filter-form'))?>
		<? foreach ($attributes as $field => $item): ?>
			<? if( isset($item["VIEW"]) ): ?>
				<div class="b-filter-block">
					<h3><?=$labels[$field]?></h3>
					<div class="clearfix">
						<? if( $item["VIEW"] == "CHECKBOX" ): ?>	
							<?=CHTML::checkBoxList($arr_name."[$field]", (isset($filter_values[$field]))?$filter_values[$field]:"", $item["VARIANTS"], array("separator"=>"","template"=>"<div class='b-filter-checkbox'>{input}{label}</div>")); ?>
						<? elseif( $item["VIEW"] == "FROMTO" ): ?>
							<?=CHTML::numberField($arr_name."[$field][FROM]", $filter_values[$field]["FROM"]); ?> - 
							<?=CHTML::numberField($arr_name."[$field][TO]", $filter_values[$field]["TO"]); ?>
						<? endif; ?>
					</div>
				</div>
			<? endif; ?>
		<? endforeach; ?>
		<?=CHTML::dropDownList("sort[field]", $_POST["sort"]["field"], $sort_fields,array('id'=>'b-sort-2','class'=>'hidden')); ?>
		<?=CHTML::dropDownList("sort[type]", $_POST["sort"]["type"], array("ASC"=>"По возрастанию", "DESC"=>"По убыванию"),array('id'=>'b-order-2','class'=>'hidden')); ?>
		<div class="row buttons">
			<?=CHTML::submitButton('Применить')?>
			<input type="button" onclick="$.fancybox.close(); return false;" value="Закрыть">
		</div>
	<?=CHTML::endForm()?>
	</div>
</div>