<?
	for ($i=0; $i < 300; $i++) {
		file_put_contents("time.txt",$i.". хуй\r\n",FILE_APPEND); 
		sleep(1);
	}
?>