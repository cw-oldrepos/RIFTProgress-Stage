<?php
	$ROOT 			= dirname(dirname(dirname(__FILE__)));
	include_once 	"{$ROOT}/configuration.php";

	$module = "privacy";

	function draw_privacy_policy($module) {
		$query = mysql_query(sprintf("SELECT *
						FROM %s
						WHERE document_id = '%s'",
						mysql_real_escape_string($GLOBALS['table_document']),
						mysql_real_escape_string('privacy_policy')
						)) or die(draw_error_page());
		$privacy_policy = mysql_fetch_array($query);

		echo "<div class='primary_content_block'>";
			echo "<article class='content_article'>";
				echo "<div class='content_article_content'>";
					echo "<h1>{$GLOBALS[$module]['title']}</h1>";
					echo "<hr><div class='clear'></div>";
					echo $privacy_policy['content'];
				echo "</div>";
			echo "</article>";
		echo "</div>";
	}
?>