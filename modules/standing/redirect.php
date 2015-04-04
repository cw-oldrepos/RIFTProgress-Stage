<?php
	$tier 		= isset($_POST['tier']) && strlen($_POST['tier']) > 0 ? $_POST['tier'] : "";
	$dungeon 	= isset($_POST['dungeon']) && strlen($_POST['dungeon']) > 0 ? $_POST['dungeon'] : "";
	$mob  		= isset($_POST['mob']) && strlen($_POST['mob']) > 0 ? $_POST['mob'] : "";
	$type  		= isset($_POST['type']) && strlen($_POST['type']) > 0 ? $_POST['type'] : "";
	$display 	= "";

	if ( isset($_POST['server']) ) $display = "server";
	if ( isset($_POST['region']) ) $display = "region";
	if ( isset($_POST['world']) ) $display = "world";

	$url = "$display/$tier";

	if ( $type != "") $url = $url."/$type";
	if ( $dungeon != "") $url = $url."/$dungeon";
	if ( $mob != "") $url = $url."/$mob";		

	header("Location: $url");
?>