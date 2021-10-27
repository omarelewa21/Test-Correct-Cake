<div id="buttons">
    <a href="#" class="btn white mr2" onclick="Navigation.back();">
        <span class="fa fa-backward mr5"></span>
        Terug
    </a>

    <a href="#" class="btn white mr2" onclick="TestTake.checkStartDiscussion('<?=$take_id?>', '<?= $take['consists_only_closed_question'] ?>');">
        <span class="fa fa-users mr5"></span>
        Toets bespreken
    </a>

    <a href="#" class="btn white mr2" onclick="Popup.load('/test_takes/skip_discussion_popup/<?=$take_id?>',500);">
        <span class="fa fa-forward mr5"></span>
        Meteen naar nakijken
    </a>
    <? if($take['test_take_status_id'] >= 6) { ?>
        <a href="#" class="btn white mr2" onclick="Navigation.load('/test_takes/add_retake/<?=$take_id?>');">
            <span class="fa fa-refresh mr5"></span>
            Inhaal-toets plannen
        </a>
    <? } ?>
</div>

<h1>Afgenomen toets</h1>

<div class="block">
    <div class="block-head">Toetsinformatie</div>
    <div class="block-content">
        <table class="table table-striped">
            <tr>
                <th width="12%">Toets</th>
                <td width="21%"><?=$take['test']['name']?></td>
                <th width="12%">Gepland</th>
                <td width="21%"><?=date('d-m-Y', strtotime($take['time_start']))?></td>
                <th width="12%">Type</th>
                <td width="21%"><?=$take['retake'] == 0 ? 'Normale toets' : 'Inhaal toets'?></td>
            </tr>
            <tr>

                <th>Weging</th>
                <td><?=$take['weight']?></td>
                <th>Gepland door</th>
                <td>
                    <?=$take['user']['name_first']?>
                    <?=$take['user']['name_suffix']?>
                    <?=$take['user']['name']?>
                </td>
                <th>Vak</th>
                <td>
                    <?=$take['test']['subject']['name']?>
                </td>
            </tr>
            <tr>
                <th>Klas(sen)</th>
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
    <div class="block-head">Informatie</div>
    <div class="block-content">
        <div class="tabs">
            <a href="#" class="btn grey highlight" page="participants" tabs="view_test_take">
                Studenten
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