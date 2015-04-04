<?php
	include_once 	"properties.php";
	
	if ( isset($_SESSION) ) {
		session_destroy();
		header("Location: ".$PAGE_INDEX);
		exit;
	}
?>