<?php
$this->pageTitle=Yii::app()->name . ' - Ошибка';
$this->breadcrumbs=array(
	'Error',
);
?>
<h1>Ошибка</h1>

<h2>Error <?php echo $code; ?></h2>

<div class="error">
<?php echo Yii::app()->errorHandler->error["message"]; ?><br>
<?php $tmp = explode("/koleso/",Yii::app()->errorHandler->error["file"]); echo $tmp[1]."(".Yii::app()->errorHandler->error["line"].")"; ?>
<? 
$arr = explode("#", Yii::app()->errorHandler->error["trace"]); 

foreach ($arr as $key => $value) {
	echo $value."<br>";
}

?>
</div>