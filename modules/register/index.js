function pageLoad() {}
    
/* Reserved for Faction Logo/Guild Logo Preview
function previewFaction(faction) {
	var module 		= "register/";
	var urlIndex 	= document.URL.indexOf(module);
	var imagePath 	= document.URL.substring(0, urlIndex)+"images/guild/logo/tmp/"+faction+"_default.png".toLowerCase();

	$('#preview_faction').attr('src', imagePath);
}

function readURL(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();

        reader.onload = function (e) {
            $('#preview_logo').attr('src', e.target.result);
        }

        reader.readAsDataURL(input.files[0]);
    }
}

$(function() {
	$("#input_logo").change(function(){
	    readURL(this);
	});

	$("#select_faction").change(function(){
	    previewFaction($(this).val())
	});
});
*/