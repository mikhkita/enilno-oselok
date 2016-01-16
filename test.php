<?

$titles = array(
	'R17х7" 5-114.3 б/у',
	'R17х7" 5-114.3 б/у',
	'R17х7" 5-114.3/4-114.3 б/у',
	'R17х7 5-114.3/4-114.3 бу',
	'R17 7J 5-114.3 б. у '
);

$texts = array(
	"Пoставляется на заказ с нашего склада, доставка 9 - 12 дней, включена в цену.
диаметр 17, cверловка 5-114.3, ширина 7,вылет 42, 
Возможна отправка по РФ. Предоставлю доп. фотографии",
	"Поставляется на заказ с нашего склада, доставка 9 - 12 дней, включена в цену.
диаметр 17, cверловка 5-114.3, ширина 7,вылет 38, 
Возможна отправка по России. Предоставлю доп. фото",
	"Поставляется на заказ, доставка 9 - 12 дней, включена в цену.
диаметр 17, cверловка 5-114.3/4-114.3, ширина 7,вылет 50, 
Возможна отправка по России.",
	"Поставляется на заказ с нашего склада, доставка 9 - 12 дней, включена в цену.
диаметр 17, cверловка 5-114.3/4-114.3, ширина 7,вылет 35, 
Возможна отправка по России.",
	"Поставляется на заказ с нашего склада, доставка 3 - 5 дней, включена в цену. 
диаметр 17, cверловка 5-114.3, ширина 7,вылет 38,вес 10.1кг. 
Возможна отправка по России, в Казахстан, Беларусь. Предоставлю дополнительные фотографии."
);

$array = $texts;

foreach ($array as $key1 => $title1) {
	for( $key2 = $key1 ; $key2 < count($array) ; $key2 ++ ){
		$title2 = $array[$key2];

		if( $title1 !== $title2 ){
			$percent = levenshtein(substr($title1, 0, 255), substr($title2, 0, 255));
			// if( $percent > 90 )
				echo $key1." ".$key2." - ".round($percent)."<br>";
		}
	}
}

?>