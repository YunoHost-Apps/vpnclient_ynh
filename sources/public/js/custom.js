$(document).ready(function() {
  $('.btn-group').button();
  $('[data-toggle="tooltip"]').tooltip();

  $('.fileinput').click(function() {
    if(!$(this).hasClass('btn-danger')) {
      var realinputid = '#' + $(this).attr('id').replace(/_chooser.*/, '');

      $(realinputid).click();
    }
  });

  $('.deletefile').click(function() {
    var chooserbtnid = '#' + $(this).attr('id').replace(/_deletebtn$/, '_chooserbtn');
    var choosertxtid = '#' + $(this).attr('id').replace(/_deletebtn$/, '_choosertxt');
    var fileinputid = '#' + $(this).attr('id').replace(/_deletebtn$/, '');
    var deleteinputid = '#' + $(this).attr('id').replace(/btn$/, '');

    $(deleteinputid).click();
    $(chooserbtnid).toggleClass('btn-danger');
    $(chooserbtnid).toggleClass('not-allowed');
    $(choosertxtid).toggleClass('btn-danger');
    $(choosertxtid).val($(choosertxtid).hasClass('btn-danger') ? 'Removal requested' : '');
    $(fileinputid).val('');

    if($(this).attr('id').search('_key') >= 0) {
      if($(choosertxtid).hasClass('btn-danger') != $('#crt_client_choosertxt').hasClass('btn-danger')) {
        $('#crt_client_deletebtn').click();
      }
    } else {
      if($(choosertxtid).hasClass('btn-danger') != $('#crt_client_key_choosertxt').hasClass('btn-danger')) {
        $('#crt_client_key_deletebtn').click();
      }
    }
  });

  $('input[type="file"]').change(function() {
    var choosertxtid = '#' + $(this).attr('id') + '_choosertxt';

    $(choosertxtid).val($(this).val());
  });

  $('#save').click(function() {
    $(this).prop('disabled', true);
    $('#save-loading').show();
  });

  $('#status .close').click(function() {
    $(this).parent().hide();
  });

  $('#statusbtn').click(function() {
    $('#status-loading').show();

    $.ajax({
      url: '?/status',
    }).done(function(data) {
      $('#status-loading').hide();
      $('#status-text').html('<ul>' + data + '</ul>');
      $('#status').show('slow');
    });
  });
});
