function enablebutton (button, button2, target)
{
	var string = target.value;
	if (button2 != null) {
		button2.disabled = false;
	}
	if (string.length > 0) {
		button.disabled = false;
	} else {
		button.disabled = true;
	}
}

function checkEqualFields(field, against) {
	if(field.value == against.value) {
		field.style.backgroundColor = '#8FFF00';
	} else {
		field.style.backgroundColor = "#F56874";
	}
	return false;
}

function securePasswordCheck(field) {
	/*La función comprueba si la clave contiene al menos
	 *ocho caracteres e incluye mayúsculas, minúsculas y números.
	 *
	 * Function checks if the password provided contains at least
	 * eight chars, including upper, lower and numbers.
	 *
	 * jotape - jplopez.net */

	if (field.value.length > 5 && field.value.match("^(?=.{6,})(?=(.*[a-z].*))(?=(.*[A-Z0-9].*)).*$", "g")) {
		if (field.value.match("^(?=.{8,})(?=(.*[a-z].*))(?=(.*[A-Z].*))(?=(.*[0-9].*)).*$", "g")) {
			field.style.backgroundColor = "#8FFF00";
		} else {
			field.style.backgroundColor = "#F2ED54";
		}
	} else {
		field.style.backgroundColor = "#F56874";
	}
	return false;
}

function checkfield (type, form, field)
{
	var url = 'lib/checkfield.php?type='+type+'&name=' + encodeURIComponent(field.value);
	$.get(url,
		 function(html) {
			if (html == 'OK') {
				/*$('#'+type+'checkitvalue').html('<span style="color:black">"' + encodeURI(field.value) + '": ' + html + '</span>');*/
				$('#'+type+'checkitvalue').html('<span style="color:black">"' + field.value + '": ' + html + '</span>');
				form.submit.disabled = '';
			} else {
				/*$('#'+type+'checkitvalue').html('<span style="color:red">"' + encodeURI(field.value) + '": ' + html + '</span>');*/
				$('#'+type+'checkitvalue').html('<span style="color:red">"' + field.value + '": ' + html + '</span>');
				form.submit.disabled = 'disabled';
			}
		}
	);
	return false;
}

function check_checkfield(fieldname, mess) {
	field = document.getElementById(fieldname);
	if (field && !field.checked) {
		alert(mess);
		// box is not checked
		return false;
	}
}

function horaCheck(field) {	// permite horas mayores que 24 ! TODO 

	if (field.value.match(/^[0-2]?[0-9]:[0-5][0-9]$/)) {
		field.style.backgroundColor = "#8FFF00";
	} else {
		field.style.backgroundColor = "#F56874";
	}
//	return false;
}

function get_stars( score ) {

	if( score < 1 )
		stars = 0;
	else if( score < 2 )
		stars = 1;
	else if( score < 3 )
		stars = 2;
	else if( score < 4 )
		stars = 3;
	else if( score < 5 )
		stars = 4;
	else
		stars = 5;

	file = "img/star_" + stars + ".png";
//	file_path = birrolpath + '/' + file;
//	if (is_readable($file_path))
//		return $globals['base_static'] . $file;
	// TODO !!
	return "http://localhost/opencratbeer/" + file;

}

$(document).ready(function()
{
	$(".usuario").autocomplete('lib/search_user.php',
	{
	matchContains: true,
 	mustMatch: true,
	max: 30,
	cacheLength: 1,
	extraParams: {
		deporte_id: function() { return $("#deporte_id").val(); },
		jugador_id: function() { return $("#current_user_id").val(); },
		jugador_login: function() { return $("#current_user_login").val(); },
		tipo: function() { return $("#tipo_search_user").val(); }
		},
	formatItem: function(row) {
		if( document.getElementById("num_jugador") )
			var num_jugador = document.getElementById("num_jugador").value;
		if( num_jugador > 0 )
			return row[0] + ", " + row[2] + ", " + row[1];
		else
			return row[0];
		},
	formatResult: function(row) {
			return row[0];
		}
	}).result(function(event, data, formatted) {
		if( document.getElementById("num_jugador") )
			var num_jugador = document.getElementById("num_jugador").value;
		if( num_jugador > 0 ) {
			$("#categoria"+num_jugador).val(data[2]);
			$("#categoria"+num_jugador+"_hdn").val(data[2]);
			$("#categoria"+num_jugador).attr("disabled", "disabled");
			document.getElementById("op_cat_"+num_jugador).style.display = "none";
			document.getElementById("puntos"+num_jugador+"_d").value = data[1];
			document.getElementById("puntos"+num_jugador+"_d").style.display = "inline";
			$("#stars_"+num_jugador).attr("src", get_stars( data[1] ));
			document.getElementById("stars_"+num_jugador).style.display = "inline";
			// TODO: avatar
			$("#avatar_link_"+num_jugador).attr("href", "http://todo");
			//$("#avatar_img_"+num_jugador).attr("src", get_stars( data[1] ));
			$("#avatar_img_"+num_jugador).attr("alt", get_stars( data[0] ));
			document.getElementById("avatar_link_"+num_jugador).style.display = "inline";
		}
	}).blur(function() {
		$(this).search(function (result){
			// if no value found, ...
			if( !result ) {
				$(this).val("");
			}
		})
	});

	$(".club").autocomplete('lib/search_club.php',
	{
	matchContains: true, 
	max: 30,
	selectFirst: false,
	extraParams: {
		deporte_id: function() { return $("#deporte_id").val(); },
//		pais: function() { return $("#pais_hdn").val(); },
		provincia: function() { return $("#provincia").val(); }
		},
	formatItem: function(row) {
			return row[0] + ", " + row[1];
		},
	formatResult: function(row) {
			return row[0];
		}
	}).result(function(event, data, formatted) {
		if( data ) {
//			alert( 'data'+data+' event:'+event );
			$("#poblacion").val(data[1]);
			$("#poblacion_hdn").val(data[1]);
			$("#poblacion").attr("disabled", "disabled");
			$("#direccion").val(data[2]);
			$("#direccion_hdn").val(data[2]);
			$("#direccion").attr("disabled", "disabled");
			$("#lat_hdn").val(data[3]);
			$("#lon_hdn").val(data[4]);
			$("#club_id_hdn").val(data[5]);
			$("#stars_club").attr("src", get_stars( data[6] ));
			document.getElementById("stars_club").style.display = "inline";
			// TODO: avatar
		} /*else { 
			alert( 'No data'+data+' event:'+event );
			$("#poblacion").removeAttr("disabled");
			$("#poblacion").val("");
			$("#poblacion_hdn").val("");
			$("#direccion").removeAttr("disabled");
			$("#direccion").val("");
			$("#direccion_hdn").val("");
			$("#lat_hdn").val("");
			$("#lon_hdn").val("");
			$("#club_id_hdn").val("");
		}*/
	}).blur(function() {
		$(this).search(function (result){
			// if no value found, ...
			if( !result ) {
				$("#poblacion").removeAttr("disabled");
				$("#poblacion").val("");
				$("#poblacion_hdn").val("");
				$("#direccion").removeAttr("disabled");
				$("#direccion").val("");
				$("#direccion_hdn").val("");
				$("#lat_hdn").val("");
				$("#lon_hdn").val("");
				$("#club_id_hdn").val("");
				document.getElementById("stars_club").style.display = "none";
				// TODO: avatar
			}
		})
	});

});

////// MAPAS ////////////
function cargarMapa( club, direccion, poblacion, provincia, pais, not_found ) {
//	var mapa = new GMap2(document.getElementById("map"));
	var myOptions = {
		zoom: 13,
		mapTypeId: google.maps.MapTypeId.ROADMAP
	}
	var mapa = new google.maps.Map(document.getElementById("map"), myOptions );
	var direccion = club + "+" + direccion + "+" + poblacion + "+" + provincia + "+" + pais;
	geocoder = new google.maps.Geocoder();

//	codeAdress( mapa, direccion );
	geocoder.geocode( { 'address': direccion }, function(results, status) {
		if (status == google.maps.GeocoderStatus.OK) {
			mapa.setCenter(results[0].geometry.location);
			for (var i = 0; i < results.length; i++) {
				var marker = new google.maps.Marker({
					map: mapa, 
					position: results[i].geometry.location
				});
  			}
		} else {
			//alert("Geocode was not successful for the following reason: " + status);
			$("#map").append("<p>"+ not_found +"</p>");
		}
	});
/*	var url = "http://maps.google.com/maps/api/geocode/json?address="+ direccion + "+" + poblacion + "+" + provincia +"&sensor=false";
	var json = $.get(url); 
	for (i = 0; i < json.results.length; i++) {
		lat[i] = json.results[i].geometry.location.lat;
		lon[i] = json.results[i].geometry.location.lng;
	}
	map.setCenter(new GLatLng(lat[0], lng[0]), 13);
	map.setUIToDefault();
	for (var i = 0; i < json.results.length; i++) {
		var point = new GLatLng( lat[i], lon[i] );
		map.addOverlay(new GMarker(point));
  	}
*/
} // cargarMapa
////// MAPAS ////////////

function invitar_meil( usuario, email ) {

	var url = 'lib/invitar_meils.php?usuario='+usuario+'&email=' + email + '&meils=' + encodeURIComponent(document.getElementById("inv_meils").value);
	$.get(url,
		 function(html) {
			if (html == 'OK') {
				/*$('#'+type+'checkitvalue').html('<span style="color:black">"' + encodeURI(field.value) + '": ' + html + '</span>');*/
				$('#inv_checkitvalue').html('<span style="color:black">' + html + '</span>');
				document.getElementById("inv_meils").value = "";
			} else {
				/*$('#'+type+'checkitvalue').html('<span style="color:red">"' + encodeURI(field.value) + '": ' + html + '</span>');*/
				$('#inv_checkitvalue').html('<span style="color:red">' + html + '</span>');
			}
		}
	);
}

function select_all( tabla ) {
	var filas = document.getElementById(tabla).rows.length;
	for( i=2; i<filas; i++ )
		document.getElementById(tabla).rows[i].cells[0].childNodes[0].checked=document.getElementById("chk-head").checked;
}

