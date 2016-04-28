<? if(count($goods)): ?>
    <? foreach ($goods as $i => $good): ?>
        <li><a href="/<?=$good['code']?>/<?=$good['varchar_value']?>">#<?=($titles[$good['id']])?></a></li>
    <? endforeach; ?>
<? else: ?>    
    <li><span>Товаров не найдено</span></li>
<? endif; ?>
<input type="hidden" name="query" id="query" value="<?=$query?>">