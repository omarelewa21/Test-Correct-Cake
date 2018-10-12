<div id="buttons">
    <a href="#" class="btn white" onclick="Navigation.load('/analyses/student/<?=$user['id']?>');">
        <span class="fa fa-line-chart mr5"></span>
        Analyse
    </a>
    <a href="#" class="btn white" onclick="Popup.load('/users/edit/<?=$user['id']?>', 400);">
        <span class="fa fa-edit mr5"></span>
        Wijzigen
    </a>
    <a href="#" class="btn white" onclick="Popup.load('/messages/send/<?=$user['id']?>', 500);">
        <span class="fa fa-envelope-o"></span>
        Bericht sturen
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

<div class="block" style="width:calc(100% - 200px); float:left;">
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
                <th width="10%">Tijd dispensatie</th>
                <td width="23%"><?=$user['time_dispensation'] == 0 ? 'Nee' : 'Ja'?></td>
                <th width="10%">Klassen</th>
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
    <div class="block-head">Profielfoto</div>
    <div class="block-content">
        <img src="/users/profile_picture/<?=$user['id']?>/<?=time()?>" id="profile-picture-<?=$user['id']?>" style="max-width:130px;" />
    </div>
</div>

<br clear="all" />

<div class="block">
    <div class="block-head">Ouders</div>
    <div class="block-content">
        <table class="table table-striped">
            <tr>
                <th>Naam</th>
                <th>E-mailadres</th>
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
                        <a href="#" class="btn white pull-right dropblock-owner dropblock-left" id="test_<?=$parent['id']?>">
                            <span class="fa fa-list-ul"></span>
                        </a>
                        <div class="dropblock blur-close" for="test_<?=$parent['id']?>">
                            <a href="#" class="btn highlight white" onclick="Popup.load('/users/edit/<?=$parent['id']?>', 400);">
                                <span class="fa fa-edit mr5"></span>
                                Wijzigen
                            </a>
                            <a href="#" class="btn highlight white" onclick="User.delete(<?=$parent['id']?>);">
                                <span class="fa fa-remove mr5"></span>
                                Delete
                            </a>
                        </div>
                    </td>
                </tr>
                <?
            }
            ?>

        </table>

        <br clear="all" />
        <center>
            <a href="#" class="btn highlight inline-block" onclick="Popup.load('/users/add/parents/<?=$user['id']?>', 400);">
                Ouder toevoegen
            </a>
        </center>
    </div>
</div>

<? if(!empty($user['note'])) { ?>
    <div class="block">
        <div class="block-head">Notities</div>
        <div class="block-content">
            <?=nl2br($user['note'])?>
        </div>
    </div>
<? } ?>