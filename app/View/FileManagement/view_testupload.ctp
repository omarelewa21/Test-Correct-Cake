<style>
    .editable {}
    .editable_elements {
        display:none;
    }
    .editable_view {
        display:block;
    }

    .color-indicator {
        cursor: pointer;
    }
    .colorcode-selector .color-indicator {
        border-width:2px;
        border-color:transparent;
    }
    .color-indicator-active {
        border-color:red !important;
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
                <th width="12%"><?= __("School locatie")?></th>
                <td width="38%">
                    <?= $file['school_location']['name']?>
                </td>
            </tr>
            <tr>
                <th width="12%"><?= __("Behandelaar")?></th>
                <td width="38%" onClick="makeEditable()">
                    <div class="editable_view colorcode-selector">
                        <?php
                        if(isset($file['handler']) && $file['handler']){
                            echo sprintf('%s %s %s',$file['handler']['name_first'], $file['handler']['name_suffix'],$file['handler']['name']);
                        } else {
                            echo '-';
                        }
                        ?>
                    </div>
                    <div class="editable_elements">
                        <select name="handledby" class="editable_input" style="width:100%">
                            <option value="">-</option>
                            <? foreach($teachers as $teacher){
                                    $name = sprintf('%s %s %s', $teacher['name_first'], $teacher['name_suffix'], $teacher['name']);
                                    $selected = "";
                                    if($teacher['id'] == $file['handledby']){
                                        $selected = "selected = 'selected'";
                                    }
                                    echo "<option value='".getUUID($teacher,'get')."' ".$selected.">".$name."</option>";
                            }
                            ?>

                        </select>
                    </div>
                </td>
                <th width="12%"><?= __("Code")?></th>
                <td width="38%">
                    <?= $file['school_location']['customer_code']?>
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

                <th width="12%"><?= __("Vak")?> (<?= __("Volgens docent") ?>)</th>
                <td width="38%">
                    <?=$file['typedetails']['subject'] ?>
                </td>
            </tr>
            <tr>
                <th width="12%" rowspan="2" valign="top"><?= __("Notitie")?></th>
                <td width="38%" rowspan="2" valign="top" id='notes' class="editable" onClick="makeEditable();">
                    <div id="notes_div" class='editable_view' title='<?= __("Klik om te wijzigen")?>' style="max-height:60px;overflow:scroll"><?
                        echo (strlen($file['notes']) > 0) ? nl2br($file['notes']) : __("Klik om een notitie toe te toevoegen");
                        ?></div>
                    <div id="notes_edit" class='editable_elements'>
                        <textarea name="notes" class='editable_input' style="height:60px;"><?=$file['notes']?></textarea>
                    </div>
                </td>
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
                <th width="12%"><?= __("Toevoegingen")?></th>
                <td width="38%" class="editable" onClick="makeEditable();">
                    <div class="editable_view">
                        <?= __($file['test_upload_additional_options'][$file['typedetails']['test_upload_additional_option']]) ?? __('None')?>
                    </div>
                    <div class="editable_elements">
                        <select name="test_upload_additional_option" class="editable_input" style="width:100%">
                            <?php foreach($file['test_upload_additional_options'] as $key => $option) { ?>
                                <option value="<?= $key ?>" <? if($file['typedetails']['test_upload_additional_option'] == $key) { echo 'selected'; } ?>><?= __($option) ?></option>
                            <?php } ?>
                        </select>
                        <a href="#" onClick="save('<?=getUUID($file, 'get');?>')" class="btn highlight pull-right mt2 mr5" style="display:inline-block"><i class="fa fa-save"></i> <?= __("Opslaan")?></a>
                    </div>
                </td>
                <th>
                <?= __("Type")?>
                </th>
                <td><?= $testKind?></td>
            </tr>
            <tr>
                <th width="12%"></th>
                <td width="38%"></td>
                <th>
                <?= __("Naam toets")?>
                </th>
                <td><?= $file['typedetails']['name']?></td>
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
                    <a href="file_management/download/<?=getUUID($child, 'get');?>/testupload"><?=$child['name']?></a>
                </td>
                <td><?=$child['origname']?></td>
            </tr>
            <?
            }
            ?>
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
                <th><?= __("E-mailadres")?></th>
            </tr>
            <?
            foreach($schoolbeheerders as $manager) {
                ?>
            <tr>
                <td><?=$manager['name_first']?></td>
                <td><?=$manager['name_suffix']?></td>
                <td><?=$manager['name']?></td>
                <td><?=$manager['username']?></td>
            </tr>
            <?
            }
            ?>
        </table>

    </div>
</div>

<script>
    function changeColorcode(e){
        jQuery(".color-indicator-active").removeClass("color-indicator-active");
        jQuery(e).addClass('color-indicator-active');
        jQuery("#colorcodeHiddenInput").val(jQuery(e).data('color'));
    }

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
        var jqxhr = $.post('/file_management/update/<?= getUUID($file, 'get');?>/testupload', data)
            .done(function(response) {
                if (response == 1) {
                    Notify.notify('<?= __("De gegevens zijn aangepast")?>', 'success');
                    Navigation.refresh();
                } else {
                    Notify.notify('<?= __("Er is iets fout gegaan bij het aanpassen van de gegevens")?>', 'error');
                }
            })
            .always(() => {
                Loading.hide();
            });
    }
    $('select').select2();
</script>
