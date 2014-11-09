$(document).ready(function() {
  $('.btn-group').button();
  $('[data-toggle="tooltip"]').tooltip();

  $('.fileinput').click(function() {
    var realinputid = '#' + $(this).attr('id').replace(/_chooser.*/, '');
    $(realinputid).click();
  });

  $('input[type="file"]').change(function() {
    var choosertxtid = '#' + $(this).attr('id') + '_choosertxt';
    $(choosertxtid).val($(this).val());
  });
});
