function get_reload_url()  {
	url = table_url + "?search_type=" + document.getElementById('search_type').value;

	// type
	if( document.getElementById('brewery-check').checked || document.getElementById('pub-check').checked || document.getElementById('store-check').checked ) {
	    url = url + '&brewery=' + document.getElementById('brewery-check').checked + '&pub=' + document.getElementById('pub-check').checked + '&store=' + document.getElementById('store-check').checked;
	}
	// country
	if( document.getElementById('country_id').value != '' ) {
	  url = url + "&country_id=" + document.getElementById('country_id').value;
	}
	// facilities
	if( document.getElementById('food-check').checked || document.getElementById('wifi-check').checked || document.getElementById('homebrew-check').checked ) {
	    url = url + '&food=' + document.getElementById('food-check').checked + '&wifi=' + document.getElementById('wifi-check').checked + '&homebrew=' + document.getElementById('homebrew-check').checked;
	}

	// search
	if( document.getElementById('search-input').value != '' ) {
	  url = url + "&search=" + document.getElementById('search-input').value;
	}
	
	return url;
} // get_reload_url

$('#search-button-map').on({
  click: function () {
    document.getElementById("search_type").value="map";
    $('#$div').load(get_reload_url(), function() {
	var map = L.map('map').setView([51.505, -0.09], 13);
	L.tileLayer('http://{s}.tile.cloudmade.com/BC9A493B41014CAABB98F0471D759707/997/256/{z}/{x}/{y}.png', {
	  maxZoom: 18,
	  attribution: 'Map data &copy; <a href=\"http://openstreetmap.org\">OpenStreetMap</a> contributors, <a href=\"http://creativecommons.org/licenses/by-sa/2.0/\">CC-BY-SA</a>, Imagery © <a href=\"http://cloudmade.com\">CloudMade</a>'
	}).addTo(map);

	L.marker([51.5, -0.09]).addTo(map).bindPopup("<b>Flint!</b><br />Aquí hay bírrols!!.").openPopup();

	var popup = L.popup();

	function onMapClick(e) {
	  popup
		.setLatLng(e.latlng)
		.setContent("You clicked the map at " + e.latlng.toString())
		.openOn(map);
	}

	map.on('click', onMapClick);
    });
  }
});

$('country_id').on({
  select: function () {
    reload_div(get_reload_url(), 'results');
  }
});

jQuery(function(){
  jQuery('select.turn-to-ac').selectToAutocomplete();
});
