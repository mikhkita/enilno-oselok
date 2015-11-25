<h1><?=$this->adminMenu["cur"]->name?></h1>

<?php $htmlopt = array("separator"=>"","template"=>"<div class='b-advert-checkbox'>{input}{label}</div>");  
$form=$this->beginWidget('CActiveForm',array('id'=>'adverts-form',"action" => $this->createUrl('/advert/adminindex'),"method" => "GET")); ?>
<div class="b-advert-filter clearfix">
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
		<h3><?=$data['AttrName'][58]?></h3>
		<div class="b-group-vars">
	        <? foreach ($data['Attr'][58] as $key => $vars): ?>
	            <div class="b-group-col">
	                <?=CHTML::checkBoxList("Attr[58]", $_GET['Attr'][58], $vars, array("separator"=>"","template"=>"<div>{input}{label}</div>")); ?>
	            </div>
	        <? endforeach; ?>
	    </div>
	</div>
	<div class="b-advert-textarea">
		<textarea name="Codes"><?=$_GET['Codes']?></textarea>
	</div>
</div>	
<div class="clearfix">
	<?php echo CHtml::submitButton("Поиск",array("class"=> "b-butt advert-butt")); ?>
</div>
<div id="adverts-form-hidden" style="display:none;"></div>
<?php $this->endWidget(); ?>
	<table class="b-table b-advert-table" border=1>
		<tr>
			<th>Код</th>
			<th><?=$labels["place_id"]?></th>
			<th><?=$labels["type_id"]?></th>
			<th><?=$labels["city_id"]?></th>
			<th><?=$labels["url"]?></th>
		</tr>
		<?if ($adverts_arr):?>
			<? foreach ($adverts_arr as $name => $place): ?>
				<? foreach ($place as $code => $adverts): ?>
					<? foreach ($adverts as $advert): ?>
					<tr>
						<td><?=$code?></td>
						<td><?=$name?></td>
						<td><?=$advert->type->value?></td>
						<td><?=$advert->city->value?></td>
						<td>
							<? if($advert->url): ?>
							<a href="<?=$advert->getUrl();?>" target="_blank"><?=$advert->getUrl();?></a>
							<? endif; ?>
						</td>
					</tr>
					<? endforeach; ?>
				<? endforeach; ?>
			<? endforeach; ?>
		<? else:?>
			<tr>
				<td colspan="5">Пусто</td>
			</tr>
		<? endif; ?>
	</table>
	<div class="b-upadvert-input-cont clearfix">
		<div class="left">
			<label for="offset">Задержка</label>
			<input type="number" id="offset" class="offset" name="offset" placeholder="В минутах">
		</div>
		<div class="left">
			<label for="interval">Интервал</label>
			<input type="number" id="interval" class="interval" name="interval" placeholder="В минутах">
		</div>
		<a href="<?php echo $this->createUrl('/advert/adminpayadverts')?>" class="b-payadvert b-butt left">Поднять</a>
	</div>
	<div class="b-pagination-cont clearfix">
        <?php $this->widget('CLinkPager', array(
	        'header' => '',
	        'lastPageLabel' => 'последняя &raquo;',
	        'firstPageLabel' => '&laquo; первая', 
	        'pages' => $pages,
	        'prevPageLabel' => '< назад',
	        'nextPageLabel' => 'далее >'
	    )) ?>

        <!-- <div class="b-lot-count">Всего товаров: <?=$good_count?></div> -->
    </div>
   
