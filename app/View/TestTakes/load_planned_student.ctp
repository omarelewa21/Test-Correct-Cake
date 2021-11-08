<?
foreach($test_takes as $test_take) {
    ?>
    <tr>
        <td><?=$test_take['test']['name']?> [<?=$test_take['test']['abbreviation']?>]</td>
        <td><?=$test_take['test']['question_count']?></td>
        <td>
            <?
            foreach($test_take['invigilator_users'] as $user) {
                echo $user['name_first']. ' ' . $user['name_suffix'] . ' ' . $user['name']. '<br />';
            }
            ?>
        </td>
        <td>
            <?=$test_take['user']['name_first']?>
            <?=$test_take['user']['name_suffix']?>
            <?=$test_take['user']['name']?>
        </td>
        <td><?=$test_take['test']['subject']['name']?></td>
        <td><?=date('d-m-Y', strtotime($test_take['time_start']))?></td>
        <td>
            <?=$test_take['test_take_status']['name']?>
        </td>
        <td>
            <?
            if($test_take['retake'] == 0) {
                ?>
                <div class="label label-info"><?= __("Standaard")?></div>
                <?
            }else{
                ?>
                <div class="label label-warning"><?= __("Inhaaltoets")?></div>
            <?
            }
            ?>
        </td>
        <td><?=$test_take['weight']?></td>
        <td class="nopadding" width="100">
            <? if(in_array($test_take['test_take_status_id'], [1, 3]) && date('d-m-Y', strtotime($test_take['time_start'])) == date('d-m-Y')) { ?>
                <a href="#" class="btn highlight mb1" onclick="TestTake.loadTake('<?=getUUID($test_take, 'get');?>', true);">
                <?= __("Toets maken")?>
                </a>
            <? }else{ ?>
                <a href="#" class="btn grey mb1">
                <?= __("Toets maken")?>
                </a>
            <? } ?>
        </td>
    </tr>
    <?
}
?>
