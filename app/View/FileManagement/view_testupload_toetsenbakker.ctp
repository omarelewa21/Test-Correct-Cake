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
    <a href="#" class="btn white mr2" onclick="Navigation.load('/file_management/testuploads');">
        <span class="fa fa-backward mr5"></span>
        Terug
    </a>
</div>

<h1>Toetsbestand</h1>

<div class="block">
    <div class="block-head">informatie</div>
    <div class="block-content">
        <table class="table table-striped form">
            <tr>
                <th width="12%">Status</th>
                <td width="38%" class="editable" onClick="makeEditable();">
                    <div class="editable_view">
                        <?=$file['status']['name']?>
                    </div>
                    <div class="editable_elements">
                        <select name="file_management_status_id" class="editable_input" style="width:100%">
                            <? foreach($file['statuses'] as $status){
                                $selected = "";
                                if($status['id'] == $file['status']['id']){
                                    $selected = "selected = 'selected'";
                                }
                                echo "<option value='".$status['id']."' ".$selected.">".$status['name']."</option>";
                            }
                        ?>
                        </select>
                    </div>
                </td>
                <th width="12%">Klantcode</th>
                <td width="38%">
                    <?= $file['school_location']['customer_code']?>
                </td>
            </tr>
            <tr>
                <th width="12%" rowspan="5" valign="top">Notitie</th>
                <td width="38%" rowspan="5" valign="top" id='notes' class="editable" onClick="makeEditable();">
                    <div id="notes_div" class='editable_view' title="Klik om te wijzigen" style="max-height:60px;overflow:scroll"><?
                        echo (strlen($file['notes']) > 0) ? nl2br($file['notes']) : 'Klik om een notitie toe te toevoegen';
                    ?></div>
                    <div id="notes_edit" class='editable_elements'>
                        <textarea name="notes" class='editable_input' style="height:60px;"><?=$file['notes']?></textarea>
                        <a href="#" onClick="save('<?=$file['id']?>')" class="btn highlight pull-right" style="display:inline-block"><i class="fa fa-save"></i> Opslaan</a>
                    </div>
                    </td>
                <th width="12%">Vak</th>
                <td width="38%">
                    <?=$file['typedetails']['subject'] ?>
                </td>
            </tr>
            <tr>
                <th width="12%">Docent</th>
                <td width="38%">
                    <?=$file['user']['name_first']?>
                    <?=$file['user']['name_suffix']?>
                    <?=$file['user']['name']?>
                </td>
            </tr>
            <tr>
                <th width="12%">Niveau</th>
                <td width="38%">
                    <?=$educationLevel?> <?=$file['typedetails']['education_level_year']?>
                </td>
            </tr>
            <tr>
                <th>
                    Type
                </th>
                <td><?= $testKind?></td>
            </tr>
            <tr>
                <th>
                    Naam
                </th>
                <td><?= $file['typedetails']['name']?></td>
            </tr>
        </table>
    </div>
</div>

<div class="block">
    <div class="block-head">Bestanden</div>
    <div class="block-content">


        <table class="table table-stiped">
            <tr>
                <th>Bestand</th>
                <th>Originele naam</th>
            </tr>
            <?
            foreach($file['children'] as $child) {
                ?>
            <tr>
                <td>
                    <a href="file_management/download/<?=$child['id']?>/testupload" target="_blank"><?=$child['name']?></a>
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
        var jqxhr = $.post('/file_management/update_status/<?= $file['id']?>',
                {data: {'action' : action}},
                function(response) {
                    Loading.hide();
                    Notify.notify('De status is aangepast', 'success');
                    Navigation.refresh();
                }
            )
            .fail(function(){
                Loading.hide();
                Notify.notify('Er is iets fout gegaan bij het aanpassen van de status', 'error');
            });
    }

    function save(id,part){
        var data = {};
        jQuery(".editable_input:input").each(function(e){
            data[$(this).attr('name')] = $(this).val();
        });
        var jqxhr = $.post('/file_management/update/<?= $file['id']?>/testupload',
                data,
                function(response) {
                    Loading.hide();
                    Notify.notify('De gegevens zin aangepast', 'success');
                    Navigation.refresh();
                }
            )
            .fail(function(){
                    Loading.hide();
                    Notify.notify('Er is iets fout gegaan bij het aanpassen van de gegevens', 'error');
                });
            }
    $('select').select2();
</script>
