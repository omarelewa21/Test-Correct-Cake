<div id="buttons">
    <? if($isAdministrator): ?>
        <a href="#" class="btn white" onclick="Popup.load('/schools/edit/<?=getUUID($school, 'get');?>', 800);">
            <span class="fa fa-edit mr5"></span>
            Wijzigen
        </a>
        <a href="#" class="btn white" onclick="School.delete(<?=getUUID($school, 'getQuoted');?>);">
            <span class="fa fa-remove mr5"></span>
            Verwijderen
        </a>
    <? endif; ?>
    <a href="#" class="btn white mr2" onclick="Navigation.back();">
        <span class="fa fa-backward mr5"></span>
        Terug
    </a>
</div>

<h1><?=$school['name']?></h1>


<div class="block">
    <div class="block-head">Informatie</div>
    <div class="block-content">
        <table class="table table-striped">
            <tr>
                <th width="15%">Klantcode</th>
                <td width="35%">
                    <?=$school['customer_code']?>
                </td>
                <th width="15%">Accountmanager</th>
                <td>
                    <?=$school['user']['name_first']?>  <?=$school['user']['name_suffix']?> <?=$school['user']['name']?>
                </td>
            </tr>
            <tr>
                <th width="15%">Bezoekadres</th>
                <td width="35%">
                    <?=$school['main_address']?><br />
                    <?=$school['main_postal']?> <?=$school['main_city']?><br />
                    <?=$school['main_country']?>
                </td>
                <th width="15%">Factuuradres</th>
                <td>
                    <?=$school['invoice_address']?><br />
                    <?=$school['invoice_postal']?> <?=$school['invoice_city']?><br />
                    <?=$school['invoice_country']?>
                </td>
            </tr>
            <tr>
                <th>Studenten</th>
                <td><?=$school['count_students']?></td>
                <th>Vraagitems</th>
                <td><?=$school['count_questions']?></td>
            </tr>
            <tr>
                <th>Afgenomen toetsen</th>
                <td><?=$school['count_tests_taken']?></td>
                <th>Actieve docenten</th>
                <td><?=$school['count_active_teachers']?></td>
            </tr>
            <tr>
                <th>Koepelorganisatie</th>
                <td>
                    <?
                    if(isset($school['umbrella_organization']) && !empty($school['umbrella_organization']['name'])) {
                        echo $school['umbrella_organization']['name'];
                    }else{
                        ?>
                        <div class="label" style="background: green;">Geen, dit is de eindklant</div>
                        <?
                    }
                    ?>
                </td>
                <th>Actieve licenties</th>
                <td><?=$school['count_active_licenses']?></td>
            </tr>

            <tr>
                <th>Brin code</th>
                <td><?=$school['external_main_code'];?></td>
                <th>Voorlees licenties</th>
                <td><?=$school['count_text2speech']?></td>
            </tr>

        </table>
    </div>
</div>


<div class="block">
    <div class="block-head">Schoollocaties</div>
    <div class="block-content">
        <table class="table table-striped" id="schoolsTable">
            <thead>
            <tr>
                <th>Schoollocatie</th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            <?
            foreach($school['school_locations'] as $school_location) {
                ?>
                <tr>
                    <td><?=$school_location['name']?></td>
                    <td class="nopadding">
                        <a href="#" class="btn white pull-right dropblock-left" onclick="Navigation.load('/school_locations/view/<?=getUUID($school_location, 'get');?>');">
                            <span class="fa fa-folder-open-o"></span>
                        </a>
                    </td>
                </tr>
            <?
            }
            ?>
            </tbody>
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
                <th width="30">

                </th>
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
                        <a href="#" class="btn white pull-right dropblock-left" onclick="Popup.load('/users/edit/<?=getUUID($manager, 'get')?>', 400);">
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
            <a href="#" class="btn highlight inline-block mt15" onclick="Popup.load('/users/add/managers/school/<?=getUUID($school, 'get');?>', 400);">
                <span class="fa fa-plus"></span>
                Schoolbeheerder toevoegen
            </a>
        </center>
    </div>
</div>


<div class="block">
    <div class="block-head">Contactpersonen</div>
    <div class="block-content">
        <?
        foreach($school['school_contacts'] as $contact) {
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
                </table>

                <? if($isAdministrator): ?>
                    <center>
                        <a href="#" class="btn small highlight inline-block"onclick="Popup.load('/contacts/edit/<?=getUUID($contact['contact'], 'get')?>', 400);">
                            <span class="fa fa-edit"></span>
                        </a>
                        <a href="#" class="btn small red inline-block" onclick="Contact.delete('school', <?=getUUID($school, 'getQuoted');?>, '<?=$type?>', '<?=getUUID($contact['contact'], 'get')?>');">
                            <span class="fa fa-remove"></span>
                        </a>
                    </center>
                <? endif;?>
            </div>
            <?
        }
        ?>

        <br clear="all" />
        <? if($isAdministrator): ?>
            <center>
                <a href="#" class="btn highlight inline-block mt15" onclick="Popup.load('/contacts/add/school/<?=getUUID($school, 'get');?>', 400);">
                    <span class="fa fa-plus"></span>
                    Contactpersoon toevoegen
                </a>
            </center>
        <? endif; ?>
    </div>
</div>