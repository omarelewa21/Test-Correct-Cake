<?
$present = true;
foreach($participants as $participant) {

    $active = !empty($participant['heartbeat_at']) && strtotime($participant['heartbeat_at']) > (time() - 10);

    $present = $active ? $present : false;

    if($status == 6 && !in_array($participant['test_take_status_id'], [4, 6, 7, 8])) {
        continue;
    }
    ?>
    <div class="participant <?= $active ? 'active' : ''?>">
        <? if($status == 1) { ?>
            <span class="pull-right fa fa-remove" style="cursor:pointer;" onclick="TestTake.removeParticipant('<?=$take_id?>', '<?=getUUID($participant, 'get');?>');"></span>
        <? } ?>
        <?=$participant['user']['name_first']?>
        <?=$participant['user']['name_suffix']?>
        <?=$participant['user']['name']?>

        <?
        if(!empty($participant['rating'])) {
            echo '[ ' . $participant['rating'] . ' ]';
        }
        ?>
    </div>
    <?
}
?>

<br clear="all" />

<? if($status == 1) { ?>
    <center>
        <a href="#" class="btn highlight inline-block" onclick="Popup.load('/test_takes/add_participants/<?=$take_id?>', 700);">
            <span class="fa fa-plus"></span>
            <?= __("Studenten toevoegen")?>
        </a>
    </center>
<? } ?>

<script type="text/javascript">
    clearTimeout(window.loadParticipants);
    window.loadParticipants = setTimeout(function() {
        TestTake.loadParticipants('<?=$take_id?>');
    }, 2000);

    <?php
    if($present) {
        ?>
        TestTake.studentsPresent = true;
        <?php
    }else{
        ?>
        TestTake.studentsPresent = false;
        <?php
    }
    ?>
</script>