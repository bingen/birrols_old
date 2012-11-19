var abv_min = 2;
var abv_max = 15;

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

	// abv
	if( document.getElementById('abv-min').value != '' && document.getElementById('abv-min').value > abv_min ) {
	  url = url + "&abv_min=" + document.getElementById('abv-min').value;
	}
	if( document.getElementById('abv-max').value != '' && document.getElementById('abv-max').value < abv_max ) {
	  url = url + "&abv_max=" + document.getElementById('abv-max').value;
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
});

$(function() {
    $( "#slider-abv" ).slider({
        range: true,
        min: abv_min,
        max: abv_max,
        values: [ 3, 8 ],
        slide: function( event, ui ) {
             $( "#abv" ).val( ui.values[ 0 ] + "% - " + ui.values[ 1 ] + "%" );
	     $( "#abv-min" ).val( ui.values[ 0 ]);
	     $( "#abv-max" ).val( ui.values[ 1 ]);
        },
        stop: function( event, ui ) {
	     reload_div(get_reload_url(), 'results');
        }
    });
    $( "#abv" ).val( $( "#slider-abv" ).slider( "values", 0 ) + "% - " + $( "#slider-abv" ).slider( "values", 1 ) + "%" );
});