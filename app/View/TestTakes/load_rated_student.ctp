<?
foreach($test_takes as $test_take) {
    ?>
    <tr>
        <td><?=$test_take['test']['name']?> [<?=$test_take['test']['abbreviation']?>]</td>
        <td><?=$test_take['test']['question_count']?></td>
        <td>
            <?=substr($test_take['user']['name_first'], 0, 1)?>.
            <?=$test_take['user']['name_suffix']?>
            <?=$test_take['user']['name']?>
        </td>
        <td><?=$test_take['test']['subject']['name']?></td>
        <td>
            <?=$test_take['test_take_status']['name']?>
        </td>
        <td>
            <?
            if($test_take['retake'] == 0) {
                ?>
                <div class="label label-info">Standaard</div>
                <?
            }else{
                ?>
                <div class="label label-warning">Inhaaltoets</div>
                <?
            }
            ?>
        </td>
        <td><?=$test_take['weight']?></td>
        <td><?= isset($test_take['test_participant']['rating']) ? round($test_take['test_participant']['rating'], 1) : '-' ?></td>
    </tr>
    <?
}
?>