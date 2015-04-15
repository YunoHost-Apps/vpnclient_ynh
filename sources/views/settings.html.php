<!--
  VPN Client app for YunoHost 
  Copyright (C) 2015 Julien Vaubourg <julien@vaubourg.com>
  Contribute at https://github.com/jvaubourg/vpnclient_ynh
  
  This program is free software: you can redistribute it and/or modify
  it under the terms of the GNU Affero General Public License as published by
  the Free Software Foundation, either version 3 of the License, or
  (at your option) any later version.
  
  This program is distributed in the hope that it will be useful,
  but WITHOUT ANY WARRANTY; without even the implied warranty of
  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
  GNU Affero General Public License for more details.
  
  You should have received a copy of the GNU Affero General Public License
  along with this program.  If not, see <http://www.gnu.org/licenses/>.
-->

<h2><?= T_("VPN Client Configuration") ?></h2>
<?php if($faststatus): ?>
  <span class="label label-success" data-toggle="tooltip" data-title="<?= T_('This is a fast status. Click on More details to show the complete status.') ?>"><?= T_('Running') ?></span>
<?php else: ?>
  <span class="label label-danger" data-toggle="tooltip" data-title="<?= T_('This is a fast status. Click on More details to show the complete status.') ?>"><?= T_('Not Running') ?></span>
<?php endif; ?>

 &nbsp; <img src="public/img/loading.gif" id="status-loading" alt="Loading..." /><a href="#" id="statusbtn" data-toggle="tooltip" data-title="<?= T_('Loading complete status may take a few minutes. Be patient.') ?>"><?= T_('More details') ?></a>

<div id="status" class="alert alert-dismissible alert-info fade in" style="margin-top: 10px" role="alert">
  <button type="button" class="close"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
  <div id="status-text"></div>
</div>

<hr />

<div class="row">
  <div class="col-sm-offset-2 col-sm-8">
    <form method="post" enctype="multipart/form-data" action="?/settings" class="form-horizontal" role="form" id="form">
      <input type="hidden" name="_method" value="put" />

      <div class="panel panel-default">
        <div class="panel-heading">
          <h3 class="panel-title"><?= T_("Service") ?></h3>
        </div>

        <div style="padding: 14px 14px 0 10px">
          <div class="form-group">
            <label for="wifi_secure" class="col-sm-3 control-label"><?= T_('VPN Enabled') ?></label>
            <div class="col-sm-9 input-group-btn">
              <div class="input-group">
                <input type="checkbox" class="form-control switch" name="service_enabled" id="service_enabled" value="1" <?= $service_enabled == 1 ? 'checked="checked"' : '' ?> />
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="panel panel-default enabled" <?= $service_enabled == 0 ? 'style="display: none"' : '' ?>>
        <div class="panel-heading">
          <h3 class="panel-title"><?= T_("VPN") ?></h3>
        </div>

        <div style="padding: 14px 14px 0 10px">
          <div class="form-group">
            <label for="server_name" class="col-sm-3 control-label"><?= T_('Server Address') ?></label>
            <div class="col-sm-9">
              <input type="text" class="form-control" name="server_name" id="server_name" placeholder="access.ldn-fai.net" value="<?= $server_name ?>" />
            </div>
          </div>
    
          <div class="form-group">
            <label for="server_port" class="col-sm-3 control-label"><?= T_('Server Port') ?></label>
            <div class="col-sm-9">
              <input type="text" data-toggle="tooltip" data-title="<?= T_('With restricted access, you should use 443 (TCP) or 53 (UDP)') ?>" class="form-control" name="server_port" id="server_port" placeholder="1194" value="<?= $server_port ?>" />
            </div>
          </div>
    
          <div class="form-group">
            <label for="server_proto" class="col-sm-3 control-label"><?= T_('Protocol') ?></label>
            <div class="btn-group col-sm-9" data-toggle="buttons">
              <label class="btn btn-default <?= $server_proto == 'udp' ? 'active' : '' ?>">
                <input type="radio" name="server_proto" value="udp" <?= $server_proto == 'udp' ? 'checked="cheked"' : '' ?> /> <?= T_('UDP') ?>
              </label>
    
              <label class="btn btn-default <?= $server_proto == 'tcp' ? 'active' : '' ?>" data-toggle="tooltip" data-title="<?= T_('UDP is more efficient than TCP (but more filtered in case of restrictive access)') ?>">
                <input type="radio" name="server_proto" value="tcp" <?= $server_proto == 'tcp' ? 'checked="cheked"' : '' ?> /> <?= T_('TCP') ?>
              </label>
            </div>
          </div>

          <div class="form-group" id="raw_openvpn_btnpanel">
            <label class="col-sm-3 control-label"></label>
            <div class="col-sm-9">
              <span class="glyphicon glyphicon-cog"></span> <a href="#" id="raw_openvpn_btn" data-toggle="tooltip" data-title="<?= T_('Edit the raw configuration only if you know what you do!') ?>"><?= T_('Advanced') ?></a>
            </div>
          </div>

          <div class="form-group" id="raw_openvpn_panel">
            <label for="raw_openvpn" class="col-sm-3 control-label"><?= T_('Advanced') ?></label>
            <div class="col-sm-9">
              <pre><textarea class="form-control" name="raw_openvpn" id="raw_openvpn"><?= $raw_openvpn ?></textarea></pre>
            </div>
          </div>
        </div>
      </div>

      <div class="panel panel-default enabled" <?= $service_enabled == 0 ? 'style="display: none"' : '' ?>>
        <div class="panel-heading">
          <h3 class="panel-title"><?= T_("IPv6") ?></h3>
        </div>

        <div style="padding: 14px 14px 0 10px">
          <div class="form-group">
            <label for="ip6_net" class="col-sm-3 control-label"><?= T_('Delegated prefix') ?></label>
            <div class="col-sm-9">
              <input type="text" data-toggle="tooltip" data-title="<?= T_('Leave empty if your Internet Service Provider does not give you a delegated prefix') ?>" class="form-control" name="ip6_net" id="ip6_net" placeholder="2001:db8:42::" value="<?= $ip6_net ?>" />
            </div>
          </div>
        </div>
      </div>

      <?php if(!$crt_client_key_exists && empty($login_user)): ?>
        <div class="alert alert-dismissible alert-warning fade in enabled" <?= $service_enabled == 0 ? 'style="display: none"' : '' ?> style="margin: 2px 0px 17px" role="alert">
          <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
          <strong><?= T_('Notice') ?>:</strong> <?= T_("You need to upload a Client Certificate, or define a Username (or both) for starting your VPN Client.") ?>
        </div>
      <?php endif; ?>

      <div class="panel panel-default enabled" <?= $service_enabled == 0 ? 'style="display: none"' : '' ?>>
        <div class="panel-heading">
          <h3 class="panel-title"><?= T_("Certificates") ?></h3>
        </div>

        <div style="padding: 14px 14px 0 10px">
          <div class="form-group">
            <label for="crt_client" class="col-sm-3 control-label"><?= $crt_client_exists ? T_('Update Client Cert.') : T_('Upload Client Cert.') ?></label>
            <div class="input-group col-sm-9" style="padding: 0 15px">
              <?php if($crt_client_exists): ?>
                <a class="btn btn-danger input-group-addon deletefile" id="crt_client_deletebtn" data-toggle="tooltip" data-title="<?= T_('Delete this certificate') ?>"><span class="glyphicon glyphicon-remove"></span></a>
                <input id="crt_client_delete" name="crt_client_delete" type="checkbox" value="1" style="display: none" />
              <?php endif; ?>
              <input type="text" class="form-control fileinput" id="crt_client_choosertxt" placeholder="-----BEGIN CERTIFICATE-----" readonly="readonly" />
              <input id="crt_client" name="crt_client" type="file" style="display: none" />
              <a class="btn input-group-addon fileinput" id="crt_client_chooserbtn" data-toggle="tooltip" data-title="<?= T_('Browse') ?>"><span class="glyphicon glyphicon-search"></span></a>
            </div>
          </div>

          <div class="form-group">
            <label for="crt_client_key" class="col-sm-3 control-label"><?= $crt_client_key_exists ? T_('Update Client Key') : T_('Upload Client Key') ?></label>
            <div class="input-group col-sm-9" style="padding: 0 15px">
              <?php if($crt_client_key_exists): ?>
                <a class="btn btn-danger input-group-addon deletefile" id="crt_client_key_deletebtn" data-toggle="tooltip" data-title="<?= T_('Delete this certificate') ?>"><span class="glyphicon glyphicon-remove"></span></a>
                <input id="crt_client_key_delete" name="crt_client_key_delete" type="checkbox" value="1" style="display: none" />
              <?php endif; ?>
              <input type="text" class="form-control fileinput" id="crt_client_key_choosertxt" data-toggle="tooltip" data-title="<?= T_('Make sure your browser is able to read the key file before uploading') ?>" placeholder="-----BEGIN PRIVATE KEY-----" readonly="readonly" />
              <input id="crt_client_key" name="crt_client_key" type="file" style="display: none" />
              <a class="btn input-group-addon fileinput" id="crt_client_key_chooserbtn" data-toggle="tooltip" data-title="<?= T_('Browse') ?> (<?= T_('make sure your browser is able to read the key file before uploading') ?>)"><span class="glyphicon glyphicon-search"></span></a>
            </div>
          </div>

          <div class="form-group">
            <?php if(!$crt_server_ca_exists): ?>
              <div class="alert alert-dismissible alert-warning fade in" style="margin: 2px 16px 17px" role="alert">
                <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <strong><?= T_('Notice') ?>:</strong> <?= T_("You need to upload a Server CA for starting your VPN Client.") ?>
              </div>
            <?php endif; ?>

            <label for="crt_server_ca" class="col-sm-3 control-label"><?= $crt_server_ca_exists ? T_('Update Server CA') : T_('Upload Server CA') ?></label>
            <div class="input-group col-sm-9" style="padding: 0 15px">
              <?php if($crt_server_ca_exists): ?>
                <a class="btn btn-danger not-allowed btn-disabled input-group-addon" id="crt_server_ca_deletebtn" data-toggle="tooltip" data-title="<?= T_('You cannot have no server CA') ?>"><span class="glyphicon glyphicon-remove"></span></a>
                <input id="crt_server_ca_delete" name="crt_server_ca_delete" type="checkbox" value="1" style="display: none" />
              <?php endif; ?>
              <input type="text" class="form-control fileinput" id="crt_server_ca_choosertxt" placeholder="-----BEGIN CERTIFICATE-----" readonly="readonly" />
              <input id="crt_server_ca" name="crt_server_ca" type="file" style="display: none" />
              <a class="btn input-group-addon fileinput" id="crt_server_ca_chooserbtn" data-toggle="tooltip" data-title="<?= T_('Browse') ?>"><span class="glyphicon glyphicon-search"></span></a>
            </div>
          </div>
        </div>
      </div>

      <div class="panel panel-default enabled" <?= $service_enabled == 0 ? 'style="display: none"' : '' ?>>
        <div class="panel-heading">
          <h3 class="panel-title"><?= T_("Login") ?></h3>
        </div>

        <div style="padding: 14px 14px 0 10px">
          <div class="form-group">
            <label for="login_user" class="col-sm-3 control-label"><?= T_('Username') ?></label>
            <div class="col-sm-9">
              <input type="text" data-toggle="tooltip" data-title="<?= T_('Leave empty if not necessary') ?>" class="form-control" name="login_user" id="login_user" placeholder="michu" value="<?= $login_user ?>" />
            </div>
          </div>

          <div class="form-group">
            <label for="login_passphrase" class="col-sm-3 control-label"><?= T_('Password') ?></label>
            <div class="col-sm-9">
              <input type="text" data-toggle="tooltip" data-title="<?= T_('Leave empty if not necessary') ?>" class="form-control" name="login_passphrase" id="login_passphrase" placeholder="XVCwSbDkxnqQ" value="<?= $login_passphrase ?>" />
            </div>
          </div>
        </div>
      </div>

      <div class="form-group">
        <div style="text-align: center">
          <button type="submit" class="btn btn-default" data-toggle="tooltip" id="save" data-title="<?= T_('Reloading may take a few minutes. Be patient.') ?>"><?= T_('Save and reload') ?></button> <img src="public/img/loading.gif" id="save-loading" alt="Loading..." />
        </div>
      </div>
    </form>
  </div>
</div>
