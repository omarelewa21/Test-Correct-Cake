<?
foreach($test_takes as $test_take) {
    ?>
    <tr>
        <td><?=$test_take['test']['name']?> [<?=$test_take['test']['abbreviation']?>]</td>
        <td><?=empty($test_take['test_participant']['invigilator_note']) ? '-' : $test_take['test_participant']['invigilator_note']?></td>
        <td><?=date('d-m-Y', strtotime($test_take['time_start']))?></td>
        <td><?=date('d-m-Y', strtotime($test_take['show_results']))?></td>

        <td class="nopadding" width="100">
            <a href="#" class="btn highlight mb1" onclick="Navigation.load('/test_takes/glance/<?=$test_take['id']?>');">
                Inzien
            </a>
        </td>
    </tr>
<?
}
?>