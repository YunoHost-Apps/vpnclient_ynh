<h2><?= T_("VPN Client Configuration") ?></h2>

<hr />

<div class="row">
  <div class="col-sm-offset-2 col-sm-8">
    <form method="post" enctype="multipart/form-data" action="?/settings" class="form-horizontal" role="form">
      <input type="hidden" name="_method" value="put" />

      <div class="panel panel-default">
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
        </div>
      </div>

      <div class="panel panel-success">
        <div class="panel-heading">
          <h3 class="panel-title" data-toggle="tooltip" data-title="<?= T_('Real Internet') ?>"><?= T_("IPv6") ?></h3>
        </div>

        <div style="padding: 14px 14px 0 10px">
          <div class="form-group">
            <label for="ip6_net" class="col-sm-3 control-label"><?= T_('Delegated prefix') ?></label>
            <div class="col-sm-9">
              <input type="text" data-toggle="tooltip" data-title="<?= T_('Leave empty if your internet provider is a dirty provider that does not give you a delegated prefix') ?>" class="form-control" name="ip6_net" id="ip6_net" placeholder="2001:db8:42::" value="<?= $ip6_net ?>" />
            </div>
          </div>
        </div>
      </div>

      <div class="panel panel-default">
        <div class="panel-heading">
          <h3 class="panel-title"><?= T_("Certificates") ?></h3>
        </div>

        <div style="padding: 14px 14px 0 10px">
          <div class="form-group">
            <label for="crt_client" class="col-sm-3 control-label"><?= T_('Update Client Cert.') ?></label>
            <div class="input-group col-sm-9" style="padding: 0 15px">
              <input id="crt_client" name="crt_client" type="file" style="display: none" />
              <input type="text" class="form-control fileinput" id="crt_client_choosertxt" placeholder="-----BEGIN CERTIFICATE-----" readonly="readonly" />
              <a class="btn input-group-addon fileinput" id="crt_client_chooserbtn"><?= T_('Browse') ?></a>
            </div>
          </div>

          <div class="form-group">
            <label for="crt_client_key" class="col-sm-3 control-label"><?= T_('Update Client Key') ?></label>
            <div class="input-group col-sm-9" style="padding: 0 15px">
              <input id="crt_client_key" name="crt_client_key" type="file" style="display: none" />
              <input type="text" class="form-control fileinput" id="crt_client_key_choosertxt" placeholder="-----BEGIN PRIVATE KEY-----" readonly="readonly" />
              <a class="btn input-group-addon fileinput" id="crt_client_key_chooserbtn"><?= T_('Browse') ?></a>
            </div>
          </div>

          <div class="form-group">
            <label for="crt_server_ca" class="col-sm-3 control-label"><?= T_('Update Server CA') ?></label>
            <div class="input-group col-sm-9" style="padding: 0 15px">
              <input id="crt_server_ca" name="crt_server_ca" type="file" style="display: none" />
              <input type="text" class="form-control fileinput" id="crt_server_ca_choosertxt" placeholder="-----BEGIN CERTIFICATE-----" readonly="readonly" />
              <a class="btn input-group-addon fileinput" id="crt_server_ca_chooserbtn"><?= T_('Browse') ?></a>
            </div>
          </div>
        </div>
      </div>

      <div class="panel panel-default">
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
          <button type="submit" class="btn btn-default" data-toggle="tooltip" data-title="<?= T_('Reloading may take a few minutes. Be patient.') ?>"><?= T_('Save and reload') ?></button>
        </div>
      </div>
    </form>
  </div>
</div>
