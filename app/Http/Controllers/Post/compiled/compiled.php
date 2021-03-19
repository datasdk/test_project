<?php
	
	if($_SERVER['REMOTE_ADDR'] != "127.0.0.1"){ 

		exit();

	}

	
	if(isset($_SESSION["compress_scripts"]))
	if($_SESSION["compress_scripts"]){

		Assets::compress("css");

		Assets::compress("js");

		$_SESSION["compress_scripts"] = false;

	}
	

?>