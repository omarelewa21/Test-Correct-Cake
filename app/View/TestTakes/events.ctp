<div class="block">
    <div class="block-head">Gebeurtenissen</div>
    <div class="block-content" style="max-height:400px; overflow: auto;">
        <table class="table table-striped">
            <tr>
                <th>Student</th>
                <th width="100">Gebeurtenis</th>
                <th width="45">Tijd</th>
                <th width="70"></th>
            </tr>
            <?

            $translations = [
                'Start' => 'Gestart met toets',
                'Stop' => 'Gestopt met toets',
                'Lost focus' => 'App verlaten',
                'Screenshot' => 'Screenshot gemaakt',
                'Started late' => 'Laat gestart',
                'Application closed' => 'Opnieuw gestart met toets',
                'Lost focus alt tab' => 'Via alt+tab naar ander venster',
            ];

            foreach($events as $event) {
                if (isset($translations[$event['test_take_event_type']['name']])) {
                    ?>
                    <tr id="event_<?= getUUID($event, 'get'); ?>">
                        <td>
                            <?= $event['test_participant']['user']['name_first'] ?>
                            <?= $event['test_participant']['user']['name_suffix'] ?>
                            <?= $event['test_participant']['user']['name'] ?>
                        </td>
                        <td>
                            <?= $translations[$event['test_take_event_type']['name']] ?>
                        </td>
                        <td>
                            <?= date('H:i', strtotime($event['created_at'])) ?>
                        </td>
                        <td class="nopadding" align="right">
                            <a href="#" class="btn highlight mb1 mr1 inline-block small"
                               onclick="TestTake.addParticipantNote('<?= getUUID($event['test_take'], 'get') ?>', '<?= getUUID($event['test_participant'], 'get'); ?>');">
                                <span class="fa fa-edit"></span>
                            </a>
                            <a href="#" class="btn highlight mb1 inline-block small"
                               onclick="TestTake.confirmEvent('<?= getUUID($event['test_take'], 'get') ?>', '<?= getUUID($event, 'get'); ?>');">
                                <span class="fa fa-check"></span>
                            </a>
                        </td>
                    </tr>
                <?
                }
            }
            ?>
        </table>
    </div>
    <div class="block-footer">
        <a href="#" class="btn grey pull-right" onclick="Popup.closeLast();">
            Sluiten
        </a>
    </div>
</div>