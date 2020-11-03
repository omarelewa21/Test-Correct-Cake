<div class="popup-head">Meerdere toets-afnames starten</div>
<div class="popup-content">
    <?= $this->Form->create('TestTake')?>
        <table class="table table-striped">
            <tr>
                <th width="30"></th>
                <th>Toets</th>
                <th>Klassen</th>
                <th>Vak</th>
                <th>Type</th>
            </tr>
            <?
            foreach($test_takes as $test_take) {
                ?>
                <tr>
                    <td>
                        <?=$this->Form->input('TestTake.'.getUUID($test_take, 'get'), [
                            'type' => 'checkbox',
                            'label' => false,
                            'class' => 'test_take',
                            'take_id' => getUUID($test_take, 'get')
                        ])?>
                    </td>
                    <td><?=$test_take['test']['name']?></td>
                    <td>
                        <?
                        foreach($test_take['school_classes'] as $class) {
                            echo $class['name'].'<br />';
                        }
                        ?>
                    </td>
                    <td><?=$test_take['test']['subject']['name']?></td>
                    <td><?=$test_take['retake'] == 0 ? 'Normaal' : 'Inhalen'?></td>
                </tr>
                <?
            }
            ?>
        </table>
    <?= $this->Form->end() ?>
</div>
<div class="popup-footer">
    <a href="#" class="btn grey mt5 mr5 pull-right" onclick="Popup.closeLast();">
        Annuleer
    </a>
    <a href="#" class="btn highlight mt5 mr5 pull-right" onclick="TestTake.startMultiple();">
        Toetsen nu afnemen
    </a>
</div>