jQuery(function(){
	jQuery('.activator.tip-specific-class').BAToolTip({
    	tipOpacity: 1.0,
        tipOffset: -50
    });
})

$(function() {
	$("div.banner_region_server").click(function() {
		var row_id = $(this).attr('id');
		$("#pane_" + row_id).slideToggle(500, function() {} );
  	})
});

$(function() {
	$(".standing_region div.standing_region").click(function() {
		var row_id = $(this).attr('id');
		$("#test_" + row_id).slideToggle(500, function() {} );
  	})
});

function pageLoad() {}