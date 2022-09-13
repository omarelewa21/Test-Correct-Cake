<?

$normalization = true;
foreach($participants as $participant) {
if($participant['answer_require_rating'] > 0) {
$normalization = false;
}
}
$rating = empty($take['ppp']) && empty($take['epp']) && empty($take['wanted_average']) && empty($take['n_term']) ? false : true;
?>
<div id="buttons" class="contains_dropdown">
    <a href="#" class="btn white mr2" onclick="Navigation.load('/test_takes/to_rate');">
        <span class="fa fa-backward mr5"></span>
        <?= __("Terug")?>
    </a>

    <? if($take['test_take_status_id'] == 8) { ?>
        <div id="nav_pdf_button">
            <a href="#" class="btn white mr2 action ui-dropper" data-drop="select_drop">
                <span class="fa fa-print mr5"></span>
                <?= __("PDF") ?>
            </a>
            <ul id="select_drop" class="ui-dropdown action-dropdown">
                <li><a href="#" onclick="Popup.showPdfTestTake('<?= $take_id ?>');"><?=  __("Toets") ?></a></li>
                <? if($hasPdfAttachments) { ?>
                <li><a href="#" onclick="Loading.show();Popup.load('/tests/pdf_showPDFAttachment/<?= getUUID($take['test'], 'get') ?>', 1000)"><?=  __("Toets pdf bijlagen") ?></a></li>
                <? } ?>
                <li><a href="#" onclick="Popup.showPreviewAnswerModelTest('<?= $test_uuid ?>');"><?=  __("Antwoord model") ?></a></li>
                <li><a href="#" onclick="Popup.showPreviewTestTakeAnswers('<?= $take_id ?>')"><?= __("Antwoorden") ?></a></li>
            </ul>
        </div>
    <?
    if(!empty($take['show_results']) && time() < strtotime($take['show_results'])) {
    ?>
    <a href="#" class="btn white mr2" onclick="TestTake.closeShowResults('<?= $take_id ?>');">
        <span class="fa fa-eye mr5"></span>
        <?= __("Dichtzetten")?>
    </a>
    <? }else{ ?>
    <a href="#" class="btn white mr2" onclick="Popup.load('/test_takes/update_show_results/<?= $take_id ?>', 420);">
        <span class="fa fa-eye mr5"></span>
        <?= __("Openzetten")?>
    </a>
    <? }?>
    <?php
    if($take['writing_assignments_count'] > 0){
    ?>
        <a href="#" class="btn white mr2" onclick="Navigation.load('/test_takes/rate_teacher_participant/<?=$take_id?>');">
        <span class="fa fa-hourglass-1 mr5"></span>
        <?= __("Nakijken")?>
    </a>
    <?php } else { ?>
    <a href="#" class="btn white mr2" onclick="Popup.load('/test_takes/start_rate_popup/<?= $take_id ?>', 610);">
        <span class="fa fa-hourglass-1 mr5"></span>
        <?= __("Nakijken")?>
    </a>
    <?php } ?>
    <? if($normalization && $take['is_rtti_test_take'] == 0){ ?>
    <a href="#" class="btn white mr2" onclick="Navigation.load('/test_takes/normalization/<?= $take_id ?>');">
        <span class="fa fa-hourglass-2 mr5"></span>
        <?= __("Normeren")?>
    </a>
    <? } ?>
    <? if($normalization && $take['is_rtti_test_take'] == 0) {?>
    <a href='/test_takes/csv_export/<?= $take_id ?>' target="_blank" class="btn white mr2">
        <span class="fa fa-download mr5"></span>
        <?= __("RTTI-Export")?>
    </a>
    <? } ?>
    <? if($rating) { ?>
    <a href="#" class="btn white mr2" onclick="Navigation.load('/test_takes/set_final_rates/<?= $take_id ?>');">
        <span class="fa fa-hourglass-3 mr5"></span>
        <?= __("Becijferen")?>
    </a>
    <? } ?>
    <a href="#" class="btn white mr2" onclick="Navigation.load('/test_takes/add_retake/<?= $take_id ?>');">
        <span class="fa fa-refresh mr5"></span>
        <?= __("Inhaal-toets plannen")?>
    </a>

    <?php if($normalization && $take['is_rtti_test_take'] == 1): ?>
        <a href="#" onclick="updateRTTI('<?=$take_id?>')" title="Exporteren naar RTTI-Online" class="btn white mr2">Exporteren naar RTTI-Online</a>
    <?php endif; ?>

    <? } ?>

</div>

<h1><?= __("Na te kijken toets")?></h1>
<?php if(isset($take['test_take_code']) && !empty($take['test_take_code']) && $take['guest_accounts']) {?>
    <div class="test-take-code-show-wrapper">
        <div class="test-take-code-text-container">
            <h5>Student inlogtoetscode</h5>
            <h1><?= $take['test_take_code']['prefix'] ?> <?= chunk_split($take['test_take_code']['code'], 3, ' ') ?></h1>
        </div>
    </div>
<?php } ?>
<div class="block">
    <div class="block-head"><?= __("Toetsinformatie")?></div>
    <div class="block-content">
        <table class="table table-striped">
            <tr>
                <th width="12%"><?= __("Toets")?></th>
                <td width="21%"><?= $take['test']['name'] ?></td>
                <th width="12%"><?= __("Gepland")?></th>
                <td width="21%"><?= date('d-m-Y', strtotime($take['time_start'])) ?></td>
                <th width="12%"><?= __("Type")?></th>
                <td width="21%"><?= $take['retake'] == 0 ? __("Normale toets") : __("Inhaal toets") ?></td>
            </tr>
            <tr>

                <th><?= __("Weging")?></th>
                <td><?= $take['weight'] ?></td>
                <th><?= __("Gepland door")?></th>
                <td>
                    <?= $take['user']['name_first'] ?>
                    <?= $take['user']['name_suffix'] ?>
                    <?= $take['user']['name'] ?>
                </td>
                <th><?= __("Vak")?></th>
                <td>
                    <?= $take['test']['subject']['name'] ?>
                </td>
            </tr>
            <tr>
                <th><?= __("Klas(sen)")?></th>
                <td>
                    <?
                    foreach($take['school_classes'] as $class) {
                    echo $class['name'] . '<br />';
                    }
                    ?>
                </td>
                <?php if($take['is_rtti_test_take'] == 1): ?>
                    <th nowrap><?= __("Laatste RTTI export")?></th>
                    <td nowrap><?= $take['exported_to_rtti_formated'] ?></td>
                <?php endif; ?>
            </tr>
        </table>
    </div>
</div>


<div class="block">
    <div class="block-head"><?= __("Studenten")?></div>
    <div class="block-content">
        <table class="table table-striped">
            <tr>
                <th><?= __("Student")?></th>
                <th width="200"><?= __("Vragen na te kijken")?></th>
                <th width="120"><?= __("Score / Max")?></th>
                <th width="120"><?= __("Veroorzaakte discrepanties")?></th>
                <th width="60"><?= __("Notities")?></th>
                <th width="130"></th>
            </tr>
            <?
            foreach($participants as $participant) {
            ?>
            <tr>
                <td>
                    <?= $participant['user']['name_first'] ?>
                    <?= $participant['user']['name_suffix'] ?>
                    <?= $participant['user']['name'] ?>
                </td>
                <td>
                    <?= $participant['answer_require_rating'] ?>
                </td>
                <td>
                    <?= $participant['score'] ?> / <?= $participant['max_score'] ?>
                </td>
                <td><?= $normalization ? $participant['abnormalities'] : '-' ?></td>
                <td>
                    <?= empty($participant['invigilator_note']) ? __("Nee") : __("Ja") ?>
                </td>
                <td class="nopadding" width="100">
                    <a href="#" class="btn white pull-right" onclick="Navigation.load('/test_takes/view_results/<?= getUUID($take, 'get'); ?>/<?= getUUID($participant, 'get'); ?>');">
                        <span class="fa fa-folder-open-o"></span>
                    </a>
                    <a href="#" class="btn white pull-right" onclick="Popup.load('/test_takes/rated_info/<?= getUUID($take, 'get'); ?>/<?= getUUID($participant, 'get'); ?>', 500);">
                        <span class="fa fa-info-circle"></span>
                    </a>
                    <a href="#" class="btn white pull-right" onclick="Popup.load('/messages/send/<?= getUUID($participant['user'], 'get'); ?>', 500);">
                        <span class="fa fa-envelope-o"></span>
                    </a>
                </td>
            </tr>
            <?
            }
            ?>
        </table>
    </div>
</div>

<?php
if($isTeacher && $analysis && count($analysis)){
 echo $this->element("test_take_attainment_analysis",['analysis' => $analysis, 'test_take_id' => getUUID($take,'get')]);
}
?>

<script type="text/javascript">
    function updateRTTI(take_id)
    {
        var url = '/test_takes/export_to_rtti/' + take_id;
        $.post(url, function(response, status){
            
            // console.log(response);
            
            response = JSON.parse(response);

            if(response["status"] == 1) {
                Notify.notify('<?= __("Toets met succes naar RTTI verzonden.")?>',3000);
            } else {
                Notify.notify(response['data'], 'error',3000);
            }

            Navigation.refresh();
        });
    }
</script>

<script>
    Menu.initDropdownMenuButton();
</script>
