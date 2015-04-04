function pageLoad() {}

$(function() {
	$(".tier").click(function() {
		var row_id = $(this).attr('id');
		$("#pane_" + row_id).slideToggle(500, function() {} );
  	})
});

$(function() {
	$(".dungeon").click(function() {
		var row_id = $(this).attr('id');
		$("#pane_" + row_id).slideToggle(500, function() {} );
  	})
});