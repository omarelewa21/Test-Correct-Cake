<div id="buttons">
    <a href="#" class="btn white mr2" onclick="Navigation.back();">
        <span class="fa fa-backward mr5"></span>
        <?= __("Terug") ?>
    </a>
    <a href="#" class="btn white mr2" onclick="TestTake.copyDirectlink('<?=$take['directLink']?>');">
        <span class="fa fa-clipboard mr5"></span>
        <?= __("Kopieer toetslink")?>
    </a>
    <? if ($take['test_take_status_id'] == 1) { ?>
        <? if (date('d-m-Y', strtotime($take['time_start'])) == date('d-m-Y')) { ?>
            <a href="#" class="btn white mr2
                     <? if(!$take['invigilators_acceptable']){?>
                        toets_afnemen_disabled
                <?}?>
                "
               <? if($take['invigilators_acceptable']){?>
               onclick="TestTake.startTake('<?= $take_id ?>');"
                <?}?>
                <? if(!$take['invigilators_acceptable']){?>
                    onclick="TestTake.noStartTake('<?=$take['invigilators_unacceptable_message']?>');"
                <?}?>

                >
                <span class="fa fa-pencil mr5"></span>
                <?= __("Toets afnemen") ?>
            </a>
        <? } ?>

        <a class="btn white mr2" href="#"
           onclick="Popup.showPdfTestTake('<?= getUUID($take, 'get') ?>', 1000)">
            <span class="fa fa-print mr5"></span>
            <?= __("PDF") ?>
        </a>
        <? if($hasPdfAttachments) { ?>
        <a class="btn white mr2" href="#"
           onclick="Loading.show();Popup.load('/tests/pdf_showPDFAttachment/<?= getUUID($take['test'], 'get') ?>', 1000)">
            <span class="fa fa-print mr5"></span>
            <?= __("Toets pdf bijlagen") ?>
        </a>

        <? } ?>

        <a href="#" class="btn white" onclick="Popup.load('/test_takes/edit/<?= $take_id ?>', 500);">
            <span class="fa fa-edit mr5"></span>
            <?= __("Gegevens wijzigen") ?>
        </a>
    <? } ?>
    <? if ($take['test_take_status_id'] == 6) { ?>
        <a href="#" class="btn white mr2" onclick="Popup.load('/test_takes/add_retake/<?= $take_id ?>', 500);">
            <span class="fa fa-refresh mr5"></span>
            <?= __("Inhaal-toets plannen") ?>
        </a>
    <? } ?>
</div>

<h1><?= __("Geplande toets") ?></h1>
<?php if (isset($take['test_take_code']) && !empty($take['test_take_code'])) { ?>
    <div class="test-take-code-show-wrapper">
        <div class="test-take-code-text-container">
            <h5><?= __('Toetscode') ?></h5>
            <h1><?= $take['test_take_code']['prefix'] ?> <?= chunk_split($take['test_take_code']['code'], 3, ' ') ?></h1>
            <h2 title="<?= __('Kopieer toetslink') ?>" onclick="TestTake.copyDirectlink('<?=$take['directLink']?>');" style="margin-left:1.5rem; color:#041f74; cursor:pointer;">
                <span class="fa fa-clipboard mr5"></span>
            </h2>
        </div>
    </div>
<?php } ?>
<div class="block">
    <div class="block-head"><?= __("Toetsinformatie") ?></div>
    <div class="block-content">
        <table class="table table-striped">
            <tr>
                <th width="12%"><?= __("Toets") ?></th>
                <td width="21%"><?= $take['test']['name'] ?></td>
                <th width="12%"><?= __("Gepland") ?></th>
                <td width="21%"><?= date('d-m-Y', strtotime($take['time_start'])) ?></td>

                <th width="12%"><?= __("Type") ?></th>
                <td width="21%"><?= $take['retake'] == 0 ? __("Normale toets") : __("Inhaal toets") ?></td>
            </tr>
            <tr>

                <th><?= __("Weging") ?></th>
                <td><?= $take['weight'] ?></td>
                <?php if(!empty($take['time_end'])) { ?>
                    <th width="12%"><?= __("Gepland tot") ?></th>
                    <td width="21%"><?= date('d-m-Y', strtotime($take['time_end'])) ?></td>
                <?php } ?>

                <th><?= __("Vak") ?></th>
                <td>
                    <?= $take['test']['subject']['name'] ?>
                </td>
            </tr>
            <tr>
                <th><?= __("Klas(sen)") ?></th>
                <td>
                    <?
                    foreach ($take['school_classes'] as $class) {
                        echo $class['name'] . '<br />';
                    }
                    ?>
                </td>
                <th><?= __("Gepland door") ?></th>
                <td>
                    <?= $take['user']['name_first'] ?>
                    <?= $take['user']['name_suffix'] ?>
                    <?= $take['user']['name'] ?>
                </td>
            </tr>
        </table>
    </div>
</div>

<? if (!empty($take['invigilator_note'])) { ?>
    <div class="block">
        <div class="block-head"><?= __("Notities voor surveillant") ?></div>
        <div class="block-content">
            <?= nl2br($take['invigilator_note']) ?>
        </div>
    </div>
<? } ?>


<div class="block">
    <div class="block-head"><?= __("Informatie") ?></div>
    <div class="block-content">
        <div class="tabs">
            <a href="#" class="btn grey highlight" page="participants" tabs="view_test_take">
                <?= __("Studenten") ?>
            </a>

            <a href="#" class="btn grey" page="invigilators" tabs="view_test_take">
                <?= __("Surveillanten") ?>
            </a>
            <br clear="all"/>
        </div>

        <div page="participants" class="page active" tabs="view_test_take">

        </div>

        <div page="invigilators" class="page" tabs="view_test_take">
            <?
            foreach ($take['invigilator_users'] as $invigilator) {
                $disabled = false;
                if(!is_null($invigilator['deleted_at'])){
                    $disabled = true;
                }
                ?>
                <div class="participant
                            <?
                                if($disabled){?>
                                  disabled_invigilator
                                <?}
                                ?>
                    ">
                    <?= $invigilator['name_first'] ?>
                    <?= $invigilator['name_suffix'] ?>
                    <?= $invigilator['name'] ?>
                </div>
                <?
            }
            ?>

            <br clear="all"/>
            <? if ($take['test_take_status_id'] == 1) { ?>
                <center>
                    <a href="#" class="btn highlight inline-block"
                       onclick="Popup.load('/test_takes/edit/<?= $take_id ?>', 500);">
                        <span class="fa fa-edit"></span>
                        <?= __("Surveillanten wijzigen") ?>
                    </a>
                </center>
            <? } ?>
        </div>
    </div>
</div>

<script type="text/javascript">
    clearTimeout(window.loadParticipants);
    TestTake.loadParticipants('<?=$take_id?>');
    User.surpressInactive = true;

    TestTake.enterWaitingRoomPresenceChannel('<?=Configure::read('pusher-key')?>', '<?= $take_id ?>');
</script>
