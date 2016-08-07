<div class="for_all b-popup-city">
    <div class="city-top">
        <? if($show === 1): ?>
            <h3 class="city-popup-show">Выберите ближайший к вам город</h3>
        <? else:?>
            <h3>Выбор города</h3>
        <? endif; ?>
        <h4>Федеральный округ<span>Город</span></h4>
        <div class="clearfix city-tabs popup-fo">
            <ul class="left">
                <? $i = 0; foreach ($cities as $name => $group): ?>
                    <li><a href="#fo-<?=$i?>"><?=$name?></a></li>
                <? $i++; endforeach; ?>
            </ul>
            <? $i = 0; foreach ($cities as $name => $group): ?>
                <div id="fo-<?=$i?>" class="popup-cities clearfix left">
                    <? foreach ($group as $col): ?>
                        <ul class="left">
                            <? foreach ($col as $city): ?>
                                <li><a href="http://<?=$city['code']?>.<?=Yii::app()->params["host"]?><?=$_SERVER["REQUEST_URI"]?>"><?=$city['name']?></a></li>
                            <? endforeach; ?>
                        </ul>
                    <? endforeach; ?>
                </div>
            <? $i++; endforeach; ?>
        </div>
    </div>
    <div class="city-input clearfix">
        <select class="city-select left" name="city" required>
            <option></option>
            <? foreach ($cities as $name => $group): ?>
            <optgroup label="<?=$name?>">
                <? foreach ($group as $col): ?>
                    <? foreach ($col as $city): ?>
                        <option value="http://<?=$city['code']?>.<?=Yii::app()->params["host"]?><?=$_SERVER["REQUEST_URI"]?>"><?=$city['name']?></option>
                    <? endforeach; ?>
                <? endforeach; ?>
            </optgroup>
            <? endforeach; ?>
        </select>
        <a class="right b-orange-butt b-city-link">Выбрать</a>
    </div>
</div>