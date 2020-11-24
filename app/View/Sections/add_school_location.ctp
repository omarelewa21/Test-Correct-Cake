<div class="popup-head">School locatie</div>
<div class="popup-content">
    <?=$this->Form->create('SchoolLocation') ?>
    <table class="table">
        <tr>
            <th>Naam</th>
            <td>
                <?=$this->Form->input('school_location_id', array('style' => 'width: 185px', 'options' => $schoolLocationIds, 'label' => false, 'verify' => 'notempty')) ?>
            </td>
        </tr>
    </table>
    <?=$this->Form->end();?>
</div>
<div class="popup-footer">
    <a href="#" class="btn grey mt5 mr5 pull-right" onclick="Popup.closeLast();">
        Annuleer
    </a>
    <a href="#" class="btn highlight mt5 mr5 pull-right" id="btnSave">
        Koppelen
    </a>
</div>

<script type="text/javascript">
    $('#SubjectAddSubjectForm').formify(
        {
            confirm : $('#btnSave'),
            onsuccess : function(result) {
                Popup.closeLast();
                Notify.notify("Vak aangemaakt", "info");
                Navigation.refresh();
            },
            onfailure : function(result) {
                Notify.notify("Vak kon niet worden aangemaakt", "error");
            }
        }
    );
</script>