function get_reload_url()  {
	url = table_url + '?search_type=' + document.getElementById('search_type').value;

	if( document.getElementById('brewery-check').checked || document.getElementById('pub-check').checked || document.getElementById('store-check').checked ) {
	    url = url + '&brewery=' + document.getElementById('brewery-check').checked + '&pub=' + document.getElementById('pub-check').checked + '&store=' + document.getElementById('store-check').checked;
	 }

	return url;
} // get_reload_url
