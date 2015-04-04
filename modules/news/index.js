var block_rank_height = "";

function pageLoad() {}

$(function() {
	$(".block_subtitle.click").click(function() {
		if ( block_rank_height == "" ) block_rank_height = $(this).parent().css("height");

		var pane_id 	= $(this).attr("id")+"_block"; // ID of clicked block/pane
		var all_panes 	= document.getElementsByName("pane_rank"); // All panes in an array
		var checkOnly 	= checkPanes(pane_id, all_panes); // Check for any panes currently visible outside the one clicked
		var blockHeight = getHighestBlock(all_panes);

		if ( ($("#"+pane_id).css("display") == "block" && checkOnly == 1) || ($("#"+pane_id).css("display") == "none" && checkOnly == 0) ) {			
			$("#"+pane_id).slideToggle();
			$(this).parent().css("height", block_rank_height);
			
			if ( parseInt($(this).parent().parent().css("height"), 10) <= blockHeight ) { 
				$(this).parent().parent().css("height", (parseInt($(this).parent().parent().css("height"), 10)+blockHeight-125)); 
			} 

			//if ( parseInt($(this).parent().parent().css("height"), 10) > parseInt($(this).parent().css("height"), 10) ) { 
			//	$(this).parent().parent().css("height", (parseInt($(this).parent().parent().css("height"), 10)-blockHeight+125)); 
			//}
		}

		for ( count = 0; count < all_panes.length; count++ ) {
			var pane_id2 = all_panes[count].id;
			
			if ( $("#"+pane_id2).css("display") == "block" && pane_id != pane_id2 ) { 
				$("#"+pane_id2).slideToggle(); 
			}		
		}
  	})

	$(".test_click").click(function() {
		if ( $("#div_media").length ) { $("#div_media").remove(); }

		var value 	= $(this).attr("value");
		var address = $('#host').text();
		$(this).append("<div id='div_media'></div>");
		jQuery('#div_media').load(address+'/encounter_media.php?value='+value);
		$("#div_media").fadeToggle();
	})

	$(".hover_menu").live("mouseover", function() {
		//alert("off1");
	})
});

function checkPanes(pane_id, all_panes) { // Returns 0 for no visible panes or > 0 for other visible panes
	var valid = 0;

	for ( count = 0; count < all_panes.length; count++ ) {
		var pane_id2 = all_panes[count].id;

		if ( $("#"+pane_id2).css("display") == "block" && pane_id != pane_id ) {
			valid++;
		}
	}

	return valid;
}

function getHighestBlock(all_panes) {
	var highest = 0;

	for ( count = 0; count < all_panes.length; count++ ) {
		var pane_id2 = all_panes[count].id;

		if ( parseInt($("#"+pane_id2).css("height"), 10) >= highest ) {
			highest = parseInt($("#"+pane_id2).css("height"), 10);
		}
	}

	return highest;	
}