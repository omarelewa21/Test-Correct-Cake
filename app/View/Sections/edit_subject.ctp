<div class="popup-head">Vak</div>
<div class="popup-content">
    <?=$this->Form->create('Subject') ?>
    <table class="table">
        <tr>
            <th width="130">
                Naam
            </th>
            <td>
                <?=$this->Form->input('name', array('style' => 'width: 185px', 'label' => false, 'verify' => 'notempty')) ?>
            </td>
        </tr>
        <tr>
            <th width="130">
                Afkorting
            </th>
            <td>
                <?=$this->Form->input('abbreviation', array('style' => 'width: 185px', 'label' => false, 'verify' => 'notempty')) ?>
            </td>
        </tr>
        <tr>
            <th>Categorie</th>
            <td>
                <?=$this->Form->input('base_subject_id', array('style' => 'width: 185px', 'options' => $base_subjects, 'label' => false, 'verify' => 'notempty')) ?>
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
        Aanmaken
    </a>
</div>

<script type="text/javascript">
    $('#SubjectEditSubjectForm').formify(
        {
            confirm : $('#btnSave'),
            onsuccess : function(result) {
                Popup.closeLast();
                Notify.notify("Vak gewijzigd", "info");
                Navigation.refresh();
            },
            onfailure : function(result) {
                Notify.notify("Vak kon niet worden gewijzigd", "error");
            }
        }
    );
</script>