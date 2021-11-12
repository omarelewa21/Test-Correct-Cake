<div id="buttons">
    <a href="#" class="btn white" onclick="Popup.load('/users/edit/<?=getUUID($user, 'get');?>', 400);">
        <span class="fa fa-edit mr5"></span>
        <?=__('Wijzigen')?>
    </a>
    <a href="#" class="btn white mr2" onclick="Navigation.back();">
        <span class="fa fa-backward mr5"></span>
        <?=__('Terug')?>
    </a>
</div>

<h1>
    <?=$user['name_first']?>
    <?=$user['name_suffix']?>
    <?=$user['name']?>
</h1>

<div class="block">
    <div class="block-head"><?=__('Informatie')?></div>
    <div class="block-content">
        <table class="table table-striped">
            <tr>
                <th width="10%"><?=__('Naam')?></th>
                <td width="23%">
                    <?=$user['name_first']?>
                    <?=$user['name_suffix']?>
                    <?=$user['name']?>
                </td>
            </tr>
            <tr>
                <th width="10%"><?=__('Aangemaakt op')?></th>
                <td width="23%">
                    <?php
                    $created_at = new DateTime($user['created_at']);
                    $created_at->setTimezone(new DateTimeZone(Configure::read('Config.timezone')));
                    echo $created_at->format('d-m-Y H:i:s');
                    ?>
                </td>
            </tr>
        </table>
    </div>
</div>