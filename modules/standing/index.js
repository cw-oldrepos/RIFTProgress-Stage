function pageLoad() {
    /*var bar_encounters = document.getElementsByName('encounter_bars');
    var val_encounters = document.getElementsByName('encounter_values');

    if ( bar_encounters != undefined ) {
        for ( var count = 0; count < bar_encounters.length; count++ ) {
            var bar_value = document.getElementById(bar_encounters[count].id).innerHTML;
            var val_value = document.getElementById(val_encounters[count].id).innerHTML;

            $("#"+bar_encounters[count].id).animate({
                width: val_value
            }, 400 );
        }
    }*/
}


jQuery(function(){
	jQuery('.activator.tip-specific-class').BAToolTip({
    	tipOpacity: 1.0,
        tipOffset: -50
    });
})


$(function() {
	$("div.standing_shard").click(function() {
		var row_id = $(this).attr('id');
        var item_width = $(this).css('width');
        $("#pane_" + row_id).css('width', item_width); // Add Smoothness
		$("#pane_" + row_id).slideToggle(500, function() {} );
  	})
});