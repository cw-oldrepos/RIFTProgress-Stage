<?php
	function load_scripts($page_script, $page_description) {
		$JS_JQUERY		= "{$GLOBALS['root_path']}/js/jquery-1.7.2.js";
		$JS_LIGHTBOX	= "{$GLOBALS['root_path']}/js/lightbox.js";	
		$JS_HEADER		= "{$GLOBALS['root_path']}/js/header.js";	
		$JS_TOOLTIP		= "{$GLOBALS['root_path']}/js/jquery.BA.ToolTip.js";
		$JS_EASING		= "{$GLOBALS['root_path']}/js/jquery.easing.1.3.js";
		$JS_PAGE		= "{$GLOBALS['root_path']}/modules/$page_script/index.js";
		$JS_TINYMCE 	= "{$GLOBALS['root_path']}/js/tinymce/tinymce.min.js";
		$JS_JSCOLOR 	= "{$GLOBALS['root_path']}/js/jscolor/jscolor.js";				
		$CSS_HEADER		= "{$GLOBALS['root_path']}/css/index.css";
		$CSS_LIGHTBOX	= "{$GLOBALS['root_path']}/css/lightbox.css";

		if ( $page_description != "" ) echo "<meta name='description' content='$page_description'>";
		echo "<meta name='author' content='{$GLOBALS['meta_author']}'>";
		echo "<meta name='keywords' content='{$GLOBALS['meta_keywords']}'>";
		echo "<meta http-equiv='Content-Type' content='text/html; charset=UTF-8'>";
		echo "<link rel='stylesheet' type='text/css' href='$CSS_HEADER'>";
		echo "<link rel='stylesheet' type='text/css' href='$CSS_LIGHTBOX'>";

		if ( $GLOBALS['live'] == 1 ) { 
			echo "<script>
					  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
					  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
					  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
					  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

					  ga('create', 'UA-39972522-1', 'topofrift.com');
					  ga('send', 'pageview');";
			echo "</script>";
		}

		echo "<script type='text/javascript' src='$JS_JQUERY'></script>";
		echo "<script type='text/javascript' src='$JS_LIGHTBOX'></script>";
		echo "<script type='text/javascript' src='$JS_HEADER'></script>";
		echo "<script type='text/javascript' src='$JS_TOOLTIP'></script>";	
		echo "<script type='text/javascript' src='$JS_EASING'></script>";
		echo "<script type='text/javascript' src='$JS_PAGE'></script>";
		echo "<script type='text/javascript' src='$JS_TINYMCE'></script>";
		echo "<script type='text/javascript' src='$JS_JSCOLOR'></script>";	

		echo "<script type='text/javascript'>
			tinymce.init({
			    selector: 'textarea#tiny',
			    theme: 'modern',
			    plugins: [
			        'advlist autolink lists link image charmap print preview hr anchor pagebreak',
			        'searchreplace wordcount visualblocks visualchars code fullscreen',
			        'insertdatetime media nonbreaking save table contextmenu directionality',
			        'emoticons template paste textcolor'
			    ],
			    toolbar1: 'insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image',
			    toolbar2: 'print preview media | forecolor backcolor emoticons',
			    image_advtab: true
			 });";
		echo "</script>";	
	}
?>