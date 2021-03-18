<div id="buttons">
    <a href="#" class="btn white mr2" onclick="Navigation.back();">
        <span class="fa fa-backward mr5"></span>
        <?= __("Terug")?>
    </a>
    <? if($take['test_take_status_id'] == 1) { ?>
        <? if(date('d-m-Y', strtotime($take['time_start'])) == date('d-m-Y')) {?>
            <a href="#" class="btn white mr2" onclick="TestTake.startTake('<?=$take_id?>');">
                <span class="fa fa-pencil mr5"></span>
                <?= __("Toets afnemen")?>
            </a>
        <? } ?>

        <a class="btn white mr2" href="#" onclick="Popup.load('/tests/pdf_showPDFAttachment/<?=getUUID($take['test'], 'get')?>', 1000)">
            <span class="fa fa-print mr5"></span>
            <?= __("PDF")?>
        </a>

        <a href="#" class="btn white" onclick="Popup.load('/test_takes/edit/<?=$take_id?>', 500);">
            <span class="fa fa-edit mr5"></span>
            <?= __("Gegevens wijzigen")?>
        </a>
    <? } ?>
    <? if($take['test_take_status_id'] == 6) { ?>
        <a href="#" class="btn white mr2" onclick="Popup.load('/test_takes/add_retake/<?=$take_id?>', 500);">
            <span class="fa fa-refresh mr5"></span>
            <?= __("Inhaal-toets plannen")?>
        </a>
    <? } ?>
</div>

<h1><?= __("Geplande toets")?></h1>

<div class="block">
    <div class="block-head"><?= __("Toets informatie")?></div>
    <div class="block-content">
        <table class="table table-striped">
            <tr>
                <th width="12%"><?= __("Toets")?></th>
                <td width="21%"><?=$take['test']['name']?></td>
                <th width="12%"><?= __("Gepland")?></th>
                <td width="21%"><?=date('d-m-Y', strtotime($take['time_start']))?></td>
                <th width="12%"><?= __("Type")?></th>
                <td width="21%"><?=$take['retake'] == 0 ? __("Normale toets") : __("Inhaal toets")?></td>
            </tr>
            <tr>

                <th><?= __("Weging")?></th>
                <td><?=$take['weight']?></td>
                <th><?= __("Gepland door")?></th>
                <td>
                    <?=$take['user']['name_first']?>
                    <?=$take['user']['name_suffix']?>
                    <?=$take['user']['name']?>
                </td>
                <th><?= __("Vak")?></th>
                <td>
                    <?=$take['test']['subject']['name']?>
                </td>
            </tr>
            <tr>
                <th><?= __("Klas(sen)")?></th>
                <td colspan="5">
                    <?
                    foreach($take['school_classes'] as $class) {
                        echo $class['name'] . '<br />';
                    }
                    ?>
                </td>
            </tr>
        </table>
    </div>
</div>

<? if(!empty($take['invigilator_note'])) { ?>
    <div class="block">
        <div class="block-head"><?= __("Notities voor surveillant")?></div>
        <div class="block-content">
            <?=nl2br($take['invigilator_note'])?>
        </div>
    </div>
<? } ?>


<div class="block">
    <div class="block-head"><?= __("Informatie")?></div>
    <div class="block-content">
        <div class="tabs">
            <a href="#" class="btn grey highlight" page="participants" tabs="view_test_take">
            <?= __("Studenten")?>
            </a>

            <a href="#" class="btn grey" page="invigilators" tabs="view_test_take">
            <?= __("Surveillanten")?>
            </a>
            <br clear="all" />
        </div>

        <div page="participants" class="page active" tabs="view_test_take">

        </div>

        <div page="invigilators" class="page" tabs="view_test_take">
            <?
            foreach($take['invigilator_users'] as $invigilator) {
                ?>
                <div class="participant">
                    <?=$invigilator['name_first']?>
                    <?=$invigilator['name_suffix']?>
                    <?=$invigilator['name']?>
                </div>
                <?
            }
            ?>

            <br clear="all" />
            <? if($take['test_take_status_id'] == 1) { ?>
                <center>
                    <a href="#" class="btn highlight inline-block" onclick="Popup.load('/test_takes/edit/<?=$take_id?>', 500);">
                        <span class="fa fa-edit"></span>
                        <?= __("Surveillanten wijzigen")?>
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
</script>
