<div id="buttons">
    <? if($isAdministrator): ?>
        <a href="#" class="btn white" onclick="Popup.load('/umbrella_organisations/edit/<?=getUUID($organisation, 'get');?>', 800);">
            <span class="fa fa-edit mr5"></span>
            <?= __("Wijzigen")?>
        </a>
        <a href="#" class="btn white" onclick="Organisation.delete(<?=getUUID($organisation, 'getQuoted');?>);">
            <span class="fa fa-remove mr5"></span>
            <?= __("Verwijderen")?>
        </a>
    <? endif; ?>
    <a href="#" class="btn white mr2" onclick="Navigation.back();">
        <span class="fa fa-backward mr5"></span>
        <?= __("Terug")?>
    </a>
</div>

<h1><?=$organisation['name']?></h1>
<div class="block">
    <div class="block-head"><?= __("Informatie")?></div>
    <div class="block-content">
        <table class="table table-striped">
            <tr>
                <th width="15%"><?= __("Klantcode")?></th>
                <td width="35%">
                    <?=$organisation['customer_code']?>
                </td>
                <th width="15%"><?= __("Accountmanager")?></th>
                <td>
                    <?=$organisation['user']['name_first']?>  <?=$organisation['user']['name_suffix']?> <?=$organisation['user']['name']?>
                </td>
            </tr>
            <tr>
                <th width="15%"><?= __("Bezoekadres")?></th>
                <td width="35%">
                    <?=$organisation['main_address']?><br />
                    <?=$organisation['main_postal']?> <?=$organisation['main_city']?><br />
                    <?=$organisation['main_country']?>
                </td>
                <th width="15%"><?= __("Factuuradres")?></th>
                <td>
                    <?=$organisation['invoice_address']?><br />
                    <?=$organisation['invoice_postal']?> <?=$organisation['invoice_city']?><br />
                    <?=$organisation['invoice_country']?>
                </td>
            </tr>
            <tr>
                <th><?= __("Studenten")?></th>
                <td><?=$organisation['count_students']?></td>
                <th><?= __("Vraagitems")?></th>
                <td><?=$organisation['count_questions']?></td>
            </tr>
            <tr>
                <th><?= __("Afgenomen toetsen")?></th>
                <td><?=$organisation['count_tests_taken']?></td>
                <th><?= __("Actieve docenten")?></th>
                <td><?=$organisation['count_active_teachers']?></td>
            </tr>
            <tr>
                <th><?= __("Actieve licenties")?></th>
                <td ><?=$organisation['count_active_licenses']?></td>
                
                <th><?= __("Brin code")?></th>
                <td><?=$organisation['external_main_code']?></td>
            </tr>
        </table>
    </div>
</div>

<div class="block">
    <div class="block-head"><?= __("Scholengemeenschappen")?></div>
    <div class="block-content">
        <table class="table table-striped" id="schoolsTable">
            <thead>
            <tr>
                <th><?= __("School")?></th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            <?
            foreach($organisation['schools'] as $school) {
                ?>
                <tr>
                    <td><?=$school['name']?></td>
                    <td class="nopadding">
                        <a href="#" class="btn white pull-right dropblock-left" onclick="Navigation.load('/schools/view/<?=getUUID($school, 'get');?>');">
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
    <div class="block-head"><?= __("Contactpersonen")?></div>
    <div class="block-content">
        <?
        foreach($organisation['umbrella_organization_contacts'] as $contact) {
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
                </table>

                <? if($isAdministrator): ?>
                    <center>
                        <a href="#" class="btn small highlight inline-block"onclick="Popup.load('/contacts/edit/<?=getUUID($contact['contact'], 'get')?>', 400);">
                            <span class="fa fa-edit"></span>
                        </a>
                        <a href="#" class="btn small red inline-block" onclick="Contact.delete('umbrella_organization', <?=getUUID($organisation, 'getQuoted');?>, '<?=$type?>', '<?=getUUID($contact['contact'], 'get')?>');">
                            <span class="fa fa-remove"></span>
                        </a>
                    </center>
                <? endif; ?>
            </div>
            <?
        }
        ?>

        <br clear="all" />
        <? if($isAdministrator): ?>
            <center>
                <a href="#" class="btn highlight inline-block mt15" onclick="Popup.load('/contacts/add/umbrella_organization/<?=getUUID($organisation, 'get');?>', 400);">
                    <span class="fa fa-plus"></span>
                    <?= __("Contactpersoon toevoegen")?>
                </a>
            </center>
        <? endif; ?>
    </div>
</div>