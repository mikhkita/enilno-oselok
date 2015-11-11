<?
include_once(dirname(__FILE__).'/simple_html_dom.php');
$html = new simple_html_dom();
$ch = curl_init();

	$url = "https://www.avito.ru/profile/login";
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_AUTOREFERER,true);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
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
		'image'=> new CurlFile(dirname(__FILE__)."/6.jpg")
	));

	$image_id1 = json_decode(curl_exec( $ch ))->id;
	$url = "https://www.avito.ru/additem/image";
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_POSTFIELDS, array(
		'image'=> new CurlFile(dirname(__FILE__)."/8.jpg")
	));

	$image_id2 = json_decode(curl_exec( $ch ))->id;

	$url = "https://www.avito.ru/additem";
	curl_setopt($ch, CURLOPT_URL, $url);

	$html = str_get_html(curl_exec( $ch ));
    $token = array();
    $token['name'] = $html->find('input[name^=token]',0)->name;
    $token['value'] = $html->find('input[name^=token]',0)->value;

	$params_arr =  array(
		$token['name'] => $token['value'],
		'email'=>'vladis1ove81@gmail.com',
		'authState'=>'phone-edit',
		'private' => 1,
		'source' => "add",
		'seller_name' => "Владислав",
		'phone' => '8+952+896-09-88',
		'root_category_id' => 1,
		'category_id' => 10,
		'location_id' => 657600,
		'metro_id' => "",
		'district_id' => "",
		'road_id' => "",
		'params[5]' => 19,
		'params[709]' => 10048,
		'params[733]' => 10359,
		'params[734]' => 10376,
		'params[731]' => 10312,
		'params[732]' => 10340,
		'title' => 'Что-то123123',
		'description' => 'Что-то тамasdasd',
		'price' => 1000,
		'images' => array($image_id1,$image_id2),
		// 'images' => $image_id2,
		'rotate['.$image_id1.']' => 0,
		'rotate['.$image_id2.']' => 0,
		'image' => "",
		'videoUrl' => "",
		'service_code' => 'free'

	);
	curl_setopt($ch, CURLOPT_POSTFIELDS,$params_arr);     
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
			$html = str_get_html(curl_exec( $ch ));
			$id = $html->find('.content-text a[rel="nofollow"]',0)->href;
			$id = end(explode("_", $id));
			// print_r($id);

			$url ="https://www.avito.ru/".$id;
			curl_setopt($ch, CURLOPT_URL, $url);
			$html = str_get_html(curl_exec( $ch ));
			$href = $html->find('.item_change',0)->href;
			$href = "https://www.avito.ru".substr($href, 0, -3)."/edit";

			curl_setopt($ch, CURLOPT_URL, $href);
			$html = str_get_html(curl_exec( $ch ));
			$version = $html->find('input[name="version"]',0)->value;

			$params_arr['version'] = $version;
			$params_arr['source'] = 'edit';	
			$params_arr['title'] = "Измена";
			$params_arr['description'] = "Измена1";
			curl_setopt($ch, CURLOPT_URL, $href);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $params_arr);
			curl_exec($ch);
			$url = $href."/confirm";
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_POSTFIELDS, array(
				'done' => "",
				'subscribe-position' => '1'
			));     
			print_r(curl_exec( $ch ));
		} else {
			die($captcha."РАСШИФРОВКА НЕ ПРИШЛА");
		}
	} else {
		die($captcha."КАПТЧА НЕ ОТПРАВИЛАСЬ");
	}
curl_close($ch);    

?> 
