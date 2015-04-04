$(function() {
	$("div.standing_shard").click(function() {
		var row_id = $(this).attr('id');
        var item_width = $(this).css('width');
        $("#pane_" + row_id).css('width', item_width); // Add Smoothness
		$("#pane_" + row_id).slideToggle(500, function() {} );
  	})

	$('#update_guild_popup').click(
		function() {
			$('#popup_wrapper').fadeToggle(fadeDuration, function() {} );
			$('#update_guild_content').fadeToggle(fadeDuration, function() {} );
			$('#fade').fadeToggle(fadeDuration, function() {} );
			return false; 
		}
	);

	$('#update_guild_popup_close').click(
		function() {
			$('#popup_wrapper').fadeToggle(fadeDuration, function() {} );
			$('#update_guild_content').fadeToggle(fadeDuration, function() {} );
			$('#fade').fadeToggle(fadeDuration, function() {} );
			return false; 
		}
	);
});