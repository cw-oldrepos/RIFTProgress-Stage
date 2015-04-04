<?php
	$tier 		= isset($_POST['tier']) && strlen($_POST['tier']) > 0 ? $_POST['tier'] : "";
	$server		= isset($_POST['server']) && strlen($_POST['server']) > 0 ? $_POST['server'] : "";
	$url 		= "$server/$tier";

	header("Location: $url");
?>