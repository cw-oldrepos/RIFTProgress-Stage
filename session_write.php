<?php
	include_once 	"configuration.php";
	
	if ( isset($_GET['active']) ) {
		$_SESSION['active'] = $_GET['active'];
	}
?>