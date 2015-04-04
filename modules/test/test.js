function pageLoad() {
	var name_tiers 				= document.getElementsByName('tiers');

	var name_tiers_bar 			= document.getElementsByName('tier_bar_values');
	var tier_bar_array 			= [];
	
	var name_tiers_title 		= document.getElementsByName('tier_bar_title_values');
	var tier_bar_title_array 	= [];

	var name_tiers_test_bar 	= document.getElementsByName('tier_test_bar_values');
	var tier_test_bar_array 	= [];
	
	var name_dungeons_bar		= document.getElementsByName('dungeon_bar_values');
	var dungeon_bar_array 		= [];

	var name_dungeons_title		= document.getElementsByName('dungeon_bar_title_values');
	var dungeon_bar_title_array = [];

	var name_dungeons_test_bar 	= document.getElementsByName('dungeon_test_bar_values');
	var dungeon_test_bar_array 	= [];
	
	for ( var tier = 0; tier < name_tiers.length; tier++ ) {
		var value_tier 		= document.getElementById(name_tiers[tier].id).innerHTML;
		var name_dungeons 	= document.getElementsByName('dungeons_'+value_tier);

		for ( var dungeon = 0; dungeon < name_dungeons.length; dungeon++ ) {
			var value_dungeon = document.getElementById(name_dungeons[dungeon].id).innerHTML;

			var name_encounters 		= document.getElementsByName('encounters_'+value_dungeon);
			var name_perc_encounters 	= document.getElementsByName('encounters_percent_'+value_dungeon);
			var name_bar_encounters 	= document.getElementsByName('encounters_bar_'+value_dungeon);
			var percArray				= [];

			for ( var encounter = 0; encounter < name_encounters.length; encounter++ ) {
				var value_encounter 	= document.getElementById(name_encounters[encounter].id).innerHTML;
				var percent_encounter 	= document.getElementById(name_perc_encounters[encounter].id).innerHTML;
				
				var temp 				= [];
				temp[0] 				= value_encounter;
				temp[1] 				= parseFloat(percent_encounter);
				percArray[encounter] 	= temp;
				percent_encounter 		= document.getElementById(name_perc_encounters[encounter].id).innerHTML+"%";

				$("#"+name_bar_encounters[encounter].id).animate({
		        	width: percent_encounter
		    	}, 400 );
			}
			
			var id = 'pie_dungeon_'+value_dungeon;
			draw_pie_graph(id, percArray);
		}

		var name_dungeons2 		= document.getElementsByName('dungeons_name_'+value_tier);
		var name_perc_dungeons 	= document.getElementsByName('dungeons_percent_'+value_tier);
		var percArray2			= [];

		for ( var dungeon = 0; dungeon < name_dungeons2.length; dungeon++ ) {
			var value_dungeon 	= document.getElementById(name_dungeons2[dungeon].id).innerHTML;
			var percent_dungeon = document.getElementById(name_perc_dungeons[dungeon].id).innerHTML;
			
			var temp 			= [];
			temp[0] 			= value_dungeon;
			temp[1] 			= parseFloat(percent_dungeon);
			percArray2[dungeon] = temp;
		}
		
		var id = 'pie_tier_'+value_tier;
		draw_pie_graph(id, percArray2);
	}
	
	for ( var tier = 0; tier < name_tiers_title.length; tier++ ) {
		var title_tier = document.getElementById(name_tiers_title[tier].id).innerHTML;
		var value_tier = parseFloat(document.getElementById(name_tiers_bar[tier].id).innerHTML);
		var value_test_tier = parseFloat(document.getElementById(name_tiers_test_bar[tier].id).innerHTML);

		tier_bar_title_array[tier] 	= title_tier;
		tier_bar_array[tier] 		= value_tier;
		tier_test_bar_array[tier] 	= value_test_tier;
	}
	
	var id = "tier_bar";
	draw_bar_graph(id, "Test", tier_bar_title_array, tier_bar_array, tier_test_bar_array);
	
	for ( var dungeon = 0; dungeon < name_dungeons_title.length; dungeon++ ) {
		var title_dungeon = document.getElementById(name_dungeons_title[dungeon].id).innerHTML;
		var value_dungeon = parseFloat(document.getElementById(name_dungeons_bar[dungeon].id).innerHTML);
		var value_test_dungeon = parseFloat(document.getElementById(name_dungeons_test_bar[dungeon].id).innerHTML);

		dungeon_bar_title_array[dungeon] 	= title_dungeon;
		dungeon_bar_array[dungeon] 			= value_dungeon;
		dungeon_test_bar_array[dungeon] 	= value_test_dungeon;
	}

	var id = "dungeon_bar";
	draw_bar_graph(id, "Test", dungeon_bar_title_array, dungeon_bar_array, dungeon_test_bar_array);
}


function draw_pie_graph(id, data) {
	$(function () {
		var chartPie = new Highcharts.Chart({
			chart: {
				renderTo: id,
				type: 'pie',
				backgroundColor: '',
				plotBackgroundColor: null,
				plotBorderWidth: null,
				plotShadow: false,
				margin: [0, 0, 0, 0],
				height: 200
			},
			title: {
				text: ''
			},
			legend: {
				enabled: false,
				layout: 'horizontal',
				backgroundColor: '#FFFFFF',
				align: 'right',
				verticalAlign: 'top',
				floating: false,
				x: 0,
				y: 100
			},
			credits: {
				enabled: false
			},
			tooltip: {
				formatter: function() {
					return '<b>'+this.point.name+'</b>::: ' + this.percentage.toFixed(2) +'%';
				}
			},
			plotOptions: {
				pie: {
					allowPointSelect: true,
					size: '55%',
					cursor: 'pointer',
					dataLabels: {
						enabled: true,
						distance: 20,
						color: '#FFFFFF',
						connectorColor: '#FFFFFF',
						formatter: function() {
							return '<center><b>'+ this.point.name +'</b></center><br><center>'+ this.percentage.toFixed(2) +'%</center>';
						}
					},
					showInLegend: true
				}
			},
			series: [{
				name: name,
				data: data
			}]
		});
	});
}

function draw_bar_graph(id, style, data_bar_title_array, data_bar_array, data_test_bar_array) {
	$(function () {
        var chartBar = new Highcharts.Chart({
            chart: {
            	renderTo: id,
				shadow: 				true,
				plotBackgroundColor: 	null,
				plotBorderWidth: 		null,
				plotShadow: 			false,
				backgroundColor:		'#000000',
				type: 					'bar'
            },
            title: {
                text: 'Test Title'
            },
            subtitle: {
                text: 'Test Subtitle'
            },
            xAxis: {
                categories: data_bar_title_array,
                title: {
                    text: null
                }
            },
            yAxis: {
                min: 0,
                title: {
                    text: 'Implied Average Number of Days',
                    align: 'high'
                },
                labels: {
                    overflow: 'justify'
                }
            },
            tooltip: {
                valueSuffix: ' days'
            },
            plotOptions: {
                bar: {
                    dataLabels: {
                        enabled: true
                    }
                }
            },
            legend: {
                layout: 'vertical',
                align: 'right',
                verticalAlign: 'top',
                x: -40,
                y: 100,
                floating: true,
                borderWidth: 1,
                backgroundColor: '#FFFFFF',
                shadow: true
            },
            credits: {
                enabled: false
            },
            series: [{
				id: style,
				name: style,
				data: data_bar_array
			}, {
				id: style,
				name: style,
				data: data_test_bar_array				
            }]
        });
    });
}