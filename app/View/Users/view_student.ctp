<div id="buttons">
    <a href="#" class="btn white" onclick="Navigation.load('/analyses/student/<?=getUUID($user, 'get');?>');">
        <span class="fa fa-line-chart mr5"></span>
        <?= __("Analyse")?>
    </a>
    <?php if((bool) $user['demo'] !== true){?>
    <a href="#" class="btn white" onclick="Popup.load('/users/edit/<?=getUUID($user, 'get');?>', 400);">
        <span class="fa fa-edit mr5"></span>
        <?= __("Wijzigen")?>
    </a>
    <a href="#" class="btn white" onclick="Popup.load('/messages/send/<?=getUUID($user, 'get');?>', 500);">
        <span class="fa fa-envelope-o"></span>
        <?= __("Bericht sturen")?>
    </a>
    <?php } ?>
    <a href="#" class="btn white mr2" onclick="Navigation.back();">
        <span class="fa fa-backward mr5"></span>
        <?= __("Terug")?>
    </a>
</div>

<h1>
    <?=$user['name_first']?>
    <?=$user['name_suffix']?>
    <?=$user['name']?>
</h1>

<div class="block" style="width:calc(100% - 200px); float:left;">
    <div class="block-head"><?= __("Informatie")?></div>
    <div class="block-content">
        <table class="table table-striped">
            <tr>
                <th width="10%"><?= __("Naam")?></th>
                <td width="23%">
                    <?=$user['name_first']?>
                    <?=$user['name_suffix']?>
                    <?=$user['name']?>
                </td>
                <th width="10%"><?= __("Dyslexie")?></th>
                <td width="23%"><?=$user['time_dispensation'] == 0 ? __("Nee") : __("Ja")?></td>
                <th width="10%"><?= __("Voorlees functionaliteit")?></th>
                <td width="23%">
                    <?=$user['has_text2speech'] == 0 ? __("Nee") : __("Is toegekend")?>
                    <?= ($user['has_text2speech'] && !$user['active_text2speech']) ? __("<br />maar is (tijdelijk) gedeactiveerd") : ''?>
                </td>
                <th width="10%"><?= __("Klassen")?></th>
                <td width="23%">
                    <?
                    foreach($user['student_school_classes'] as $class) {
                        echo $class['name'] . ', ';
                    }
                    ?>
                </td>
            </tr>
        </table>
    </div>
</div>

<div class="block" style="width:180px; float:right;">
    <div class="block-head"><?= __("Profielfoto")?></div>
    <div class="block-content">
        <img src="/users/profile_picture/<?=getUUID($user, 'get');?>/<?=time()?>" id="profile-picture-<?=getUUID($user, 'get');?>" style="max-width:130px;" />
    </div>
</div>

<br clear="all" />

<div class="block">
    <div class="block-head"><?= __("Ouders")?></div>
    <div class="block-content">
        <table class="table table-striped">
            <tr>
                <th><?= __("Naam")?></th>
                <th><?= __("E-mailadres")?></th>
                <th></th>
            </tr>
            <?
            foreach($parents as $parent) {
                ?>
                <tr>
                    <td>
                        <?=$parent['name_first']?>
                        <?=$parent['name_suffix']?>
                        <?=$parent['name']?>
                    </td>
                    <td>
                        <?=$parent['username']?>
                    </td>
                    <td class="nopadding">
                        <a href="#" class="btn white pull-right dropblock-owner dropblock-left" id="test_<?=getUUID($parent, 'get');?>">
                            <span class="fa fa-list-ul"></span>
                        </a>
                        <div class="dropblock blur-close" for="test_<?=getUUID($parent, 'get');?>">
                            <a href="#" class="btn highlight white" onclick="Popup.load('/users/edit/<?=getUUID($parent, 'get');?>', 400);">
                                <span class="fa fa-edit mr5"></span>
                                <?= __("Wijzigen")?>
                            </a>
                            <a href="#" class="btn highlight white" onclick="User.delete(<?=getUUID($parent, 'getQuoted');?>);">
                                <span class="fa fa-remove mr5"></span>
                                <?= __("Verwijderen")?>
                            </a>
                        </div>
                    </td>
                </tr>
                <?
            }
            ?>

        </table>

        <br clear="all" />
        <?php if((bool) $user['demo'] !== true){?>
        <center>
            <a href="#" class="btn highlight inline-block" onclick="Popup.load('/users/add/parents/<?=getUUID($user, 'get');?>', 400);">
            <?= __("Ouder toevoegen")?>
            </a>
        </center>
        <?php } ?>
    </div>
</div>

<? if(!empty($user['note'])) { ?>
    <div class="block">
        <div class="block-head"><?= __("Notities")?></div>
        <div class="block-content">
            <?=nl2br($user['note'])?>
        </div>
    </div>
<? } ?>