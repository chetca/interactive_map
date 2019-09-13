$(document).ready(function(){
	//$("#showFormAdd").width(parseInt($("#showFormAdd").parent().width())-35+'px');
	//$("#showFormCheck").width(parseInt($("#showFormCheck").parent().width())-35+'px');
	$("#lightBox").css('opacity', '0.46');
	var showForm = function(addr, nameDiv) {
		//alert(nameDiv);
		$("#lightBox").fadeIn(300, function() {
			$("#"+nameDiv).fadeIn(100);
		});
	}
	var hideForm = function(nameDiv) {
		$("#"+nameDiv).fadeOut(300, function() {
			$("#lightBox").fadeOut(100);
		});
	}
	
	if ($("font.errortext").text()){
		$("#lightBox").css("display", "block");
		$("#showFormAdd").css("display", "block");
		//$("#formAdd").addClass('active');
	}
    $("#formAdd").click(function(){
		showForm('', 'showFormAdd');
    });
    $("#formAbout").click(function(){
		showForm('', 'showFormAbout');
    });
    $("#formCheck").click(function(){
		showForm('', 'showFormCheck');
    });

    $(".closeBtn").click(function(){
		hideForm($(this).parent().attr('id'));
    });

	/*$("#geoSearchSubmit").submit(function(){
		var text = '';
		var street = $("#geoSearchStreet").val();
		if (street == '') {
			alert('Вы не написали улицу!');
		} else {
			text = $("#geoSearchStreet").val();
			if ($("#geoSearchHouse").val() != '')
				text += ', '+$("#geoSearchHouse").val();
		};
		searchText(text);
		return false;
	});*/
});