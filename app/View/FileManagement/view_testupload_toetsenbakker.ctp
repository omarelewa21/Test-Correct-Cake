<style>
    .editable {}
    .editable_elements {
        display:none;
    }
    .editable_view {
        display:block;
    }
</style>
<div id="buttons">
    <a href="#" class="btn white mr2"
        <? if (is_null($return_route)) { ?>
            onclick="Navigation.load('/file_management/testuploads');"
        <? } else { ?>
            onclick="User.goToLaravel('<?= $return_route ?>')"
        <? } ?>
    >
        <span class="fa fa-backward mr5"></span>
        <?= __("Terug")?>
    </a>
    <?php if (isset($file['test']) && !empty($file['test'])) { ?>
        <a href="#" class="btn cta mr2"
           onclick="Questions.enterEmptyCms('<?= $file['test']['uuid'] ?>', 'edit', 'cake.filemanagement')"
        >
            <span class="fa fa-edit mr5"></span>
            <?= __("Toets verder construeren")?>
        </a>
    <?php } else { ?>
        <a href="#" class="btn cta mr2" onclick="Test.createFromFileManagement('<?= $file['uuid'] ?>')">
            <span class="fa fa-plus mr5"></span>
            <?= __("Toets construeren")?>
        </a>
    <?php } ?>
</div>

<h1><?= __("Geüpload toetsbestand")?></h1>

<div class="block">
    <div class="block-head"><?= __("informatie")?></div>
    <div class="block-content">
        <table class="table table-striped form">
            <tr>
                <th width="12%"><?= __("Status")?></th>
                <td width="38%" class="editable" onClick="makeEditable();">
                    <div class="editable_view">
                        <?=__($file['status']['name'])?>
                    </div>
                    <div class="editable_elements">
                        <select name="file_management_status_id" class="editable_input" style="width:100%">
                            <? foreach($file['statuses'] as $status){
                                $selected = "";
                                if($status['id'] == $file['status']['id']){
                                    $selected = "selected = 'selected'";
                                }
                                echo "<option value='".$status['id']."' ".$selected.">".__($status['name'])."</option>";
                            }
                        ?>
                        </select>
                    </div>
                </td>
                <th width="12%"><?= __("Klantcode")?></th>
                <td width="38%">
                    <?= $file['school_location']['customer_code']?>
                </td>
            </tr>
            <tr>
                <th width="12%" rowspan="3" valign="top"><?= __("Notitie")?></th>
                <td width="38%" rowspan="3" valign="top" id='notes' class="editable" onClick="makeEditable();">
                    <div id="notes_div" class='editable_view' title='<?= __("Klik om te wijzigen")?>' style="max-height:60px;overflow:scroll"><?
                        echo (strlen($file['notes']) > 0) ? nl2br($file['notes']) : __("Klik om een notitie toe te toevoegen");
                    ?></div>
                    <div id="notes_edit" class='editable_elements'>
                        <textarea name="notes" class='editable_input' style="height:60px;"><?=$file['notes']?></textarea>
                        <a href="#" onClick="save('<?=getUUID($file, 'get');?>')" class="btn highlight pull-right" style="display:inline-block"><i class="fa fa-save"></i> <?= __("Opslaan")?></a>
                    </div>
                    </td>
                <th width="12%"><?= __("Vak")?></th>
                <td width="38%">
                    <?= $file['subject']['name'] ?? $file['typedetails']['subject'] ?>
                </td>
            </tr>
            <tr>
                <th width="12%"><?= __("Docent")?></th>
                <td width="38%">
                    <?=$file['user']['name_first']?>
                    <?=$file['user']['name_suffix']?>
                    <?=$file['user']['name']?>
                </td>
            </tr>
            <tr>
                <th width="12%"><?= __("Niveau")?></th>
                <td width="38%">
                    <?=$educationLevel?> <?=$file['typedetails']['education_level_year']?>
                </td>
            </tr>
            <tr>
                <th width="12%" valign="top"><?= __("Toetsenbakker")?></th>
                <td width="38%" valign="top" id='test_builder_code' class="editable" onClick="makeEditable();">
                    <div id="test_builder_code_div" class='editable_view' title='<?= $file['test_builder_code'] ?? '-'?>' style="max-height:60px;overflow:scroll">
                        <?= $file['test_builder_code'] ?? '-'?>
                    </div>
                    <div id="test_builder_code_edit" class='editable_elements'>
                        <label>
                            <input type="text" name="test_builder_code" maxlength="4" class='editable_input' value="<?= $file['test_builder_code'] ?? ''?>"/>
                        </label>
                    </div>
                </td>

                <th>
                <?= __("Type")?>
                </th>
                <td><?= $testKind?></td>
            </tr>
            <tr>
                <th width="12%"><?= __("Koppelingen toevoegen")?></th>
                <td width="38%" >
                    <?= __($file['test_upload_additional_options'][$file['typedetails']['test_upload_additional_option']]) ?? __('None')?>
                </td>
                <th>
                <?= __("Naam")?>
                </th>
                <td><?= $file['typedetails']['name']?></td>
            </tr>
            <tr>
                <th width="12%"><?= __("Uitgevers toets")?></th>
                <td width="38%">
                    <?= __(!!$contains_publisher_content ? 'ja' : 'nee')?>
                </td>
                <th></th>
                <td></td>
            </tr>
        </table>
    </div>
</div>

<div class="block">
    <div class="block-head"><?= __("Bestanden")?></div>
    <div class="block-content">


        <table class="table table-stiped">
            <tr>
                <th><?= __("Bestand")?></th>
                <th><?= __("Originele naam")?></th>
            </tr>
            <?
            foreach($file['children'] as $child) {
                ?>
            <tr>
                <td>
                    <a href="file_management/download/<?=getUuid($child,'get')?>/testupload" target="_blank"><?=$child['name']?></a>
                </td>
                <td><?=$child['origname']?></td>
            </tr>
            <?
            }
            ?>
        </table>

    </div>
</div>

<script>
    function makeEditable(){
        jQuery(".editable_view").hide();
        jQuery(".editable_elements").show();
    }

    function handleFileStatusChange(id,action){
        Loading.show();
        var jqxhr = $.post('/file_management/update_status/<?= getUUID($file, 'get');?>',
                {data: {'action' : action}},
                function(response) {
                    Loading.hide();
                    Notify.notify('<?= __("De status is aangepast")?>', 'success');
                    Navigation.refresh();
                }
            )
            .fail(function(){
                Loading.hide();
                Notify.notify('<?= __("Er is iets fout gegaan bij het aanpassen van de status")?>', 'error');
            });
    }

    function save(id,part){
        var data = {};
        jQuery(".editable_input:input").each(function(e){
            data[$(this).attr('name')] = $(this).val();
        });
        var jqxhr = $.post('/file_management/update/<?= getUUID($file, 'get');?>/testupload',
                data,
                function(response) {
                    Loading.hide();
                    Notify.notify('<?= __("De gegevens zijn aangepast")?>', 'success');
                    Navigation.refresh();
                }
            )
            .fail(function(){
                    Loading.hide();
                    Notify.notify('<?= __("Er is iets fout gegaan bij het aanpassen van de gegevens")?>', 'error');
                });
            }
    $('select').select2();
</script>
