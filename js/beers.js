function get_reload_url()  {
	url = table_url + '?';

	if( document.getElementById('ale-check').checked || document.getElementById('lager-check').checked || document.getElementById('lambic-check').checked ) {
	    url = url + '&ale=' + document.getElementById('ale-check').checked + '&lager=' + document.getElementById('lager-check').checked + '&lambic=' + document.getElementById('lambic-check').checked;
	 }

	return url;
} // get_reload_url
