<?
include_once(dirname(__FILE__).'/simple_html_dom.php');
$html = new simple_html_dom();
$ch = curl_init();
$url = "https://www.avito.ru/profile/login";
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1); 
curl_setopt($ch, CURLOPT_REFERER, "https://www.avito.ru"); 
curl_setopt($ch, CURLOPT_COOKIEFILE,  dirname(__FILE__).'/cookie.txt');
curl_setopt($ch, CURLOPT_COOKIEJAR,  dirname(__FILE__).'/cookie.txt');
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, array(
	'next'=>'/profile',
	'login'=>'vladis1ove81@gmail.com',
	'password'=>'Friday13'
));
curl_exec( $ch );

$url = "https://www.avito.ru/additem/image";
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_POSTFIELDS, array(
	'image'=>'@'.dirname(__FILE__)."/1.jpg"
));

$image_id = json_decode(curl_exec( $ch ))->id;

$url = "https://www.avito.ru/additem";
curl_setopt($ch, CURLOPT_URL, $url);

$html = str_get_html(curl_exec( $ch ));
        $token = array();
        $token['name'] = $html->find('input[name^=token]',0)->name;
        $token['value'] = $html->find('input[name^=token]',0)->value;

curl_setopt($ch, CURLOPT_POSTFIELDS, array(
	$token['name'] => $token['value'],
	'email'=>'vladis1ove81@gmail.com',
	'authState'=>'phone-edit',
	'private' => 1,
	'seller_name' => "Владислав",
	'phone' => '8+952+896-09-88',
	'root_category_id' => 1,
	'category_id' => 10,
	'params[5]' => 19,
	'params[709]' => 10048,
	'location_id' => 657600,
	'metro_id' => "",
	'district_id' => "",
	'road_id' => "",
	'params[733]' => 10359,
	'params[734]' => 10376,
	'params[731]' => 10312,
	'params[732]' => 10340,
	'title' => 'Шины',
	'description' => 'Шины',
	'price' => 1000,
	'images[]' => $image_id,
	'rotate['.$image_id.']' => 0,
	'image' => "",
	'videoUrl' => "",
	'service_code' => 'free'

));     
$html = str_get_html(curl_exec( $ch ));
$captcha = $html->find('.form-captcha-image',0)->src;
curl_setopt($ch, CURLOPT_URL, 'https://www.avito.ru'.$captcha);

curl_setopt($ch, CURLOPT_BINARYTRANSFER,1);  
    $out = curl_exec($ch);  
    $image_sv = dirname(__FILE__).'/123.jpg';  
    file_put_contents($image_sv, $out); 
$url = "http://rucaptcha.com/in.php";
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_POSTFIELDS, array(
	'key'=>'0b07ab2862c1ad044df277cbaf7ceb99',
	'file'=> '@'.$image_sv
));

$captcha = curl_exec($ch);
while ($captcha == 'ERROR_NO_SLOT_AVAILABLE') {
	sleep(5);
    $captcha = curl_exec($ch);
} 
if(strpos($captcha, "|") !== false) {
	$captcha = substr($captcha, 3);
	$url = "http://rucaptcha.com/res.php?";
			$params = array(
		    	'key' => '0b07ab2862c1ad044df277cbaf7ceb99',
		    	'action' => 'get',
		    	'id' => $captcha
			);
	$url .= urldecode(http_build_query($params));
	curl_setopt($ch, CURLOPT_URL, $url);
	$captcha = curl_exec($ch);
	while ($captcha == 'CAPCHA_NOT_READY') {
		sleep(1);
   		$captcha = curl_exec($ch);
	} 
	if(strpos($captcha, "|") !== false) {
		$captcha = substr($captcha, 3);
		$url = "https://www.avito.ru/additem/confirm";
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_POSTFIELDS, array(
			'captcha' => $captcha,
			'done' => "",
			'subscribe-position' => '0'
		));     
		print_r(curl_exec($ch));
	} else {
		die($captcha."РАСШИФРОВКА НЕ ПРИШЛА");
	}
} else {
	die($captcha."КАПТЧА НЕ ОТПРАВИЛАСЬ");
}

curl_close($ch);    

?> 
