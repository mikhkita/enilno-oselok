<? foreach ($allAttr as $key => $value): ?>
<li class="ui-state-default" data-id="<?=$value->name?>"><p><?=$value->name?></p><input type="hidden" name="sorted[]" value="<?if($key[strlen($key)-1] == "a"):?>attributes<?else:?>interpreters<?endif;?>-<?=$value->id?>"></li>
<? endforeach; ?>