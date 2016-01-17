<!--
  VPN Client app for YunoHost 
  Copyright (C) 2015 Julien Vaubourg <julien@vaubourg.com>
  Contribute at https://github.com/labriqueinternet/vpnclient_ynh
  
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

<h2><?= _("VPN Client Configuration") ?></h2>
<?php if($faststatus): ?>
  <span class="label label-success" data-toggle="tooltip" data-title="<?= _('This is a fast status. Click on More details to show the complete status.') ?>"><?= _('Running') ?></span>
<?php else: ?>
  <span class="label label-danger" data-toggle="tooltip" data-title="<?= _('This is a fast status. Click on More details to show the complete status.') ?>"><?= _('Not Running') ?></span>
<?php endif; ?>

 &nbsp; <img src="public/img/loading.gif" id="status-loading" alt="Loading..." /><a href="#" id="statusbtn" data-toggle="tooltip" data-title="<?= _('Loading complete status may take a few minutes. Be patient.') ?>"><?= _('More details') ?></a>

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
          <h3 class="panel-title"><?= _("Service") ?></h3>
        </div>

        <div style="padding: 14px 14px 0 10px">
          <div class="form-group">
            <label for="service_enabled" class="col-sm-3 control-label"><?= _('VPN Enabled') ?></label>
            <div class="col-sm-9 input-group-btn">
              <div class="input-group">
                <input type="checkbox" class="form-control switch" name="service_enabled" id="service_enabled" value="1" <?= $service_enabled == 1 ? 'checked="checked"' : '' ?> />
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="enabled" <?= $service_enabled == 0 ? 'style="display: none"' : '' ?>>
        <ul class="nav nav-tabs nav-justified">
          <li role="presentation" data-tab="manualconfig" class="active"><a href="#"><?= _("Manual") ?></a></li>
          <li role="presentation" data-tab="autoconfig"><a href="#"><?= _("Automatic") ?></a></li>
        </ul>

        <div class="tabs tabmanualconfig">
          <div class="panel panel-default">
            <div class="panel-heading">
              <h3 class="panel-title"><?= _("VPN") ?></h3>
            </div>

            <div style="padding: 14px 14px 0 10px">
              <div class="form-group">
                <label for="server_name" class="col-sm-3 control-label"><?= _('Server Address') ?></label>
                <div class="col-sm-9">
                  <input type="text" class="form-control" name="server_name" id="server_name" placeholder="vpn.example.net" value="<?= $server_name ?>" />
                </div>
              </div>
    
              <div class="form-group">
                <label for="server_port" class="col-sm-3 control-label"><?= _('Server Port') ?></label>
                <div class="col-sm-9">
                  <input type="text" data-toggle="tooltip" data-title="<?= _('With restricted access, you should use 443 (TCP) or 53 (UDP)') ?>" class="form-control" name="server_port" id="server_port" placeholder="1194" value="<?= $server_port ?>" />
                </div>
              </div>
    
              <div class="form-group">
                <label for="server_proto" class="col-sm-3 control-label"><?= _('Protocol') ?></label>
                <div class="btn-group col-sm-9" data-toggle="buttons">
                  <label class="btn btn-default <?= $server_proto == 'udp' ? 'active' : '' ?>">
                    <input type="radio" name="server_proto" value="udp" <?= $server_proto == 'udp' ? 'checked="cheked"' : '' ?> /> <?= _('UDP') ?>
                  </label>
    
                  <label class="btn btn-default <?= $server_proto == 'tcp' ? 'active' : '' ?>" data-toggle="tooltip" data-title="<?= _('UDP is more efficient than TCP (but more filtered in case of restrictive access)') ?>">
                    <input type="radio" name="server_proto" value="tcp" <?= $server_proto == 'tcp' ? 'checked="cheked"' : '' ?> /> <?= _('TCP') ?>
                  </label>
                </div>
              </div>

              <div class="form-group">
                <label for="ip6_net" class="col-sm-3 control-label"><?= _('Delegated prefix (IPv6)') ?></label>
                <div class="col-sm-9">
                  <input type="text" data-toggle="tooltip" data-title="<?= _('Leave empty if your Internet Service Provider does not give you a delegated prefix') ?>" class="form-control" name="ip6_net" id="ip6_net" placeholder="2001:db8:42::" value="<?= $ip6_net ?>" />
                </div>
              </div>

              <div class="form-group" id="raw_openvpn_btnpanel">
                <label class="col-sm-3 control-label"></label>
                <div class="col-sm-9">
                  <span class="glyphicon glyphicon-cog"></span> <a href="javascript:" id="raw_openvpn_btn" data-toggle="tooltip" data-title="<?= _('Edit the raw configuration only if you know what you do!') ?>"><?= _('Advanced') ?></a>
                </div>
              </div>

              <div class="form-group" id="raw_openvpn_panel">
                <label for="raw_openvpn" class="col-sm-3 control-label"><?= _('Advanced') ?></label>
                <div class="col-sm-9">
                  <pre><textarea class="form-control" name="raw_openvpn" id="raw_openvpn"><?= $raw_openvpn ?></textarea></pre>
                </div>
              </div>
            </div>
          </div>

          <?php if(!$crt_client_key_exists && empty($login_user)): ?>
            <div class="alert alert-dismissible alert-warning fade in" style="margin: 2px 0px 17px" role="alert">
              <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
              <strong><?= _('Notice') ?>:</strong> <?= _("You need to upload a Client Certificate, or define a Username (or both) for starting your VPN Client.") ?>
            </div>
          <?php endif; ?>

          <div class="panel panel-default">
            <div class="panel-heading">
              <h3 class="panel-title"><?= _("Authentication") ?></h3>
            </div>

            <div style="padding: 14px 14px 0 10px">
              <div class="form-group">
                <?php if(!$crt_server_ca_exists): ?>
                  <div class="alert alert-dismissible alert-warning fade in" style="margin: 2px 16px 17px" role="alert">
                    <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                    <strong><?= _('Notice') ?>:</strong> <?= _("You need to upload a Server CA for starting your VPN Client.") ?>
                  </div>
                <?php endif; ?>

                <label for="crt_server_ca" class="col-sm-3 control-label"><?= $crt_server_ca_exists ? _('Update Server CA') : _('Upload Server CA') ?></label>
                <div class="input-group col-sm-9" style="padding: 0 15px">
                  <?php if($crt_server_ca_exists): ?>
                    <a class="btn btn-danger not-allowed btn-disabled input-group-addon" id="crt_server_ca_deletebtn" data-toggle="tooltip" data-title="<?= _('You cannot have no server CA') ?>"><span class="glyphicon glyphicon-remove"></span></a>
                    <input id="crt_server_ca_delete" name="crt_server_ca_delete" type="checkbox" value="1" style="display: none" />
                  <?php endif; ?>
                  <input type="text" class="form-control fileinput" id="crt_server_ca_choosertxt" placeholder="-----BEGIN CERTIFICATE-----" readonly="readonly" />
                  <input id="crt_server_ca" name="crt_server_ca" type="file" style="display: none" />
                  <a class="btn input-group-addon fileinput" id="crt_server_ca_chooserbtn" data-toggle="tooltip" data-title="<?= _('Browse') ?>"><span class="glyphicon glyphicon-search"></span></a>
                </div>
              </div>

              <div class="form-group">
                <label for="crt_client" class="col-sm-3 control-label"><?= $crt_client_exists ? _('Update Client Cert.') : _('Upload Client Cert.') ?></label>
                <div class="input-group col-sm-9" style="padding: 0 15px">
                  <?php if($crt_client_exists): ?>
                    <a class="btn btn-danger input-group-addon deletefile" id="crt_client_deletebtn" data-toggle="tooltip" data-title="<?= _('Delete this certificate') ?>"><span class="glyphicon glyphicon-remove"></span></a>
                    <input id="crt_client_delete" name="crt_client_delete" type="checkbox" value="1" style="display: none" />
                  <?php endif; ?>
                  <input type="text" class="form-control fileinput" id="crt_client_choosertxt" placeholder="-----BEGIN CERTIFICATE-----" readonly="readonly" />
                  <input id="crt_client" name="crt_client" type="file" style="display: none" />
                  <a class="btn input-group-addon fileinput" id="crt_client_chooserbtn" data-toggle="tooltip" data-title="<?= _('Browse') ?>"><span class="glyphicon glyphicon-search"></span></a>
                </div>
              </div>

              <div class="form-group">
                <label for="crt_client_key" class="col-sm-3 control-label"><?= $crt_client_key_exists ? _('Update Client Key') : _('Upload Client Key') ?></label>
                <div class="input-group col-sm-9" style="padding: 0 15px">
                  <?php if($crt_client_key_exists): ?>
                    <a class="btn btn-danger input-group-addon deletefile" id="crt_client_key_deletebtn" data-toggle="tooltip" data-title="<?= _('Delete this certificate') ?>"><span class="glyphicon glyphicon-remove"></span></a>
                    <input id="crt_client_key_delete" name="crt_client_key_delete" type="checkbox" value="1" style="display: none" />
                  <?php endif; ?>
                  <input type="text" class="form-control fileinput" id="crt_client_key_choosertxt" data-toggle="tooltip" data-title="<?= _('Make sure your browser is able to read the key file before uploading') ?>" placeholder="-----BEGIN PRIVATE KEY-----" readonly="readonly" />
                  <input id="crt_client_key" name="crt_client_key" type="file" style="display: none" />
                  <a class="btn input-group-addon fileinput" id="crt_client_key_chooserbtn" data-toggle="tooltip" data-title="<?= _('Browse') ?> (<?= _('make sure your browser is able to read the key file before uploading') ?>)"><span class="glyphicon glyphicon-search"></span></a>
                </div>
              </div>

              <div class="form-group">
                <label for="crt_client_ta" class="col-sm-3 control-label"><?= $crt_client_ta_exists ? _('Update Shared-Secret') : _('Upload Shared-Secret') ?></label>
                <div class="input-group col-sm-9" style="padding: 0 15px">
                  <?php if($crt_client_ta_exists): ?>
                    <a class="btn btn-danger input-group-addon deletefile" id="crt_client_ta_deletebtn" data-toggle="tooltip" data-title="<?= _('Delete this certificate') ?>"><span class="glyphicon glyphicon-remove"></span></a>
                    <input id="crt_client_ta_delete" name="crt_client_ta_delete" type="checkbox" value="1" style="display: none" />
                  <?php endif; ?>
                  <input type="text" class="form-control fileinput" id="crt_client_ta_choosertxt" data-toggle="tooltip" data-title="<?= _('Make sure your browser is able to read the key file before uploading') ?>" placeholder="ta.key" readonly="readonly" />
                  <input id="crt_client_ta" name="crt_client_ta" type="file" style="display: none" />
                  <a class="btn input-group-addon fileinput" id="crt_client_ta_chooserbtn" data-toggle="tooltip" data-title="<?= _('Browse') ?> (<?= _('make sure your browser is able to read the key file before uploading') ?>)"><span class="glyphicon glyphicon-search"></span></a>
                </div>
              </div>

              <div class="form-group">
                <label for="login_user" class="col-sm-3 control-label"><?= _('Username') ?></label>
                <div class="col-sm-9">
                  <input type="text" data-toggle="tooltip" data-title="<?= _('Leave empty if not necessary') ?>" class="form-control" name="login_user" id="login_user" placeholder="michu" value="<?= $login_user ?>" />
                </div>
              </div>

              <div class="form-group">
                <label for="login_passphrase" class="col-sm-3 control-label"><?= _('Password') ?></label>
                <div class="col-sm-9">
                  <input type="password" data-toggle="tooltip" data-title="<?= _('Leave empty if not necessary') ?>" class="form-control" name="login_passphrase" id="login_passphrase" placeholder="XVCwSbDkxnqQ" value="<?= $login_passphrase ?>" />
                </div>
              </div>
            </div>
          </div>

          <div class="panel panel-default">
            <div class="panel-heading">
              <h3 class="panel-title"><?= _("DNS") ?></h3>
            </div>

            <div style="padding: 14px 14px 0 10px">
              <div class="form-group">
                <label for="dns0" class="col-sm-3 control-label"><?= _('First resolver') ?></label>
                <div class="col-sm-9">
                  <input type="text" data-toggle="tooltip" data-title="<?= _('IPv6 or IPv4') ?>" class="form-control" name="dns0" id="dns0" placeholder="89.234.141.66" value="<?= $dns0 ?>" />
                </div>
              </div>

              <div class="form-group">
                <label for="dns1" class="col-sm-3 control-label"><?= _('Second resolver') ?></label>
                <div class="col-sm-9">
                  <input type="text" data-toggle="tooltip" data-title="<?= _('IPv6 or IPv4') ?>" class="form-control" name="dns1" id="dns1" placeholder="2001:913::8" value="<?= $dns1 ?>" />
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="tabs tabautoconfig" style="display: none">
          <div class="panel panel-default">
            <div class="panel-heading">
              <h3 class="panel-title"><?= _("Auto Configuration") ?></h3>
            </div>

            <div style="padding: 14px 14px 0 10px">
              <div class="form-group">
                <label for="cubefile" class="col-sm-3 control-label"><?= _('Upload Config') ?></label>
                <div class="input-group col-sm-9" style="padding: 0 15px">
                  <input type="text" class="form-control fileinput" id="cubefile_choosertxt" placeholder="config.cube" readonly="readonly" />
                  <input id="cubefile" name="cubefile" type="file" style="display: none" />
                  <a class="btn input-group-addon fileinput" id="cubefile_chooserbtn" data-toggle="tooltip" data-title="<?= _('Browse') ?>"><span class="glyphicon glyphicon-search"></span></a>
                </div>
              </div>
              <p style="text-align: center"><a href="http://internetcu.be/dotcubefiles.html"><?= _('What is a dot cube file?') ?></a></p>
            </div>
          </div>
        </div>
      </div>

      <div class="form-group">
        <div style="text-align: center">
          <button type="submit" class="btn btn-default" data-toggle="tooltip" id="save" data-title="<?= _('Reloading may take a few minutes. Be patient.') ?>"><?= _('Save and reload') ?></button> <img src="public/img/loading.gif" id="save-loading" alt="Loading..." />
        </div>
      </div>
    </form>
  </div>
</div>
