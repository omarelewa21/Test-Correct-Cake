 <?
if(count($test_takes) == 0) {
    ?>
    <br /><br />
    <center>
        Er zijn nog geen cijfers bekend.
    </center>
    <?
}else{
    ?>
    <table class="table table-striped">
        <tr>
            <th>Toets</th>
            <th width="80">Datum</th>
            <th width="150">Vak</th>
            <th width="50">Cijfer</th>
        </tr>
        <?
        foreach($test_takes as $test_take) {
                ?>
                <tr>
                    <td><?= $test_take['test']['name'] ?> [<?= $test_take['test']['abbreviation'] ?>]</td>
                    <td><?= date('d-m-Y', strtotime($test_take['time_start'])) ?></td>
                    <td><?= $test_take['test']['subject']['name'] ?></td>
                    <td><?= !empty($test_take['test_participant']['rating']) ? round($test_take['test_participant']['rating'], 1) : '-'?></td>
                </tr>
            <?
        }
        ?>
    </table>
    <?
}
?>