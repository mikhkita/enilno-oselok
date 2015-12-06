<?
	include_once(dirname(__FILE__).'/simple_html_dom.php');
	include_once  Yii::app()->basePath.'/phpexcel/Classes/PHPExcel.php';
	$html = new simple_html_dom();
	$i = 7;
	$html = file_get_html("http://baza.drom.ru/user/".$i."/wheel/");


	
	// include_once  Yii::app()->basePath.'/phpexcel/Classes/PHPExcel/IOFactory.php';

	$phpexcel = new PHPExcel(); // Создаём объект PHPExcel
	$filename = "example.xlsx";

	/* Каждый раз делаем активной 1-ю страницу и получаем её, потом записываем в неё данные */
	$page = $phpexcel->setActiveSheetIndex(0); // Делаем активной первую страницу и получаем её
	// foreach($data as $i => $ar){ // читаем массив
	// 	foreach($ar as $j => $val){
			$page->setCellValueByColumnAndRow(0,1,"asd"); // записываем данные массива в ячейку
			$page->getStyleByColumnAndRow(0,1)->getAlignment()->setWrapText(true);
		// }
	// }
	$page->setTitle($title); // Заголовок делаем "Example"
	
	for($col = 'A'; $col !== 'Z'; $col++) {
	    $page->getColumnDimension($col)->setAutoSize(true);
	}

	/* Начинаем готовиться к записи информации в xlsx-файл */
	$objWriter = PHPExcel_IOFactory::createWriter($phpexcel, 'Excel2007');
	/* Записываем в файл */
	$objWriter->save($filename);

?>