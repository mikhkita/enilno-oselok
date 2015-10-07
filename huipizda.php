<?
    $lot = "f155971023";

    $max_price = intval("1801");
    $step=intval("100");
    $cur_price = intval("1601");
    $bid = (($cur_price+$step*2)<=$max_price) ? ($cur_price+$step) : ( (($cur_price+$step)<=$max_price) ? $max_price : 0);
    if($bid===0) {
        die();
        // state = 4;
    }

	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, "https://www.yahon.ru/auth");
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_HEADER, 1);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, array(
		'set_login'=>'svc1',
		'set_pass'=>'kb5e1law',
    // 'set_login'=>'p_e_a_c_e@mail.ru',
     // 'set_pass'=>'261192',
		'set_from'=>'',
		'to_remain_here'=>'Y'
	));
	$content = curl_exec($ch);

    preg_match_all('/^Set-Cookie:\s*([^;]*)/mi', $content, $result);
    $cookies = implode(';', $result[1]);


    curl_setopt($ch, CURLOPT_URL, "https://www.yahon.ru/yahoo/bid_preview");
    curl_setopt($ch, CURLOPT_POSTFIELDS, array(
      'user_rate'=>$bid,
      'quantity'=>'1',
      'lot_no'=>$lot
    ));
    curl_setopt($ch, CURLOPT_COOKIE, $cookies);

    $content = curl_exec($ch);
    preg_match_all('/.(input [^>]*)/m', $content, $result);
    $fields = array(
      'comments' => '',
      'deliveryType' =>'2',
      'nocash' => '0.21847357973456382'
    );

    foreach ($result[1] as $key => $value) {
        if($key!=0) {
            preg_match_all('/.*name="([^"]*)".*/',$value,$name);
            preg_match_all('/.*value="([^"]*)".*/',$value,$val);
            $fields[$name[1][0]] = $val[1][0];
        }
    }

    if(!isset($fields["signature"])) {
        echo "Бабла нету либо цена больше";
        die();
        //state = 4;
    }
    // $fields['user_rate'] = 1100;

    curl_setopt($ch, CURLOPT_URL, "https://www.yahon.ru/yahoo/bid_place");
    curl_setopt($ch, CURLOPT_POSTFIELDS,$fields);

    $content = curl_exec($ch);

    preg_match_all('/.*signature=([^"]*)".*/',$content,$sign);
    preg_match_all('/.*token=([^&]*)&.*/',$content,$token);

    $params = array();
    $fields["signature"] = $sign[1][0];
    $fields["token"] = $token[1][0];
    foreach ($fields as $key => $value) {
        $params[] = $key."=".$value;
    }

    curl_setopt($ch, CURLOPT_COOKIE, $cookies.";lotViewMode=1");
    curl_setopt($ch, CURLOPT_URL,"https://www.yahon.ru/modules/yahoo_auction/data_request/rate.php?".implode("&", $params));
    curl_setopt($ch, CURLOPT_POST, 0);

    $content = curl_exec($ch);
    print_r($content);
    if(mb_stripos($content,"Ставка принята",0,"UTF-8")) {
        echo "заебись STATE RAVNO 2";
    } else {
        echo "STATE RAVNO 0";
    }
    curl_close($ch);
?> 
