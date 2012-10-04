$(document).ready(function()
{
  function enable_disable_submit() {
    if( $('#brewery_id').val() != '' && $('#name').val() != '' ) {
      $('#submit').removeAttr("disabled");
    } else {
      $('#submit').attr("disabled", "disabled");
    }
  } // enable_disable_submit
  enable_disable_submit();
  $('#name').change(enable_disable_submit);
  // autocomplete brewery
  jQuery(function(){
    jQuery('#brewery').autocomplete({
      source: lib_url + 'search_brewery.php?birrolpath=' + birrolpath,
      select: function(event, ui) {
	    $(this).val(ui.item.label);
	    $('#brewery_id').val(ui.item.id);
	    if( $('#name').val() != '' )
	      $('#submit').removeAttr("disabled");
      },
      change: function(event, ui) {
	if( !ui.item ) {
	  $('#brewery_id').val('');
	  alert(err_brewery_miss);
	  $('#submit').attr("disabled", "disabled");
	}
      }
    });
  });
  // autocomplete type
  jQuery(function(){
    jQuery('#type').autocomplete({
      source: lib_url + 'search_type.php?birrolpath='+ birrolpath +'&category_id=' + document.getElementById('category_id').value/*,
      select: function(event, ui) {
		  $(this).val(ui.item.label);
		  $('#type_id').val(ui.item.id);
	}*/
    });
    $('#category_id').change(function(){
      document.getElementById('type').value = '';
      document.getElementById('type_id').value = '';
      $('#type').autocomplete( 'option', 'source', lib_url + 'search_type.php?birrolpath='+ birrolpath +'&category_id=' + $('#category_id option:selected').attr('value') );
    });
  });

}); //$(document).ready(function()
