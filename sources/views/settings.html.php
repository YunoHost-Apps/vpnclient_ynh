<h2><?= T_("VPN Client Configuration") ?></h2>
<hr>
<div class="row">
    <div class="col-sm-offset-2 col-sm-8">
        <form method="post" action="settings" class="form-horizontal" role="form">
            <input type="hidden" name="_method" value="put" />
            <div class="form-group">
                <label for="server_name" class="col-sm-3 control-label"><?= T_('Server Address') ?></label>
                <div class="col-sm-9">
                    <input type="text" class="form-control" name="server_name" id="server_name" placeholder="access.ldn-fai.net">
                </div>
            </div>
            <div class="form-group">
                <label for="server_port" class="col-sm-3 control-label"><?= T_('Server Port') ?></label>
                <div class="col-sm-9">
                    <input type="text" data-toggle="tooltip" data-title="<?= T_('With restricted access, you should use 443 (TCP) or 53 (UDP)') ?>" class="form-control" name="server_port" id="server_port" placeholder="1194">
                </div>
            </div>
            <div class="form-group">
                <label for="server_port" class="col-sm-3 control-label"><?= T_('Protocol') ?></label>
		<div class="btn-group col-sm-9" data-toggle="buttons">
		    <label class="btn btn-default active">
		        <input type="radio" name="server_proto" value="udp"> <?= T_('UDP') ?>
		    </label>

		    <label class="btn btn-default" data-toggle="tooltip" data-title="<?= T_('UDP is more efficient than TCP (but more filtered in case of restrictive access)') ?>">
		        <input type="radio" name="server_proto" value="tcp"> <?= T_('TCP') ?>
		    </label>
		</div>
            </div>
            <div class="form-group">
                <label for="crt_client" class="col-sm-3 control-label"><?= T_('Client Certificate') ?></label>
		<div class="btn-group col-sm-9">
		    <textarea class="form-control" name="crt_client" id="crt_client" placeholder="-----BEGIN CERTIFICATE-----"></textarea>
		</div>
            </div>
            <div class="form-group">
                <label for="crt_client_key" class="col-sm-3 control-label"><?= T_('Client Certificate Key') ?></label>
		<div class="btn-group col-sm-9">
		    <textarea class="form-control" name="crt_client_key" id="crt_client_key" placeholder="-----BEGIN PRIVATE KEY-----"></textarea>
		</div>
            </div>
            <div class="form-group">
                <label for="crt_server_ca" class="col-sm-3 control-label"><?= T_('Server Certificate Authority') ?></label>
		<div class="btn-group col-sm-9">
		    <textarea class="form-control" name="crt_server_ca" id="crt_server_ca" placeholder="-----BEGIN CERTIFICATE-----"></textarea>
		</div>
            </div>

            <div class="form-group">
                <div class="col-xs-offset-3 col-sm-9">
                    <button type="submit" class="btn btn-default"><?= T_('Save and reload') ?></button>
                </div>
            </div>
        </form>
    </div>
</div>
