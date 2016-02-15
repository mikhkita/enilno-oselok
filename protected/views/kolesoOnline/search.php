<? if(count($goods)): ?>
    <? foreach ($goods as $i => $good): ?>
        <li><a href="/<?=$good->type->code?>/<?=$good->code?>">#<?=($titles[$good->id])?></a></li>
    <? endforeach; ?>
<? else: ?>    
    <li><span>Товаров не найдено</span></li>
<? endif; ?>