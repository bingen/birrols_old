var abv_min = 2;
var abv_max = 15;
var abv_inf = 4;
var abv_sup = 8;

var ibu_min = 20;
var ibu_max = 120;
var ibu_inf = 40;
var ibu_sup = 70;

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

	// search
	if( document.getElementById('search-input').value != '' ) {
	  url = url + "&search=" + document.getElementById('search-input').value;
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
  $("#search-input").keyup(function(event){
    if(event.keyCode == 13){
        $("#search-word-button").click();
    }
  });
});

$(function() {
  function slide_set_text( field, val_inf, val_sup, val_min, val_max, unit ) {
    if( val_inf == val_min && val_sup == val_max )
      $( "#"+field ).val( " - " );
    else if( val_inf == val_min )
      $( "#"+field ).val( "< " + val_sup + unit );
    else if( val_sup == val_max )
      $( "#"+field ).val( val_inf + unit + " <"  );
    else
      $( "#"+field ).val( val_inf + unit + " - " + val_sup + unit );
  } // slide_set_text
  
  function slider( field, val_inf, val_sup, val_min, val_max, unit ) {
    $( "#slider-"+field ).slider({
        range: true,
        min: val_min,
        max: val_max,
        values: [ val_inf, val_sup ],
        slide: function( event, ui ) {
	    slide_set_text( field, ui.values[0], ui.values[1], val_min, val_max, unit );
	    $( "#"+field+"-min" ).val( ui.values[ 0 ]);
	    $( "#"+field+"-max" ).val( ui.values[ 1 ]);
        },
        stop: function( event, ui ) {
	     reload_div(get_reload_url(), 'results');
        }
    });
    slide_set_text( field, $("#slider-"+field).slider( "values", 0 ), $("#slider-"+field).slider( "values", 1 ), val_min, val_max, unit );
  } // slider
  
  slider( "abv", abv_inf, abv_sup, abv_min, abv_max, "%" );
  slider( "ibu", ibu_inf, ibu_sup, ibu_min, ibu_max, "" );
  
//     $( "#slider-ibu" ).slider({
//         range: true,
//         min: ibu_min,
//         max: ibu_max,
//         values: [ 40, 70 ],
//         slide: function( event, ui ) {
// //              $( "#ibu" ).val( ui.values[ 0 ] + "% - " + ui.values[ 1 ] + "%" );
// 	    slide_set_text( "ibu", ui.values[0], ui.values[1]);
// 	     $( "#ibu-min" ).val( ui.values[ 0 ]);
// 	     $( "#ibu-max" ).val( ui.values[ 1 ]);
//         },
//         stop: function( event, ui ) {
// 	     reload_div(get_reload_url(), 'results');
//         }
//     });
//     slide_set_text( "ibu", $("#slider-ibu").slider( "values", 0 ), $("#slider-ibu").slider( "values", 1 ) );
});