<?
	$ch = curl_init();
	$url = "http://photodoska.ru/?a=auth";
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
	curl_setopt($ch, CURLOPT_COOKIEJAR, dirname(__FILE__).'/cookie.txt');
	curl_setopt($ch, CURLOPT_COOKIEFILE,  dirname(__FILE__).'/cookie.txt');
	curl_setopt($ch, CURLOPT_POST, true);
	curl_setopt($ch, CURLOPT_POSTFIELDS, array(
		'data53'=>'Vladis1ove',
		'data84'=>'261192'
	));
	curl_exec( $ch );

	$url = "http://photodoska.ru/?a=upload_photo";
	$cfile = curl_file_create(dirname(__FILE__).'/4-1.jpg');
	curl_setopt($ch, CURLOPT_POSTFIELDS, array('upload' => $cfile));
	curl_setopt($ch, CURLOPT_URL, $url);
	$photo = substr(curl_exec($ch),2);
	
	$url = "http://photodoska.ru/?a=add_ad";
	$title = "титл1";
	$text = "<p>фывфыв</p>"; 
	$tel = '89231231212';
	$price = 1000;
	$data = array(
		'data[0][name]' => 'city_id',
		'data[0][value]' => 1,
		'data[1][name]' => 'parent_rubric_id',
		'data[1][value]' => 1,
		'data[2][name]' => 'child_rubric_id',
		'data[2][value]' => 42,
		'data[3][name]' => 'title',
		'data[3][value]' => $title,
		'data[4][name]' => 'text',
		'data[4][value]' => $text,
		'data[6][name]' => 'photo_1',
		'data[6][value]' => $photo,
		'data[11][name]' => 'price',
		'data[11][value]' => $price,
		'data[12][name]' => 'phone',
		'data[12][value]' => $tel,
		'data[13][name]' => 'comment_permission',
		'data[13][value]' => 0
		);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
	curl_setopt($ch, CURLOPT_URL, $url);
	for ($i=0; $i <30 ; $i++) { 
		curl_exec( $ch );
	}
	print_r(curl_exec( $ch ));

?> 
