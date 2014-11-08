<h2><?= T_("Configure your VPN client") ?></h2>
<hr>
<div class="row">
    <div class="col-sm-offset-2 col-sm-8">
        <form method="post" action="settings" class="form-horizontal" role="form">
            <input type="hidden" name="_method" value="put" />
            <div class="form-group">
                <label for="host" class="col-sm-3 control-label"><?= T_('Host') ?></label>
                <div class="col-sm-9">
                    <input type="text" class="form-control" name="host" id="host" placeholder="vpn.neutrinet.be">
                </div>
            </div>
            <div class="form-group">
                <label for="password" class="col-sm-3 control-label"><?= T_('Password') ?></label>
                <div class="col-sm-9">
                    <input type="password" class="form-control" name="password" id="password" placeholder="Password">
                </div>
            </div>
            <div class="form-group">
                <label for="privatekey" class="col-sm-3 control-label"><?= T_('Private key') ?></label>
                <div class="col-sm-9">
                    <textarea rows="7" class="form-control" name="privatekey" id="privatekey">
-----BEGIN PRIVATE KEY-----
-----END PRIVATE KEY-----
                    </textarea>
                </div>
            </div>
            <div class="form-group">
                <div class="col-xs-offset-3 col-sm-10">
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" name="usetcp" value="yes"> <?= T_('Use TCP') ?>
                        </label>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="col-xs-offset-3 col-sm-9">
                    <button type="submit" class="btn btn-default"><?= T_('Save settings') ?></button>
                </div>
            </div>
        </form>
    </div>
</div>
