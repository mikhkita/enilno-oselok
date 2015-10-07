<?

function getArrayValue($value,$type){
	if( $value[0] == "{" && $value[strlen($value)-1] == "}" ){
		$tmp = explode("|", substr($value, 1,-1));
		$value = ($type == "REPLACE")?[0=>array(),1=>array()]:[];
		foreach ($tmp as $v) {
			$arr = explode("=", $v);
			if( count($arr) == 2 ){
				if( $type == "REPLACE" ){
					$value[0][] = trim($arr[0]);
					$value[1][] = trim($arr[1]);
				}else{
					$value[trim($arr[0])] = trim($arr[1]);
				}
			}else{
				die("В параметре \"".$type."\" отсутствует знак \"=\" или он присутствует больше одного раза");
			}
		}
		return $value;
	}else{
		die("Отсутствует одна или обе скобочки \"{}\" у значения параметра \"".$type."\"");
	}
}

$attr = array(3=>"7821",23=>"Лето",17=>23,7=>"225",8=>"45",9=>"18",26=>"б/у",27=>"Томск");

$template = "#[+ATTR=3+] [+ATTR=23;ALT={Лето=Летние|Зима=Зимние}+] [+ATTR=17;FLOAT=2;REPLACE={,=.}+] [+ATTR=7+]/[+ATTR=8+]/[+ATTR=9+] [+ATTR=26+] в [+ATTR=27;ALT={Томск=Томске|Новосибирск=Новосибирске}+]";

preg_match_all("~\[\+([^\+\]]+)\+\]~", $template, $matches);

$rules = $matches[1];

foreach ($rules as $i => $rule) {
	$tmp = explode(";", $rule);
	$params = [];
	foreach ($tmp as $param) {
		$index = stripos($param, "=");
		if( $index > 0 ){
			$key = substr($param,0,$index);
			$value = substr($param, $index+1);
			$params[trim($key)] = trim($value);
		}else{
			die("Отсутствует знак \"=\" у параметра \"".$param."\"");
		}
		
	}

	if( isset($params["ALT"]) ){
		$params["ALT"] = getArrayValue($params["ALT"],"ALT");
	}

	if( isset($params["REPLACE"]) ){
		$params["REPLACE"] = getArrayValue($params["REPLACE"],"REPLACE");
	}

	if( isset($params["ATTR"]) ){
		$val = ( isset($attr[intval($params["ATTR"])]) )?$attr[intval($params["ATTR"])]:"";

		$val = ( isset($params["FLOAT"]) )?number_format((float)$val,intval($params["FLOAT"])):$val;

		if( isset($params["REPLACE"]) ){
			$val = str_replace($params["REPLACE"][0], $params["REPLACE"][1], $val);
		}

		$matches[1][$i] = ( isset($params["ALT"]) && isset($params["ALT"][$val]) )?$params["ALT"][$val]:$val;
	}else{
		die("Отсутствует параметр \"ATTR\"");
	}
}
$result = str_replace($matches[0], $matches[1], $template);

print_r($result);

?>