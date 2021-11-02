<div class="popup-head"><?= __("Koppelen")?></div>
<div class="popup-content">
    <?=$this->Form->create('Student') ?>
    <table class="table">
        <tr>
            <th width="130">
            <?= __("Gebruiker")?>
            </th>
            <td>
                <?=$this->Form->input('student_id', array('style' => 'width: 185px', 'label' => false, 'options' => $students)) ?>
            </td>
        </tr>
    </table>
    <?=$this->Form->end();?>
</div>
<div class="popup-footer">
    <a href="#" class="btn grey mt5 mr5 pull-right" onclick="Popup.closeLast();">
    <?= __("Annuleer")?>
    </a>
    <a href="#" class="btn highlight mt5 mr5 pull-right" id="btnAddUser">
    <?= __("Aanmaken")?>
    </a>
</div>

<script type="text/javascript">
    $('#StudentAddStudentForm').formify(
        {
            confirm : $('#btnAddUser'),
            onsuccess : function(result) {
                Popup.closeLast();
                Notify.notify('<?= __("Gebruiker gekoppeld")?>', "info");
                Navigation.refresh();
            },
            onfailure : function(result) {
                Notify.notify('<?= __("Gebruiker kon niet worden aangemaakt")?>', "error");
            }
        }
    );

    $('select').select2();
</script>