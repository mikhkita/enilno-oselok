<h1><?=$name?></h1>
<div class="b-buttons-left">
	<a href="#" class="b-select-all b-butt">Выделить все</a>
	<a href="#" class="b-select-none b-butt">Снять выделение</a>
</div>
<div class="b-buttons-right">
	<a href="#" onclick="$('form').submit(); return false;" class="b-butt">Экспортировать</a>
	<p class="b-show-count">Показано: <span><?=count($data->goods)?></span></p>
</div>
<?php $form=$this->beginWidget('CActiveForm',array("action"=>$this->createUrl('/export/adminexport',array('id'=>$id)))); ?>
	<table class="b-table b-export-preview" border="1">
		<thead>
			<tr>
				<? foreach ($fields as $field): ?>
					<th<?if($field["VALUE"]->width>20):?> style="min-width:<?=$field["VALUE"]->width?>px; max-width:<?=$field["VALUE"]->width?>px;"<?endif;?>><?=$field["VALUE"]->name?></th>
				<? endforeach; ?>
			</tr>
			<tr id="b-filter">
				<? foreach ($fields as $key => $field): ?>
					<td>
						<? if( isset($field["VARIANTS"]) ): ?>
							<ul class="b-filter-export">
								<? foreach ($field["VARIANTS"] as $i => $variant): ?>
									<li><input type="checkbox" checked id="a-<?=$i?>" value="<?=$variant?>"><label for="a-<?=$i?>"><?=$variant?><label></li>
								<? endforeach; ?>
							</ul>
						<? endif; ?>
					</td>
				<? endforeach; ?>
			</tr>
		</thead>
		<tbody id="target">
			<? if( count($data->goods) ): ?>
				<? foreach ($data->goods as $i => $item): ?>
					<? 
						$attr = $item->fields_assoc;
						$attr = $attr + $dynObjects;
					?>
					<tr data-id="<?=$item->id?>">
						<? foreach ($fields as $field): ?>
							<td>
								<? if( $field["TYPE"] == "attr" ): ?>
									<? if( isset($attr[$field["VALUE"]->id]) ): ?>
										<div><p>
											<? if( is_array($attr[$field["VALUE"]->id]) ): ?>
												<? 
													$printArr = array(); 
													foreach ($attr[$field["VALUE"]->id] as $key => $value){
														$printArr[] = $value->value;
													} 
													echo implode("/", $printArr);
												?>
											<? else: ?>
												<?=$attr[$field["VALUE"]->id]->value?>
											<? endif; ?>
										</p></div>
									<? endif; ?>
								<? else: ?>
									<div><p><?=Interpreter::generate($field["VALUE"]->id,$item,$dynObjects)?></p></div>
								<? endif; ?>
							</td>
						<? endforeach; ?>
					</tr>
				<? endforeach; ?>
			<? else: ?>
				<tr>
					<td colspan=10>Пусто</td>
				</tr>
			<? endif; ?>
		</tbody>
	</table>
	<? foreach ($dynValues as $i => $dynamic): ?>
		<input type="hidden" name="dynamic_values[<?=$i?>]" value="<?=$dynamic?>">
	<? endforeach; ?>
	<textarea name="ids" id="ids" cols="30" rows="10" style="display:none;"></textarea>
<?php $this->endWidget(); ?>