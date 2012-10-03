
jQuery(function(){
  function toggle_taps() {
    if ($('#pub').is(':checked')) {
      $("#taps").show();
      $("label[for='taps']").show();
    } else {
      $("#taps").hide();
      $("label[for='taps']").hide();
    } 
  }
  jQuery('select.turn-to-ac').selectToAutocomplete();
  $('#pub').click( toggle_taps );
  toggle_taps();
});

