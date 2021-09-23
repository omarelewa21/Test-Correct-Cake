<div id="buttons">
    <?php if((bool) $user['demo'] !== true){?>
    <a href="#" class="btn white" onclick="Popup.load('/users/edit/<?=getUUID($user, 'get');?>', 400);">
        <span class="fa fa-edit mr5"></span>
        Wijzigen
    </a>
    <?php } ?>
    <a href="#" class="btn white mr2" onclick="Navigation.back();">
        <span class="fa fa-backward mr5"></span>
        Terug
    </a>
</div>

<h1>
    <?=$user['name_first']?>
    <?=$user['name_suffix']?>
    <?=$user['name']?>
</h1>

<div class="block">
    <div class="block-head">Informatie</div>
    <div class="block-content">
        <table class="table table-striped">
            <tr>
                <th width="10%">Naam</th>
                <td width="23%">
                    <?=$user['name_first']?>
                    <?=$user['name_suffix']?>
                    <?=$user['name']?>
                </td>
            </tr>
        </table>
    </div>
</div>

<div class="block">
    <div class="block-head">Logs</div>
    <div class="block-content">
        <table class="table table-striped">
            <tr>
                <th>Gebruiker</th>
                <th>Rol</th>
                <th>Datum</th>
                <th>IP</th>
            </tr>
            <?
            foreach($takeOverLogs as $log) {
                $created_at = new DateTime($log['created_at']);
                $created_at->setTimezone(new DateTimeZone(Configure::read('Config.timezone')));
                $name = sprintf(
                    '%s%s %s',
                    $log['user']['name_first'],
                    !empty($log['user']['name_suffix']) ? ' ' . $log['user']['name_suffix'] : '',
                    $log['user']['name']
                );
                ?>
                <tr>
                    <td><?= $name ?></td>
                    <td><?= $log['user']['roles'][0]['name'] ?></td>
                    <td><?= $created_at->format('d-m-Y H:i:s') ?></td>
                    <td><?= $log['ip'] ?></td>
                </tr>
                <?
            }
            ?>
        </table>
    </div>
</div>