<?
if(count($test_takes) == 0) {
    ?>
    <br /><br />
    <center>
        Vandaag geen geplande toetsen
    </center>
    <?
}else{
    ?>
    <table class="table table-striped">
        <tr>
            <th>Toets</th>
            <th>Datum</th>
            <th width="130">Vak</th>
            <th width="90">Type</th>
            <th width="90"></th>
        </tr>
        <?
        foreach($test_takes as $test_take) {
            if(in_array($test_take['test_take_status_id'], [1, 3])) {
                ?>
                <tr>
                    <td><?= $test_take['test']['name'] ?> [<?= $test_take['test']['abbreviation'] ?>]</td>
                    <td><?=date('d-m-Y', strtotime($test_take['time_start']))?></td>
                    <td><?= $test_take['test']['subject']['name'] ?></td>
                    <td>
                        <?
                        if ($test_take['retake'] == 0) {
                            ?>
                            <div class="label label-info">Standaard</div>
                        <?
                        } else {
                            ?>
                            <div class="label label-warning">Inhaaltoets</div>
                        <?
                        }
                        ?>
                    </td>
                    <td class="nopadding">
                        <? if(date('d-m-Y', strtotime($test_take['time_start'])) == date('d-m-Y')) { ?>
                            <a href="#" class="btn highlight mb1"
                               onclick="TestTake.loadTake('<?=getUUID($test_take, 'get');?>', true);">
                                Nu maken
                            </a>
                        <? }else{ ?>
                            <a href="#" class="btn white mb1">
                                Nu maken
                            </a>
                        <? } ?>
                    </td>
                </tr>
            <?
            }
        }
        ?>
    </table>
<?
}
?>