<div id="buttons">
    <a href="#" class="btn white" onclick="Popup.load('/users/edit/<?=$user['id']?>', 400);">
        <span class="fa fa-edit mr5"></span>
        Wijzigen
    </a>
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
                <th width="15%">Naam</th>
                <td width="35%">
                    <?=$user['name_first']?>
                    <?=$user['name_suffix']?>
                    <?=$user['name']?>
                </td>
                <th width="15%">Accounts</th>
                <td width="35%"><?=$user['count_accounts']?></td>
            </tr>
            <tr>
                <th width="15%">Hoeveelheid licenties</th>
                <td width="35%"><?=$user['count_licenses']?></td>
                <th width="15%">Actieve licenties</th>
                <td width="35%"><?=$user['count_active_licenses']?></td>
            </tr>
            <tr>
                <th width="15%">Verlopen licenties</th>
                <td width="35%"><?=$user['count_expired_licenses']?></td>
                <th width="15%">Docenten</th>
                <td width="35%"><?=$user['count_teachers']?></td>
            </tr>
            <tr>
                <th width="15%">Gebruikte licenties</th>
                <td colspan="3"><?=$user['count_students']?></td>
            </tr>
        </table>
    </div>
</div>



<div class="block">
    <div class="block-head">Notities</div>
    <div class="block-content">
        <?=nl2br($user['note'])?>
    </div>
</div>