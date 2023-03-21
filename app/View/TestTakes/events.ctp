<div class="block">
    <div class="block-head"><?= __("Gebeurtenissen")?></div>
    <div class="block-content" style="max-height:400px; overflow: auto;">
        <table class="table table-striped">
            <tr>
                <th><?= __("Student")?></th>
                <th width="100"><?= __("Gebeurtenis")?></th>
                <th width="45"><?= __("Tijd")?></th>
                <th width="70"></th>
            </tr>
            <?

            $translations = [
                'Start' => __("Gestart met toets"),
                'Stop' => __("Gestopt met toets"),
                'Lost focus' => __("App verlaten"),
                'Screenshot' => __("Screenshot gemaakt"),
                'Started late' => __("Laat gestart"),
                'Continue' => __("Opnieuw gestart met toets"),
                'Application closed' => __("Opnieuw gestart met toets"),
                'Lost focus alt tab' => __("Via alt+tab naar ander venster"),
                'Pressed meta key' => __("Windows of Apple toets ingedrukt"),
                'Pressed alt key' => __("Alt toets ingedrukt"),
                'Application closed alt+f4' => __("Applicatie afgesloten via alt+f4"),
                'Lost focus blur' => __("App verlaten"),
                'Window hidden' => __("Applicatie verborgen"),
                'Window minimized' => __("Applicatie geminimalizeerd"),
                'Window moved' => __("Venster bewogen"),
                'Window not fullscreen' => __("Applicatie niet volledig scherm"),
                'Always on top changed' => __("Applicatie niet altijd op de voorgrond"),
                'Window resized' => __("Venster groote aangepast"),
                'Force shutdown' => __("Applicatie geforceerd afgesloten"),
                'Other window on top' => __("Ander venster op de voorgrond"),
                'Used unallowed Ctrl key combination' => __("De student heeft een toetsencombinatie met de Control toets gebruikt die niet toegestaan is."),
                'Illegal programs' => __('De student heeft een app in de achtergrond open die niet toegestaan is'),
                'Rejoined' => __('Opnieuw gestart met toets'),
                'Forbidden device' => __('Verboden apparaat aangesloten'),
                'VM detected' => __('Virtuele Machine gedetecteerd')

            ];

            foreach($events as $event) {
                if (isset($translations[$event['test_take_event_type']['name']])) {
                    $translation = $translations[$event['test_take_event_type']['name']];
                } else {
                    $translation = $event['test_take_event_type']['name'];
                }

                if ($event['test_take_event_type']['name'] == 'VM detected') {
                    $translation .= ': ' . $event['metadata']['software'];
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
        <?= __("Sluiten")?>
        </a>
    </div>
</div>
