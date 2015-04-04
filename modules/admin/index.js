function pageLoad() {
}

function loadObject(objectId, objectPage) {
	var xmlHttp = getXMLHttp();

	xmlHttp.onreadystatechange = function() {
		if ( xmlHttp.readyState == 4 ) {
			pageObject( xmlHttp.responseText, objectId );
		}
	}

	var url = objectPage;
	xmlHttp.open("GET", url, true);
	xmlHttp.send(null);
}

function pageObject(response, objectId) {
	document.getElementById(objectId).innerHTML = response;
}

function getXMLHttp() {
	var xmlHttp

	try {
		xmlHttp = new XMLHttpRequest();
	} catch(e) {
		try {
			xmlHttp = new ActiveXObject("Msxml2.XMLHTTP");
		} catch(e) {
			try {
				xmlHttp = new ActiveXObject("Microsoft.XMLHTTP");
			} catch(e) {
				alert("Your browser does not support AJAX!")
				return false;
			}
		}
	}

	return xmlHttp;
}

function update_encounter_list(dropdown) {
	var guild_id = dropdown.options[dropdown.selectedIndex].value;
	var url = "modules/admin/get_encounter.php?gid=" + guild_id + "&type=0";
	loadObject("encounter_selectarea", url);

	document.getElementById("encounter_detail").innerHTML = "";
}

function update_encounter_details(dropdown) {
	var guild_id_dropbox 	= document.getElementById("guild_select");
	var guild_id 			= guild_id_dropbox.options[guild_id_dropbox.selectedIndex].value;
	var encounter_id 		= dropdown.options[dropdown.selectedIndex].value;

	var url = "modules/admin/get_encounter.php?gid=" + guild_id + "&eid=" + encounter_id + "&type=1";
	loadObject("encounter_detail", url);
}