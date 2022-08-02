<div id="buttons">
    <? if($isAdministrator && strtolower($school_location['customer_code']) !== 'tc-tijdelijke-docentaccounts'): ?>
        <a href="#" class="btn white" selid="BtnAddDefaultSectionsAndSubjects" onclick="SchoolLocation.addDefaultSectionsAndSubjects('<?=getUUID($school_location, 'get');?>');">
            <span class="fa fa-edit mr5"></span>
            <?= __("Voeg standaard vakken en secties toe")?>
        </a>
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
                    <?=$school_location['customer_code']?> (<?=$school_location['company_id']?>)
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
                    <span class="btn small <?=$btnClass?>" style="float:right;cursor:pointer" onClick="updateSchoolLocation(<?=$allow?>, 'allow_inbrowser_testing')"><?=$btnText?></span>
                </td>

                <th><?= __("Nieuwe studenten omgeving toestaan")?></th>
                <td>
                    <span><?=$school_location['allow_new_student_environment']?></span>
                    <label class="switch" style="display:flex;">
                        <?= $this->Form->input('allow_new_student_environment',
                            array(
                                    'checked' => $school_location['allow_new_student_environment'],
                                    'label' => false,
                                    'type' => 'checkbox',
                                    'value' => !$school_location['allow_new_student_environment'],
                                    'div' => false,
                                    'style' => 'width:20px;',
                                    'onclick' => 'updateSchoolLocation(this.checked, "allow_new_student_env")'
                            )
                        ) ?>
                        <span class="slider round"></span>
                    </label>
                </td>
            </tr>
            <tr>
                <th><?= __("LVS Koppeling")?></th>
                <td><?= $school_location['lvs_type'] === null ? __("Geen koppeling") : $school_location['lvs_type'] ?></td>

                <th><?= __("LVS Koppeling actief")?></th>
                <td><?= $school_location['lvs_active'] ? __("Ja") : __("Nee") ?></td>
            </tr>
            <tr>
                <th><?= __("SSO Koppeling")?></th>
                <td><?=$school_location['sso_type'] === null ? __("Geen koppeling") : $school_location['sso_type']?></td>

                <th><?= __("SSO Koppeling actief")?></th>
                <td><?=$school_location['sso_active'] ? __("Ja") : __("Nee") ?></td>
            </tr>
            <tr>
                <th><?= __("Examenmateriaal tonen")?></th>
                <td>
                    <label class="switch" style="display:flex;">
                        <?= $this->Form->input('show_exam_material',
                            array(
                                    'checked' => $school_location['show_exam_material'],
                                    'label' => false,
                                    'type' => 'checkbox',
                                    'value' => $school_location['show_exam_material'],
                                    'div' => false,
                                    'style' => 'width:20px;',
                                    'onclick' => 'updateSchoolLocation(this.checked, "show_exam_material")'
                            )
                        ) ?>
                        <span class="slider round"></span>
                    </label>
                </td>

                <th><?= __("Cito snelle starttoets tonen")?></th>
                <td>
                    <label class="switch" style="display:flex;">
                        <?= $this->Form->input('show_cito_quick_test_start',
                            array(
                                    'checked' => $school_location['show_cito_quick_test_start'],
                                    'label' => false,
                                    'type' => 'checkbox',
                                    'value' => $school_location['show_cito_quick_test_start'],
                                    'div' => false,
                                    'style' => 'width:20px;',
                                    'onclick' => 'updateSchoolLocation(this.checked, "show_cito_quick_test_start")'
                            )
                        ) ?>
                        <span class="slider round"></span>
                    </label>
                </td>
            </tr>

            <tr>
                <th><?= __("TestCorrect inhoud tonen")?></th>
                <td>
                    <label class="switch" style="display:flex;">
                        <?= $this->Form->input('show_test_correct_content',
                            array(
                                    'checked' => $school_location['show_test_correct_content'],
                                    'label' => false,
                                    'type' => 'checkbox',
                                    'value' => $school_location['show_test_correct_content'],
                                    'div' => false,
                                    'style' => 'width:20px;',
                                    'onclick' => 'updateSchoolLocation(this.checked, "show_test_correct_content")'
                            )
                        ) ?>
                        <span class="slider round"></span>
                    </label>
                </td>
            </tr>
            <tr>

            <th><?= __("Buiten school locatie rapport houden")?></th>
                <td>
                    <label class="switch" style="display:flex;">
                        <?= $this->Form->input('keep_out_of_school_location_report',
                            array(
                                    'checked' => $school_location['keep_out_of_school_location_report'],
                                    'label' => false,
                                    'type' => 'checkbox',
                                    'value' => $school_location['keep_out_of_school_location_report'],
                                    'div' => false,
                                    'style' => 'width:20px;',
                                    'onclick' => 'updateSchoolLocation(this.checked, "keep_out_report")'
                            )
                        ) ?>
                        <span class="slider round"></span>
                    </label>
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
                    <td>
                        <a href="#" class="btn small red inline-block" onclick="SchoolManager.delete('<?=getUUID($manager, 'get');?>');">
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

    function updateSchoolLocation(allow, info){
        Loading.show();
        $.post(`/school_locations/updateSchoolLocation/<?=getUUID($school_location, 'get');?>/${allow ? 1 : 0}/${info}`,
        function(){
           Navigation.refresh();
        });
    }
</script>