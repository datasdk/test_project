<?php


	$promotion_code = $_POST["promotion_code"];


	echo Promotion::set($promotion_code);

?>