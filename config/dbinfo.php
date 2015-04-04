<?php
	$dbtype	= $dbhost = $dbname = $dbuser = $dbpass = $dblink = "";

	if ( strpos($server, 'stage') > 0 ) {
		$dbtype	= "mysql";
		$dbhost = "localhost";
		$dbname = "vgtrin5_rift_live";
		$dbuser = "vgtrin5_rift"; 
		$dbpass = "RiftTrinity74108520!";
		$dblink = mysql_connect($dbhost, $dbuser, $dbpass);
	} else {
		$dbtype	= "mysql";
		$dbhost = "localhost";
		$dbname = "vgtrin5_rift_live";
		$dbuser = "vgtrin5_rift"; 
		$dbpass = "RiftTrinity74108520!";
		$dblink = mysql_connect($dbhost, $dbuser, $dbpass);
	}

	if (!$dblink) { die("Could not connect: ". mysql_error()); }
	mysql_select_db($dbname) or die(draw_error_page());
?>