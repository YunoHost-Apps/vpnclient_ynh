/* VPN Client app for YunoHost 
 * Copyright (C) 2015 Julien Vaubourg <julien@vaubourg.com>
 * Contribute at https://github.com/labriqueinternet/vpnclient_ynh
 * 
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 * 
 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

function tabsClick() {
  var tab = $(this).parent().attr('data-tab');

  $('.nav').find('li.active').removeClass('active');
  $(this).parent().addClass('active');

  $('.tabs').hide();
  $('.tab' + tab).show();

  return false;
}

function ready() {
  $('.btn-group').button();
  $('[data-toggle="tooltip"]').tooltip();

  $('.switch').bootstrapToggle();
  $('.nav-tabs a').click(tabsClick);

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
    } else if($(this).attr('id').search('_ta') < 0) {
      if($(choosertxtid).hasClass('btn-danger') != $('#crt_client_key_choosertxt').hasClass('btn-danger')) {
        $('#crt_client_key_deletebtn').click();
      }
    }
  });

  $('input[type="file"]').change(function() {
    var choosertxtid = '#' + $(this).attr('id') + '_choosertxt';

    $(choosertxtid).val($(this).val().replace(/^.*[\/\\]/, ''));
  });

  $('#form').on("submit", function(event) {
    event.preventDefault()
    $('#save').prop('disabled', true);
    $('#save-loading').show();
    $.ajax({
        url: this.action,
        type: this.method,
        contentType: false,
        processData: false,
        cache: false,
        data: new FormData(this),
        headers: {
          'X-Requested-With': 'jQuery',
        },
        dataType: "html",
        success: function(data){
          document.body.innerHTML = new DOMParser().parseFromString(data, "text/html").body.innerHTML
          ready()
        },
        error: function() {
          $('#save').prop('disabled', false);
          $('#save-loading').hide();
        },
    });
  })

  $('#status .close').click(function() {
    $(this).parent().hide();
  });

  $('#statusbtn').click(function() {
    if($('#status-loading').is(':hidden')) {
      $('#status').hide();
      $('#status-loading').show();

      $.ajax({
        url: '?/status',
      }).done(function(data) {
        $('#status-loading').hide();
        $('#status-text').html('<ul>' + data + '</ul>');
        $('#status').show('slow');
      });
    }
  });

  $('#raw_openvpn_btn').click(function() {
    $('#raw_openvpn_btnpanel').hide();
    $('#raw_openvpn_panel').show('low');
  });

  $('#service_enabled').change(function() {
    if($('#service_enabled').parent().hasClass('off')) {
      $('.enabled').hide('slow');
    } else {
      $('.enabled').show('slow');
    }
  });
}

$(document).ready(ready)
