<div class="popup-head"><?= __("Verplaats")?> <?php echo sprintf('%s %s %s',$user['name_first'], $user['name_suffix'],$user['name']);?></div>
<div class="popup-content">
    <?=$this->Form->create('User') ?>
    <table class="table">
        <tr>
            <th width="130">
            <?= __("Huidige schoollocatie")?>
            </th>
            <td>
                <?php echo $school_locations[$user['school_location_id']];?>
            </td>
        </tr>
        <tr>
            <th width="130">
            <?= __("Schoollocatie")?>
            </th>
            <td>
                <?=$this->Form->input('school_location_id', array('style' => 'width: 100%', 'label' => false, 'options' => $school_locations)) ?>
            </td>
        </tr>
    </table>
    <?=$this->Form->end();?>
</div>
<div class="popup-footer">
    <a href="#" class="btn grey mt5 mr5 pull-right" onclick="Popup.closeLast();" selid="cancel-btn">
    <?= __("Annuleer")?>
    </a>
    <a href="#" class="btn highlight mt5 mr5 pull-right" id="btnAddUser">
    <?= __("Overplaatsen")?>
    </a>
</div>

<script type="text/javascript">
    $('#UserMoveSchoolLocationForm').formify(
        {
            confirm : $('#btnAddUser'),
            onsuccess : function(result) {
                Popup.closeLast();
                Notify.notify('<?= __("Gebruiker overgeplaatst")?>', "info");
                Navigation.refresh();
            },
            onfailure : function(result) {
                Notify.notify('<?= __("Gebruiker kon niet worden verplaatst, bevat de target school al een valide docentenaccount?")?>', "error");
            }
        }
    );
</script>