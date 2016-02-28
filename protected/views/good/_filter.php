<div style="display:none;">
	<div class="b-popup-filter b-popup-good-filter b-popup">
		<h1>Фильтр<a href="#" class="b-filter-clear-all" style="margin-left: 20px;">Сбросить выделение</a></h1>
	<?=CHTML::beginForm(Yii::app()->createUrl('/good/adminindex',array('good_type_id' => $_GET['good_type_id'])),'POST',array('id'=>'b-filter-form'))?>
		<div class="row b-filter-top-buttons buttons">
			<?=CHTML::submitButton('Применить')?>
			<input type="hidden" name="filter-active" value="1">
			<input type="button" class="b-good-clear-filter" value="Сбросить">
			<input type="button" onclick="$.fancybox.close(); return false;" value="Закрыть">
		</div>
		<div class="b-filter-block">
			<div class="clearfix">
				<div class='b-filter-checkbox'>
					<?=CHTML::checkBox("new_only",$filter_new_only); ?>
					<label for="new_only">Только новые</label>
				</div>
			</div>
		</div>
		<? foreach ($attributes as $field => $item): ?>
			<? if( isset($item["VIEW"]) ): ?>
				<div class="b-filter-block">
					<h3>
						<?=$labels[$field]?>
						<? if( $item["VIEW"] == "CHECKBOX" ): ?>
							<div class="b-filter-check-buttons">
								<a href="#" class="b-filter-check-section">Все</a>
								<a href="#" class="b-filter-uncheck-section">Сбросить</a>
							</div>
						<? elseif( $item["VIEW"] == "FROMTO" ): ?>
							<div class="b-filter-check-buttons">
								<a href="#" class="b-filter-clear-inputs">Сбросить</a>
							</div>
						<? endif;?>
					</h3>
					<div class="clearfix b-filter-<?=$field?>">
						<? if( $item["VIEW"] == "CHECKBOX" ): ?>	
							<?if( count($item["VARIANTS"]) > 50):?>
								<? $selected = array(); if(!empty($filter_values[$field])) foreach ($filter_values[$field] as $multi) $selected[$multi] = array('selected' => 'selected'); ?>
								<?=Chtml::dropDownList($arr_name."[$field]", "", $item["VARIANTS"],array('class'=> 'select2-filter','multiple' => 'true','options' => $selected)); ?>	
							<? else: ?>
								<?=CHTML::checkBoxList($arr_name."[$field]", (isset($filter_values[$field]))?$filter_values[$field]:"", $item["VARIANTS"], array("separator"=>"","template"=>"<div class='b-filter-checkbox'>{input}{label}</div>")); ?>
							<? endif;?>
						<? elseif( $item["VIEW"] == "FROMTO" ): ?>
							<?=CHTML::numberField($arr_name_int."[$field][min]", $filter_values_int[$field]["min"]); ?> - 
							<?=CHTML::numberField($arr_name_int."[$field][max]", $filter_values_int[$field]["max"]); ?>
						<? endif; ?>
					</div>
				</div>
			<? endif; ?>
		<? endforeach; ?>
		
		<div class="row buttons">
			<?=CHTML::submitButton('Применить')?>
			<input type="hidden" name="filter-active" value="1">
			<input type="button" class="b-good-clear-filter" value="Сбросить">
			<input type="button" onclick="$.fancybox.close(); return false;" value="Закрыть">
		</div>
	<?=CHTML::endForm()?>
	</div>
</div>