$(document).ready(function()
{
  // autocomplete brewery
  jQuery(function(){
    $('.brewery').autocomplete({
      source: lib_url + 'search_brewery.php?birrolpath=' + birrolpath,
      select: function(event, ui) {
	    $(this).val(ui.item.label);
	    $('#brewery_id_'+$(this).data('tap')).val(ui.item.id);
	    $('.beer').autocomplete({
	      source: lib_url + 'search_beer.php?birrolpath=' + birrolpath + '&brewery_id=' + $('#beer_id_'+$(this).data('tap')).val(),
	      select: function(event2, ui2) {
		$(this).val(ui2.item.label);
		$('#beer_id_'+$(this).data('tap')).val(ui2.item.id);
	      }, // select function
	      change:function(event2, ui2) {
		  if( !ui2.item ) {
		    $('#brewery_id_'+$(this).data('tap')).val('');
		    alert(err_beer_miss);
		  } //fi
	      } // change function
	    }); // beer autocomplete
      }, // select function
      change: function(event, ui) {
	if( !ui.item ) {
	  $('#brewery_id_'+$(this).data('tap')).val('');
	  alert(err_brewery_miss);
	} // fi
      } // change function
    }); // brewery autocomplete
  });

}); //$(document).ready(function()
