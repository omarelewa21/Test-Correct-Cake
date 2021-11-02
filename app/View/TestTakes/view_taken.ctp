<div id="buttons">
    <a href="#" class="btn white mr2" onclick="Navigation.back();">
        <span class="fa fa-backward mr5"></span>
        <?= __("Terug")?>
    </a>

    <a href="#" class="btn white mr2" onclick="TestTake.checkStartDiscussion('<?=$take_id?>', <?= $take['consists_only_closed_question'] ? 'true' : 'false' ?>);">
        <span class="fa fa-users mr5"></span>
        <?= __("Toets bespreken")?>
    </a>

    <a href="#" class="btn white mr2" onclick="Popup.load('/test_takes/skip_discussion_popup/<?=$take_id?>',500);">
        <span class="fa fa-forward mr5"></span>
        <?= __("Meteen naar nakijken")?>
    </a>
    <? if($take['test_take_status_id'] >= 6) { ?>
        <a href="#" class="btn white mr2" onclick="Navigation.load('/test_takes/add_retake/<?=$take_id?>');">
            <span class="fa fa-refresh mr5"></span>
            <?= __("Inhaal-toets plannen")?>
        </a>
    <? } ?>
</div>

<h1><?= __("Afgenomen toets")?></h1>
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


<div class="block">
    <div class="block-head"><?= __("Informatie")?></div>
    <div class="block-content">
        <div class="tabs">
            <a href="#" class="btn grey highlight" page="participants" tabs="view_test_take">
            <?= __("Studenten")?>
            </a>
            <br clear="all" />
        </div>

        <div page="participants" class="page active" tabs="view_test_take">

        </div>
    </div>
</div>

<script type="text/javascript">
    clearTimeout(window.loadParticipants);
    TestTake.loadParticipants('<?=$take_id?>');
</script>