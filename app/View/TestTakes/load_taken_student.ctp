<?
foreach($test_takes as $test_take) {
    ?>
    <tr>
        <td><?=$test_take['test']['name']?> [<?=$test_take['test']['abbreviation']?>]</td>
        <td><?=$test_take['test']['subject']['name']?></td>
        <td><?=date('d-m-Y', strtotime($test_take['time_start']))?></td>
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
        <td class="nopadding" width="30">
            <a href="#" class="btn white" onclick="TestTake.loadDiscussion('<?=getUUID($test_take, 'get');?>');">
                <span class="fa fa-folder-open-o"></span>
            </a>
        </td>
    </tr>
<?
}
?>