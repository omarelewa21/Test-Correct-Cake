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
                'Pressed meta key' => 'Windows/command toets ingedrukt',
                'Pressed alt key' => 'Alt toets ingedrukt',
                'Application closed alt+f4' => 'Applicatie afgesloten via alt+f4',
                'Lost focus blur' => 'App verlaten',
                'Window hidden' => 'Applicatie verborgen',
                'Window minimized' => 'Applicatie geminimalizeerd',
                'Window moved' => 'Venster bewogen',
                'Window not fullscreen' => 'Applicatie niet volledig scherm',
                'Always on top changed' => 'Applicatie niet altijd op de voorgrond',
                'Window resized' => 'Venster groote aangepast',
                'Force shutdown' => 'Applicatie geforceerd afgesloten',
                'Other window on top' => 'Ander venster op de voorgrond',
                'Used unallowed Ctrl key combination' => 'De student heeft een toetsencombinatie met de Control toets gebruikt die niet toegestaan is.',
            ];

            foreach($events as $event) {
                if (isset($translations[$event['test_take_event_type']['name']])) {
                    $translation = $translations[$event['test_take_event_type']['name']];
                } else {
                    $translation = $event['test_take_event_type']['name'];
                }
                    ?>
                    <tr id="event_<?= getUUID($event, 'get'); ?>">
                        <td>
                            <?= $event['test_participant']['user']['name_first'] ?>
                            <?= $event['test_participant']['user']['name_suffix'] ?>
                            <?= $event['test_participant']['user']['name'] ?>
                        </td>
                        <td>
                            <?= $translation ?>
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
            ?>
        </table>
    </div>
    <div class="block-footer">
        <a href="#" class="btn grey pull-right" onclick="Popup.closeLast();">
            Sluiten
        </a>
    </div>
</div>