<div class="popup-head"><?= __("Meerdere toets-afnames starten")?></div>
<div class="popup-content">
    <?= $this->Form->create('TestTake')?>
        <table class="table table-striped">
            <tr>
                <th width="30"></th>
                <th><?= __("Toets")?></th>
                <th><?= __("Klassen")?></th>
                <th><?= __("Vak")?></th>
                <th><?= __("Type")?></th>
            </tr>
            <?
            foreach($test_takes as $test_take) {
                ?>
                <tr>
                    <td>
                        <? if($test_take['invigilators_acceptable']){ ?>
                            <?=$this->Form->input('TestTake.'.getUUID($test_take, 'get'), [
                                'type' => 'checkbox',
                                'label' => false,
                                'class' => 'test_take',
                                'take_id' => getUUID($test_take, 'get')
                            ])?>
                        <? }else{ ?>
                            <img src="/img/ico/warning.svg" style="cursor:pointer;" onclick="TestTake.noStartTake('<?=$test_take['invigilators_unacceptable_message']?>');">
                        <?}?>
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
                    <td><?=$test_take['retake'] == 0 ? __("Normaal") : __("Inhalen")?></td>
                </tr>
                <?
            }
            ?>
        </table>
    <?= $this->Form->end() ?>
</div>
<div class="popup-footer">
    <a href="#" class="btn grey mt5 mr5 pull-right" onclick="Popup.closeLast();">
    <?= __("Annuleer")?>
    </a>
    <a href="#" class="btn highlight mt5 mr5 pull-right" onclick="TestTake.startMultiple();">
    <?= __("Toetsen nu afnemen")?>
    </a>
</div>