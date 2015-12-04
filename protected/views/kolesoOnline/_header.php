<div style="display:none">
    <div id="b-popup-city">
        <div class="for_all b-popup-city">
            <div class="city-top">
                <h3>Выбор города</h3>
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
                                        <li><a href="#"><?=$city['name']?></a></li>
                                    <? endforeach; ?>
                                </ul>
                            <? endforeach; ?>
                        </div>
                    <? $i++; endforeach; ?>
                </div>
            </div>
            <div class="city-input clearfix">
                <?php $form=$this->beginWidget('CActiveForm', array(
                    'enableAjaxValidation'=>false,
                    'method' => 'POST',
                    'id' => "city-form"
                )); ?>
                <select class="city-select left" name="city" required>
                    <option></option>
                    <? foreach ($cities as $name => $group): ?>
                    <optgroup label="<?=$name?>">
                        <? foreach ($group as $col): ?>
                            <? foreach ($col as $city): ?>
                                <option value="<?=$city['name']?>"><?=$city['name']?></option>
                            <? endforeach; ?>
                        <? endforeach; ?>
                    </optgroup>
                    <? endforeach; ?>
                </select>
                <input type="submit" class="right b-orange-butt" value="Выбрать">
                <?php $this->endWidget(); ?> 
            </div>
        </div>
    </div>
</div>