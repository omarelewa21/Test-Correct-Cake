<div class="popup-head">Docent</div>
<div class="popup-content">
    <?=$this->Form->create('Teacher') ?>
    <table class="table">
        <tr>
            <th width="130">
                Docent
            </th>
            <td>
                <?=$this->Form->input('teacher_id', array('style' => 'width: 185px', 'label' => false, 'options' => $teachers)) ?>
            </td>
         </tr>
        <tr>
            <th width="130">
                Vak
            </th>
            <td>
                <?=$this->Form->input('subject_id', array('style' => 'width: 185px', 'label' => false, 'options' => $subjects)) ?>
            </td>
        </tr>
    </table>
    <?=$this->Form->end();?>
</div>
<div class="popup-footer">
    <a href="#" class="btn grey mt5 mr5 pull-right" onclick="Popup.closeLast();">
        Annuleer
    </a>
    <a href="#" class="btn highlight mt5 mr5 pull-right" id="btnAddUser">
        Aanmaken
    </a>
</div>

<script type="text/javascript">
    $('#TeacherAddTeacherForm').formify(
        {
            confirm : $('#btnAddUser'),
            onsuccess : function(result) {
                Popup.closeLast();
                Notify.notify("Gebruiker aangemaakt", "info");
                Navigation.refresh();
            },
            onfailure : function(result) {
                Notify.notify("Gebruiker kon niet worden aangemaakt", "error");
            }
        }
    );

    $('select').select2();
</script>