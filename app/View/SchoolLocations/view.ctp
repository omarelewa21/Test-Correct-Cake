<div id="buttons">
    <? if($isAdministrator): ?>
        <a href="#" class="btn white" onclick="Popup.load('/school_locations/edit/<?=getUUID($school_location, 'get');?>', 1100);">
            <span class="fa fa-edit mr5"></span>
            Wijzigen
        </a>
        <a href="#" class="btn white" onclick="SchoolLocation.delete(<?=getUUID($school_location, 'getQuoted');?>, 1);">
            <span class="fa fa-remove mr5"></span>
            Verwijderen
        </a>
    <? endif; ?>
    <a href="#" class="btn white mr2" onclick="Navigation.back();">
        <span class="fa fa-backward mr5"></span>
        Terug
    </a>
</div>

<h1><?=$school_location['name']?></h1>

<div class="block">
    <div class="block-head">Informatie</div>
    <div class="block-content">
        <table class="table table-striped">
            <tr>
                <th width="15%">Klantcode</th>
                <td width="35%">
                    <?=$school_location['customer_code']?>
                </td>
                <th width="15%">Accountmanager</th>
                <td>
                    <?=$school_location['user']['name_first']?>  <?=$school_location['user']['name_suffix']?> <?=$school_location['user']['name']?>
                </td>
            </tr>
            <tr>
                <th width="15%">Bezoekadres</th>
                <td width="35%">
                    <?=$school_location['main_address']?><br />
                    <?=$school_location['main_postal']?> <?=$school_location['main_city']?><br />
                    <?=$school_location['main_country']?>
                </td>
                <th width="15%">Factuuradres</th>
                <td>
                    <?=$school_location['invoice_address']?><br />
                    <?=$school_location['invoice_postal']?> <?=$school_location['invoice_city']?><br />
                    <?=$school_location['invoice_country']?>
                </td>
            </tr>
            <tr>
                <th>Studenten</th>
                <td><?=$school_location['count_students']?> / <?=$school_location['number_of_students']?></td>
                <th>Vraagitems</th>
                <td><?=$school_location['count_questions']?></td>
            </tr>
            <tr>
                <th>Afgenomen toetsen</th>
                <td><?=$school_location['count_tests_taken']?></td>
                <th>Actieve docenten</th>
                <td><?=$school_location['count_active_teachers']?></td>
            </tr>
            <tr>
                <th>Actieve licenties</th>
                <td><?=$school_location['count_active_licenses']?></td>
                <th>Docenten</th>
                <td><?=$school_location['count_teachers']?> / <?=$school_location['number_of_teachers']?></td>
            </tr>
            <tr>
                <th>Scholengemeenschap</th>
                <td>
                    <?
                    if(isset($school_location['school']) && !empty($school_location['school']['name'])) {
                        echo $school_location['school']['name'];
                    }else{
                        ?>
                        <div class="label" style="background: green;">Geen, dit is de eindklant</div>
                        <?
                    }
                    ?>
                </td>
                <th>Voorlees licenties</th>
                <td><span title="Totaal aantal text2speech licenties voor deze locatie"><?=$school_location['count_text2speech']?></span></td>
            </tr>
            <tr>
                <th>Brin code</th>
                <td><?=$school_location['external_main_code']?></td>
                
                <th>Locatie brin code</th>
                <td><?=$school_location['external_sub_code']?></td>
            </tr>
            <tr>
                <th>InBrowser toetsen toestaan</th>
                <td>
                    <?=$school_location['allow_inbrowser_testing'] ? 'ja' : 'nee' ?>
                    <?php
                        if($school_location['allow_inbrowser_testing']){
                            $allow = 0;
                            $btnClass = 'blue';
                            $btnText = 'Ontnemen';
                        } else {
                            $allow = 1;
                            $btnClass = 'red';
                            $btnText = 'Toestaan';
                        }
                    ?>
                    <span class="btn small <?=$btnClass?>" style="float:right;cursor:pointer" onClick="ChangeAllowInBrowserTesting(<?=$allow?>)"><?=$btnText?></span>
                </td>

                <th>Toetsen in nieuwe speler</th>
                <td>
                    <?=$school_location['allow_new_player_access'] ? 'ja' : 'nee' ?>
                    <?php
                    if($school_location['allow_new_player_access']){
                        $allow = 0;
                        $btnClass = 'blue';
                        $btnText = 'Ontnemen';
                    } else {
                        $allow = 1;
                        $btnClass = 'red';
                        $btnText = 'Toestaan';
                    }
                    ?>
                    <span class="btn small <?=$btnClass?>" style="float:right;cursor:pointer" onClick="ChangeAllowNewPlayerAccess(<?=$allow?>)"><?=$btnText?></span>
                </td>
            </tr>
        </table>
    </div>
</div>

<div class="block">
    <div class="block-head">Schoolbeheerders</div>
    <div class="block-content">


        <table class="table table-stiped">
            <tr>
                <th>Voornaam</th>
                <th>Tussenv.</th>
                <th>Achternaam</th>
                <th>E-mailadres</th>
                <th>Notities</th>
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
                Schoolbeheerder toevoegen
            </a>
        </center>
    </div>
</div>

<div class="block">
    <div class="block-head">Licentiepakketten</div>
    <div class="block-content">


        <table class="table table-stiped">
            <tr>
                <th>Startdatum</th>
                <th>Einddatum</th>
                <th>Aantal</th>
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
                Licentiepakket toevoegen
            </a>
        </center>
    </div>
</div>

<div class="block">
    <div class="block-head">Contactpersonen</div>
    <div class="block-content">
        <?
        foreach($school_location['school_location_contacts'] as $contact) {
            ?>
            <div style="float:left; width:300px; background: #f1f1f1; padding:10px; margin:3px;">
                <table cellpadding="5" cellspacing="0" class="mb10">
                    <tr>
                        <th width="80">Type</th>
                        <td>
                            <?

                            $type = null;

                            switch($contact['type']) {
                                case 'FINANCE':
                                    echo 'Financieel';
                                    $type = 'financial';
                                    break;
                                case 'TECHNICAL':
                                    echo 'Technisch';
                                    $type = 'technical';
                                    break;
                                case 'IMPLEMENTATION':
                                    echo 'Implementatie';
                                    $type = 'implementation';
                                    break;
                                case 'OTHER':
                                    echo 'Anders';
                                    $type = 'other';
                                    break;
                            }
                            ?>
                        </td>
                    </tr>
                    <tr>
                        <th>Naam</th>
                        <td><?=$contact['contact']['name']?></td>
                    </tr>
                    <tr>
                        <th valign="top">Adres</th>
                        <td>
                            <?=$contact['contact']['address']?><br />
                            <?=$contact['contact']['postal']?> <?=$contact['contact']['city']?><br />
                            <?=$contact['contact']['country']?>
                        </td>
                    </tr>
                    <tr>
                        <th>Telefoon</th>
                        <td><?=$contact['contact']['phone']?></td>
                    </tr>
                    <tr>
                        <th>Mobiel</th>
                        <td><?=$contact['contact']['mobile']?></td>
                    </tr>
                    <tr>
                        <th>Notitie</th>
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
                Contactpersoon toevoegen
            </a>
        </center>
    </div>
</div>

<div class="block">
    <div class="block-head">IP-adressen</div>
    <div class="block-content">
        <table class="table table-striped">
            <tr>
                <th>Adres</th>
                <th>Netmask</th>
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
                Ip toevoegen
            </a>
        </center>
    </div>
</div>

<script>
    function ChangeAllowInBrowserTesting(allow){
        Loading.show();
        $.post('/school_locations/change_allow_inbrowser_testing/<?=getUUID($school_location, 'get');?>/'+allow,function(){
           Navigation.refresh();
        });
    }
    function ChangeAllowNewPlayerAccess(allow){
        Loading.show();
        $.post('/school_locations/change_allow_new_player_access/<?=getUUID($school_location, 'get');?>/'+allow,function(){
           Navigation.refresh();
        });
    }
</script>