function Ajax() {
	var xmlhttp=false;
	try{
		xmlhttp = new ActiveXObject("Msxml2.XMLHTTP");
	} catch(e) {
		try {
			xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
		} catch(E) {
			xmlhttp = false;
		}
	}
	if (!xmlhttp && typeof XMLHttpRequest!='undefined') {
		xmlhttp = new XMLHttpRequest();
	}
	return xmlhttp;
}

function reload_div(script, div, givefocus){
	//especificamos el div donde se mostrará el resultado
	divListado = document.getElementById(div);

// 	alert( script+div+givefocus );
	ajax=Ajax();
	//especificamos el archivo que realizará el listado
	ajax.open("GET", script);
	ajax.onreadystatechange=function() {
		if (ajax.readyState==4) {
			divListado.innerHTML = ajax.responseText;
			if( givefocus != null && givefocus != "") {
				document.getElementById(givefocus).focus();
			}
		}
	}
	ajax.send(null)
}
