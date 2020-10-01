<?
foreach($test_takes as $test_take) {
    ?>
    <tr>
        <td><?=$test_take['test']['name']?> [<?=$test_take['test']['abbreviation']?>]</td>
        <td>
            <?=$test_take['user']['name_first']?>
            <?=$test_take['user']['name_suffix']?>
            <?=$test_take['user']['name']?>
        </td>
        <td><?=date('d-m-Y H:i', strtotime($test_take['time_start']))?></td>
        <td><?=$test_take['weight']?></td>
        <td class="nopadding" width="100">
            <a href="#" class="btn highlight pull-right" onclick="TestTake.setSelectedTestTake('<?=getUUID($test_take, 'get');?>', '<?=$test_take['test']['name']?> op <?=date('d-m-Y H:i', strtotime($test_take['time_start']))?>');">
                <span class="fa fa-folder-open-o"></span>
            </a>
        </td>
    </tr>
<?
}
?>
