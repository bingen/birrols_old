function get_reload_url()  {
	url = table_url + '?';

	// category
	if( document.getElementById('ale-check').checked || document.getElementById('lager-check').checked || document.getElementById('lambic-check').checked ) {
	    url = url + '&ale=' + document.getElementById('ale-check').checked + '&lager=' + document.getElementById('lager-check').checked + '&lambic=' + document.getElementById('lambic-check').checked;
	 }
	// type
	if( document.getElementById('type_id').value != '' ) {
	  url = url + "&type_id=" + document.getElementById('type_id').value;
	}
	// country
	if( document.getElementById('country_id').value != '' ) {
	  url = url + "&country_id=" + document.getElementById('country_id').value;
	}

	return url;
} // get_reload_url

$('country_id').on({
  select: function () {
    reload_div(get_reload_url(), 'results');
  }
});

jQuery(function(){
  jQuery('select.turn-to-ac').selectToAutocomplete();
})
