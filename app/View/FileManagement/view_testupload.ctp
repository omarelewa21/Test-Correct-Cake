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
                <th width="12%">School locatie</th>
                <td width="38%">
                    <?= $file['school_location']['name']?>
                </td>
            </tr>
            <tr>
                <th width="12%">Behandelaar</th>
                <td width="38%" onClick="makeEditable()">
                    <div class="editable_view colorcode-selector">
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
                    </div>
                    <div class="editable_elements">
                        <select name="handledby" class="editable_input" style="width:100%">
                            <option value="">-</option>
                            <? foreach($teachers as $id => $name){
                                                $selected = "";
                                                if($id == $file['handledby']){
                                                    $selected = "selected = 'selected'";
                                                }
                                                echo "<option value='".$id."' ".$selected.">".$name."</option>";
                            }
                            ?>

                        </select>
                    </div>
                </td>
                <th width="12%">Code</th>
                <td width="38%">
                    <?= $file['school_location']['customer_code']?>
                </td>
            </tr>
            <tr>
                <th width="12%" valign="top">Kleurcode</th>
                <td width="38%" valign="top" class="editable" onClick="makeEditable();">
                    <div id="colorcode_div" class='editable_view' title="Klik om te wijzigen" style="max-height:60px;overflow:scroll"><?
                        echo (strlen($file['typedetails']['colorcode']) > 0) ? sprintf("<span class='mr5 color-indicator %s'></span>",$file['typedetails']['colorcode']) : 'Klik om een kleurcode te plaatsen';
                    ?></div>
                    <div id="colorcodes_edit" class='editable_elements colorcode-selector'>
                        <input type="hidden" class="editable_input" id="colorcodeHiddenInput" name="colorcode" value="<?php echo $file['typedetails']['colorcode']?>">
                        <?php
                            for($i=1;$i<=40;$i++){
                                $active = 'colorcode-'.$i == $file['colorcode'] ? 'color-indicator-active' : '';
                                echo "<span class='pull-left mr2 ml2 mb2 mt2 color-indicator colorcode colorcode-".$i." ".$active."' onclick='changeColorcode(this)' data-color='colorcode-".$i."'></span>";
                            }
                        ?>
                    </div>
                    </td>
                <th width="12%">Vak</th>
                <td width="38%">
                    <?=$file['typedetails']['subject'] ?>
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
                    </div>
                </td>
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
                <th width="12%" rowspan="2" valign="top">Uitnodiging</th>
                <td width="38%" rowspan="2" valign="top" id='notes' class="editable" onClick="makeEditable();">
                    <div id="notes_div" class='editable_view' title="Klik om uit te nodigen" style="max-height:60px;overflow:scroll"><?
                        echo (isset($file['typedetails']['invite']) && strlen($file['typedetails']['invite']) > 0) ? $file['typedetails']['invite'].'<br /> uitgenodigd op '.$this->Time->format($file['typedetails']['invited_at'],'%e %b \'%y om %H:%M', true, 'Europe/Amsterdam') : 'Klik om uit te nodigen';
                        ?></div>
                    <div id="notes_edit" class='editable_elements'>
                        <input type="text" name="invite" class='editable_input' value="<?=isset($file['typedetails']['invite']) ? $file['typedetails']['invite'] : ''?>">
                        <a href="#" onClick="save('<?=$file['id']?>')" class="btn highlight pull-right mt2 mr5" style="display:inline-block"><i class="fa fa-save"></i> Opslaan</a>
                    </div>
                </td>
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
                    <a href="file_management/download/<?=$child['id']?>/testupload"><?=$child['name']?></a>
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
