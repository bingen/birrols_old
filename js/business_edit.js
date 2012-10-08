
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
  $('#url').focus(function () {
    if( $('#url').val() == '' )
      $('#url').val('http://');
  });
  $('#url').blur(function () {
    if( $('#url').val() == 'http://' )
      $('#url').val('');
  });
  toggle_taps();
});

