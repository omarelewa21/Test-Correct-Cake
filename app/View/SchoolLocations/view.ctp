<div id="buttons">
    <? if($isAdministrator): ?>
        <a href="#" class="btn white" onclick="Popup.load('/school_locations/edit/<?=getUUID($school_location, 'get');?>', 1100);">
            <span class="fa fa-edit mr5"></span>
            <?= __("Wijzigen")?>
        </a>
        <a href="#" class="btn white" onclick="SchoolLocation.delete(<?=getUUID($school_location, 'getQuoted');?>, 1);">
            <span class="fa fa-remove mr5"></span>
            <?= __("Verwijderen")?>
        </a>
    <? endif; ?>
    <a href="#" class="btn white mr2" onclick="Navigation.back();">
        <span class="fa fa-backward mr5"></span>
        <?= __("Terug")?>
    </a>
</div>

<h1><?=$school_location['name']?></h1>

<div class="block">
    <div class="block-head"><?= __("Informatie")?></div>
    <div class="block-content">
        <table class="table table-striped">
            <tr>
                <th width="15%"><?= __("Klantcode")?></th>
                <td width="35%">
                    <?=$school_location['customer_code']?>
                </td>
                <th width="15%"><?= __("Accountmanager")?></th>
                <td>
                    <?=$school_location['user']['name_first']?>  <?=$school_location['user']['name_suffix']?> <?=$school_location['user']['name']?>
                </td>
            </tr>
            <tr>
                <th width="15%"><?= __("Bezoekadres")?></th>
                <td width="35%">
                    <?=$school_location['main_address']?><br />
                    <?=$school_location['main_postal']?> <?=$school_location['main_city']?><br />
                    <?=$school_location['main_country']?>
                </td>
                <th width="15%"><?= __("Factuuradres")?></th>
                <td>
                    <?=$school_location['invoice_address']?><br />
                    <?=$school_location['invoice_postal']?> <?=$school_location['invoice_city']?><br />
                    <?=$school_location['invoice_country']?>
                </td>
            </tr>
            <tr>
                <th><?= __("Studenten")?></th>
                <td><?=$school_location['count_students']?> / <?=$school_location['number_of_students']?></td>
                <th><?= __("Vraagitems")?></th>
                <td><?=$school_location['count_questions']?></td>
            </tr>
            <tr>
                <th><?= __("Afgenomen toetsen")?></th>
                <td><?=$school_location['count_tests_taken']?></td>
                <th><?= __("Actieve docenten")?></th>
                <td><?=$school_location['count_active_teachers']?></td>
            </tr>
            <tr>
                <th><?= __("Actieve licenties")?></th>
                <td><?=$school_location['count_active_licenses']?></td>
                <th><?= __("Docenten")?></th>
                <td><?=$school_location['count_teachers']?> / <?=$school_location['number_of_teachers']?></td>
            </tr>
            <tr>
                <th><?= __("Scholengemeenschap")?></th>
                <td>
                    <?
                    if(isset($school_location['school']) && !empty($school_location['school']['name'])) {
                        echo $school_location['school']['name'];
                    }else{
                        ?>
                        <div class="label" style="background: green;"><?= __("Geen, dit is de eindklant")?></div>
                        <?
                    }
                    ?>
                </td>
                <th><?= __("Voorlees licenties")?></th>
                <td><span title="Totaal aantal text2speech licenties voor deze locatie"><?=$school_location['count_text2speech']?></span></td>
            </tr>
            <tr>
                <th><?= __("Brin code")?></th>
                <td><?=$school_location['external_main_code']?></td>
                
                <th><?= __("Locatie brin code")?></th>
                <td><?=$school_location['external_sub_code']?></td>
            </tr>
            <tr>
                <th><?= __("InBrowser toetsen toestaan")?></th>
                <td>
                    <?=$school_location['allow_inbrowser_testing'] ? __("ja") : __("nee") ?>
                    <?php
                        if($school_location['allow_inbrowser_testing']){
                            $allow = 0;
                            $btnClass = 'blue';
                            $btnText = __("Ontnemen");
                        } else {
                            $allow = 1;
                            $btnClass = 'red';
                            $btnText = __("Toestaan");
                        }
                    ?>
                    <span class="btn small <?=$btnClass?>" style="float:right;cursor:pointer" onClick="ChangeAllowInBrowserTesting(<?=$allow?>)"><?=$btnText?></span>
                </td>

                <th>Toetsen in nieuwe speler</th>
                <td>
                    <select id="new_player_access" onchange="ChangeAllowNewPlayerAccess(this.value)">
                        <option value="0">Niet toestaan</option>
                        <option value="1">Beide spelers aanbieden</option>
                        <option value="2">Alleen nieuwe speler</option>
                    </select>
                </td>
            </tr>
        </table>
    </div>
</div>

<div class="block">
    <div class="block-head"><?= __("Schoolbeheerders")?></div>
    <div class="block-content">


        <table class="table table-stiped">
            <tr>
                <th><?= __("Voornaam")?></th>
                <th><?= __("Tussenv")?>.</th>
                <th><?= __("Achternaam")?></th>
                <th<?= __("E-mailadres")?></th>
                <th><?= __("Notities")?></th>
                <th width=30>&nbsp;</th>
            </tr>
            <?
            foreach($managers as $manager) {
                ?>
                <tr>
                    <td><?=$manager['name_first']?></td>
                    <td><?=$manager['name_suffix']?></td>
                    <td><?=$manager['name']?></td>
                    <td><?=$manager['username']?></td>
                    <td><?=$manager['note']?></td>
                    <td class="nopadding">
                        <a href="#" class="btn white pull-right dropblock-left" onclick="Popup.load('/users/edit/<?=getUUID($manager, 'get');?>', 400);">
                            <span class="fa fa-folder-open-o"></span>
                        </a>
                    </td>
                </tr>
                <?
            }
            ?>
        </table>

        <br clear="all" />

        <center>
            <a href="#" class="btn highlight inline-block mt15" onclick="Popup.load('/users/add/managers/location/<?=getUUID($school_location, 'get');?>', 400);">
                <span class="fa fa-plus"></span>
                <?= __("Schoolbeheerder toevoegen")?>
            </a>
        </center>
    </div>
</div>

<div class="block">
    <div class="block-head"><?= __("Licentiepakketten")?></div>
    <div class="block-content">


        <table class="table table-stiped">
            <tr>
                <th><?= __("Startdatum")?></th>
                <th><?= __("Einddatum")?></th>
                <th><?= __("Aantal")?></th>
                <th></th>
            </tr>
            <?
            foreach($school_location['licenses'] as $license) {
                ?>
                <tr>
                    <td><?=date('d-m-Y', strtotime($license['start']))?></td>
                    <td><?=date('d-m-Y', strtotime($license['end']))?></td>
                    <td><?=$license['amount']?></td>
                    <td class="nopadding">
                        <a href="#" class="btn white pull-right dropblock-left" onclick="SchoolLocation.deleteLicense(<?=getUUID($school_location, 'getQuoted');?>,<?=getUUID($license, 'getQuoted');?>);">
                            <span class="fa fa-remove"></span>
                        </a>
                        <a href="#" class="btn white pull-right dropblock-left" onclick="Popup.load('/school_locations/edit_licence/<?=getUUID($school_location, 'get');?>/<?=getUUID($license, 'get');?>', 400);">
                            <span class="fa fa-folder-open-o"></span>
                        </a>
                    </td>
                </tr>
                <?
            }
            ?>
        </table>

        <br clear="all" />

        <center>
            <a href="#" class="btn highlight inline-block mt15" onclick="Popup.load('/school_locations/add_licence/<?=getUUID($school_location, 'get');?>', 400);">
                <span class="fa fa-plus"></span>
                <?= __("Licentiepakket toevoegen")?>
            </a>
        </center>
    </div>
</div>

<div class="block">
    <div class="block-head"><?= __("Contactpersonen")?></div>
    <div class="block-content">
        <?
        foreach($school_location['school_location_contacts'] as $contact) {
            ?>
            <div style="float:left; width:300px; background: #f1f1f1; padding:10px; margin:3px;">
                <table cellpadding="5" cellspacing="0" class="mb10">
                    <tr>
                        <th width="80"><?= __("Type")?></th>
                        <td>
                            <?

                            $type = null;

                            switch($contact['type']) {
                                case 'FINANCE':
                                    echo __("Financieel");
                                    $type = 'financial';
                                    break;
                                case 'TECHNICAL':
                                    echo __("Technisch");
                                    $type = 'technical';
                                    break;
                                case 'IMPLEMENTATION':
                                    echo __("Implementatie");
                                    $type = 'implementation';
                                    break;
                                case 'OTHER':
                                    echo __("Anders");
                                    $type = 'other';
                                    break;
                            }
                            ?>
                        </td>
                    </tr>
                    <tr>
                        <th><?= __("Naam")?></th>
                        <td><?=$contact['contact']['name']?></td>
                    </tr>
                    <tr>
                        <th valign="top"><?= __("Adres")?></th>
                        <td>
                            <?=$contact['contact']['address']?><br />
                            <?=$contact['contact']['postal']?> <?=$contact['contact']['city']?><br />
                            <?=$contact['contact']['country']?>
                        </td>
                    </tr>
                    <tr>
                        <th><?= __("Telefoon")?></th>
                        <td><?=$contact['contact']['phone']?></td>
                    </tr>
                    <tr>
                        <th><?= __("Mobiel")?></th>
                        <td><?=$contact['contact']['mobile']?></td>
                    </tr>
                    <tr>
                        <th><?= __("Notitie")?></th>
                        <td>
                            <div style="width:190px;text-wrap: none; text-overflow: ellipsis; white-space: nowrap; overflow: hidden">
                                <?=empty($contact['contact']['note']) ? '-' : $contact['contact']['note']?>
                            </div>
                        </td>
                    </tr>
                </table>

                <center>
                    <a href="#" class="btn small highlight inline-block"onclick="Popup.load('/contacts/edit/<?=getUUID($contact['contact'], 'get')?>', 400);">
                        <span class="fa fa-edit"></span>
                    </a>
                    <a href="#" class="btn small red inline-block" onclick="Contact.delete('school_location', <?=getUUID($school_location, 'getQuoted');?>, '<?=$type?>', '<?=getUUID($contact['contact'], 'get')?>');">
                        <span class="fa fa-remove"></span>
                    </a>
                </center>
            </div>
            <?
        }
        ?>

        <br clear="all" />

        <center>
            <a href="#" class="btn highlight inline-block mt15" onclick="Popup.load('/contacts/add/school_location/<?=getUUID($school_location, 'get');?>', 400);">
                <span class="fa fa-plus"></span>
                <?= __("Contactpersoon toevoegen")?>
            </a>
        </center>
    </div>
</div>

<div class="block">
    <div class="block-head"><?= __("IP-adressen")?></div>
    <div class="block-content">
        <table class="table table-striped">
            <tr>
                <th><?= __("Adres")?></th>
                <th><?= __("Netmask")?></th>
                <th width="30"></th>
            </tr>
            <?
            foreach($ips as $ip) {
                ?>
                <tr>
                    <td><?=$ip['ip']?></td>
                    <td><?=$ip['netmask']?></td>
                    <td>
                        <a href="#" class="btn white inline-block" onclick="Ip.delete('<?=getUUID($school_location, 'get');?>', '<?=getUUID($ip, 'get');?>');">
                            <span class="fa fa-remove"></span>
                        </a>
                    </td>
                </tr>
                <?
            }
            ?>
        </table>

        <br clear="all" />

        <center>
            <a href="#" class="btn highlight inline-block mt15" onclick="Popup.load('/school_locations/add_ip/<?=getUUID($school_location, 'get');?>', 400);">
                <span class="fa fa-plus"></span>
                <?= __("Ip toevoegen")?>
            </a>
        </center>
    </div>
</div>

<script>
    $('#new_player_access').val("<?= $school_location['allow_new_player_access'] ?>");

    function ChangeAllowInBrowserTesting(allow){
        Loading.show();
        $.post('/school_locations/change_allow_inbrowser_testing/<?=getUUID($school_location, 'get');?>/'+allow,function(){
           Navigation.refresh();
        });
    }
    function ChangeAllowNewPlayerAccess(allow){
        Loading.show();
        $.post('/school_locations/change_allow_new_player_access/<?=getUUID($school_location, 'get');?>/'+allow,function(){
           Loading.hide();
        });
    }
</script>