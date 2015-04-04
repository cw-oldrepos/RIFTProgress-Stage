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

function init_sig(guild_name, guild_server, guild_id, rank, loc, dungeon, tier, points, trend, output, display, raid_size, system, host_name) {
	var textarea_output = document.getElementById('textarea_output');
	//var url = host_name+'/guild/sig/'+guild_id+'g'+rank+'r'+dungeon+'d'+tier+'t'+points+'p'+trend+'t'+display+'dr'+raid_size+'s'+system+'y';
	var url = host_name+'/guild/sig/'+guild_id+'g'+rank+'r'+dungeon+'d'+'0t'+points+'p'+trend+'t'+display+'dr'+'0s'+system+'y';
	
	document.getElementById(loc).innerHTML='<iframe src='+url+' height=50 width=610 scrolling=no style=\"border:0px solid #344E5B; overflow:hidden; border-radius:0px;" border=\"0\"></iframe>';
	
	if ( output == "0" ) {
		textarea_output.value = url;
	} else if ( output == "1" ) {
		textarea_output.value = '<a href=\"'+host_name+'/guild/'+guild_name+'-_-'+guild_server+'\" target=\"_blank\"><img src=\"'+url+'\"></a>';
	} else if ( output == "2" ) {
		textarea_output.value = '[url='+host_name+'/guild/'+guild_name+'-_-'+guild_server+'][img]'+url+'[/img][/url]';
	}
}

function update_widget(guild_name, guild_server, guild_id, host_name) {
	//var option_system 	= document.getElementById('system').value;
	//var option_tier 	= document.getElementById('tier').value;
	var option_type 	= document.getElementById('type').value;
	//var option_size 	= document.getElementById('size').value;
	var option_dungeon 	= document.getElementById('dungeon').value;
	//var option_display 	= document.getElementById('rank');
	//var option_points 	= document.getElementById('points');
	//var option_trend 	= document.getElementById('trend');
	var option_output	= document.getElementsByName('output');
	var output_value  	= 0;
	
	for ( var count = 0; count < option_output.length; count++ ) {
        var button = option_output[count];
        if (button.checked) {
            output_value = option_output[count].value;
        }
	}
	
	/*
	if (option_trend.checked) {
		option_trend = '1';
	} else {
		option_trend = '0';
	}

	if (option_points.checked) {
		option_points = '1';
	} else {
		option_points = '0';
	}

	if (option_display.checked) {
		option_display = '1';
	} else {
		option_display = '0';
	}
	*/

	//init_sig(guild_name, guild_server, guild_id, option_type, 'widget_display', option_dungeon, option_tier, option_points, option_trend, output_value, option_display, option_size, option_system, host_name);
	init_sig(guild_name, guild_server, guild_id, option_type, 'widget_display', option_dungeon, 0, 0, 0, output_value, 0, 0, 0, host_name);
}

function update_output(guild_name, guild_server, guild_id, type, host_name) {
	var textarea_output = document.getElementById('textarea_output');
	//var option_system 	= document.getElementById('system').value;
	//var option_tier 	= document.getElementById('tier').value;
	var option_type 	= document.getElementById('type').value;
	//var option_size 	= document.getElementById('size').value;
	var option_dungeon 	= document.getElementById('dungeon').value;
	//var option_display 	= document.getElementById('rank');
	//var option_points 	= document.getElementById('points');
	//var option_trend 	= document.getElementById('trend');

	/*
	if (option_trend.checked) {
		option_trend = '1';
	} else {
		option_trend = '0';
	}

	if (option_points.checked) {
		option_points = '1';
	} else {
		option_points = '0';
	}
	
	if (option_display.checked) {
		option_display = '1';
	} else {
		option_display = '0';
	}
	*/

	//var url = host_name+'/guild/sig/'+guild_id+'g'+option_type+'r'+option_dungeon+'d'+option_tier+'t'+option_points+'p'+option_trend+'t'+option_display+'dr'+option_size+'s'+option_system+'y'; 
	var url = host_name+'/guild/sig/'+guild_id+'g'+option_type+'r'+option_dungeon+'d'+'0t'+'0p'+'0t'+'0dr'+'0s'+'0y';
	
	if ( type == "0" ) {
		textarea_output.value = url;
	} else if ( type == "1" ) {
		textarea_output.value = '<a href=\"'+host_name+'/guild/'+guild_name+'-_-'+guild_server+'\" target=\"_blank\"><img src=\"'+url+'\"></a>';
	} else if ( type == "2" ) {
		textarea_output.value = '[url='+host_name+'/guild/'+guild_name+'-_-'+guild_server+'][img]'+url+'[/img][/url]';
	}
}