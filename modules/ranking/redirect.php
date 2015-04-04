<?php
	if ( isset($_POST['view']) ) {
		$tier 		= isset($_POST['tier']) && strlen($_POST['tier']) > 0 ? $_POST['tier'] : "";
		$dungeon 	= isset($_POST['dungeon']) && strlen($_POST['dungeon']) > 0 ? $_POST['dungeon'] : "";
		$poll 		= isset($_POST['poll']) && strlen($_POST['poll']) > 0 ? strtolower($_POST['poll']) : "pra";
		$display 	= $url = "";

		if ( isset($_POST['server']) ) $display = "server";
		if ( isset($_POST['region']) ) $display = "region";
		if ( isset($_POST['world']) ) $display = "world";

		if ( isset($_POST['type']) && strlen($_POST['type']) > 0 ) {
			$url = "$poll/$display/$tier/".$_POST['type'];
		} else {
			$url = "$poll/$display/$tier";

			if ( $dungeon != "") $url = $url."/$dungeon";
			if ( $mob != "") $url = $url."/$mob";		
		}

		header("Location: $url");
	} else if ( isset($_POST['system']) ) {
		$tier 		= isset($_POST['tier']) && strlen($_POST['tier']) > 0 ? $_POST['tier'] : "";
		$dungeon 	= isset($_POST['dungeon']) && strlen($_POST['dungeon']) > 0 ? $_POST['dungeon'] : "";
		$display 	= isset($_POST['display']) && strlen($_POST['display']) > 0 ? strtolower($_POST['display']) : "world";
		$poll 		= $url = "";

		if ( isset($_POST['prad']) ) $poll = "prad";
		if ( isset($_POST['pra']) ) $poll = "pra";
		if ( isset($_POST['pram']) ) $poll = "pram";

		if ( isset($_POST['type']) && strlen($_POST['type']) > 0 ) {
			$url = "$poll/$display/$tier/".$_POST['type'];
		} else {
			$url = "$poll/$display/$tier";

			if ( $dungeon != "") $url = $url."/$dungeon";
		}

		header("Location: $url");
	}
?>