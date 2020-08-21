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
    <a href="#" class="btn white mr2" onclick="Navigation.load('/file_management/classuploads');">
        <span class="fa fa-backward mr5"></span>
        Terug
    </a>
    <?php
        $options = [];
        switch($file['file_management_status_id']){
            case 1:
                $options[] = ['action' => 'claim', 'text' => 'In beheer nemen','class' => 'highlight'];
            break;
            case 2:
                $options[] = ['action' => 'claim', 'text' => 'Overnemen', 'class' => 'fa-heart-o'];
                $options[] = ['action' => 'close', 'text' => 'Sluiten', 'class' => 'fa-sign-out'];
            break;
            default: // 'closed' or 'declined'
                $options[] = ['action' => 'claim', 'text' => 'Heropenen', 'class' => 'fa-heart-o'];
            break;
        }
        foreach($options as $option){
            ?>
            <a href="#" class="btn white mr2" onclick="handleFileStatusChange('<?=$file['id']?>','<?=$option['action']?>');">
                <span class="fa <?=$option['class']?> mr5"></span>
                <?=$option['text']?>
            </a>
            <?php
        }
        ?>
</div>

<h1>Bestand</h1>

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
                        <select name="file_management_status_id" class="editable_input">
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
                <th width="12%">School locatie</th>
                <td width="38%">
                    <?= $file['school_location']['name']?>
                </td>
            </tr>
            <tr>
                <th width="12%">Behandelaar</th>
                <td width="38%">
                    <?php
                    if(isset($file['handler']) && $file['handler']){
                        echo sprintf('%s %s %s',$file['handler']['name_first'], $file['handler']['name_suffix'],$file['handler']['name']);
                    }
                    else {
                        ?>
                    -
                    <?php
                    }
                    ?>
                </td>
                <th width="12%">Code</th>
                <td width="38%">
                    <?= $file['school_location']['customer_code']?>
                </td>
            </tr>
            <tr>
                <th width="12%" rowspan="2" valign="top">Notitie</th>
                <td width="38%" rowspan="2" valign="top" id='notes' class="editable" onClick="makeEditable();">
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
                <th width="12%"></th>
                <td width="38%">

                </td>
                <th width="12%">Klas</th>
                <td width="38%">
                    <?=$file['typedetails']['class'] ?> (<?=$educationLevel?> <?=$file['typedetails']['education_level_year']?>)
                </td>
            </tr>
            <tr>
                <th>
                   File
                </th>
                <td>
                    <a href="file_management/download/<?=$file['id']?>" target="_blank"><?=$file['name']?> (<?=$file['origname']?>)</a>
                </td>
                <th>
                    Stamklas
                </th>
                <td><?= $file['typedetails']['is_main_school_class'] ? 'Ja' : 'Nee' ?></td>
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
        var jqxhr = $.post('/file_management/update/<?= $file['id']?>/classupload',
                data,
                function(response) {
                    Loading.hide();
                    Notify.notify('De gegevens zijn aangepast', 'success');
                    Navigation.refresh();
                }
            )
            .fail(function(){
                    Loading.hide();
                    Notify.notify('Er is iets fout gegaan bij het aanpassen van de gegevens', 'error');
                });
            }

</script>
