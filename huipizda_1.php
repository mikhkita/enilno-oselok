<?

    $curl = curl_init();
    curl_setopt($curl, CURLOPT_COOKIESESSION, true); 
    curl_setopt($curl, CURLOPT_HEADER, 1); 
    // curl_setopt($curl, CURLOPT_COOKIEFILE, "cookiefile"); 
    // curl_setopt($curl, CURLOPT_COOKIEFILE,  $_SERVER['DOCUMENT_ROOT'].'/cookie.txt');
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true); 
    curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true); 
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($curl, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; Windows NT 6.0; ru; rv:1.9.1.3) Gecko/20090824 Firefox/3.5.3'); 
    curl_setopt($curl, CURLOPT_URL, 'http://www.yahon.ru/auth/index/'); 
    $html = curl_exec($curl);

    // preg_match('/<input type="hidden" id="login_csrf" value="(.*)"/Uis',$html, $login_csrf);

    // $login_csrf = $login_csrf[1];

    //echo $login_csrf;

    // $post = "email=ctac4ever@gmail.com&password=test123&remember=1&login_csrf=".$login_csrf."&submit=Вход&formid=login";

    // curl_setopt($curl, CURLOPT_URL, 'https://monitor.masterfolio.ru/auth/login');
    // curl_setopt($curl, CURLOPT_POSTFIELDS, $post);
    // $html = curl_exec($curl);
    echo $html;

?>