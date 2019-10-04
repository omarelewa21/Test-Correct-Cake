<div class="popup-head">Student</div>
<div class="popup-content">
    <?=$this->Form->create('User') ?>
    <table class="table">

        <tr>
            <th width="130">
                Schoollocatie
            </th>
            <td>
                <?=$this->Form->input('school_location_id', array('style' => 'width: 185px', 'label' => false, 'options' => $school_locations)) ?>
            </td>
        </tr>
        <tr>
            <th width="130">
                Klassen
            </th>
            <td>
                <?=$this->Form->input('student_school_classes', array('style' => 'width: 185px', 'label' => false, 'multiple' => true, 'options'=> $school_classes, 'value' => $class_id)) ?>
            </td>
        </tr>
        <tr>
            <th>Geslacht</th>
            <td>
                <?=$this->Form->input('gender', array('style' => 'width: 185px', 'label' => false, 'options' => ['Male' => 'Mannelijk', 'Female' => 'Vrouwelijk'])) ?>
            </td>
        </tr>
        <tr>
            <th width="130">
                Voornaam
            </th>
            <td>
                <?=$this->Form->input('name_first', array('style' => 'width: 185px', 'label' => false, 'verify' => 'notempty')) ?>
            </td>
        </tr>
        <tr>
            <th width="130">
                Tussenvoegsel
            </th>
            <td>
                <?=$this->Form->input('name_suffix', array('style' => 'width: 185px', 'label' => false)) ?>
            </td>
        </tr>
        <tr>
            <th width="130">
                Achternaam
            </th>
            <td>
                <?=$this->Form->input('name', array('style' => 'width: 185px', 'label' => false, 'verify' => 'notempty')) ?>
            </td>
        </tr>
        <tr>
            <th width="130">
                Studentennummer
            </th>
            <td>
                <?=$this->Form->input('external_id', array('style' => 'width: 185px', 'label' => false, 'type' => 'text', 'verify' => 'notempty')) ?>
            </td>
        </tr>
        <tr>
            <th>
                Dyslexie
            </th>
            <td>
                <?=$this->Form->input('time_dispensation', ['type' => 'checkbox', 'style' => 'width:20px;', 'label' => false, 'div' => false])?> Audio-ondersteuning (txt2speech)
            </td>
        </tr>
        <?php echo $this->element('text2speech'); ?>
        <tr>
            <th width="130">
                E-mailadres
            </th>
            <td>
                <?=$this->Form->input('username', array('style' => 'width: 185px', 'label' => false, 'verify' => 'email')) ?>
            </td>
        </tr>
        <tr>
            <th width="130">
                Nieuw wachtwoord
            </th>
            <td>
                <?=$this->Form->input('password', array('style' => 'width: 185px', 'label' => false, 'type' => 'text')) ?>
            </td>
        </tr>
        <tr>
            <th colspan="2">Notities</th>
        </tr>
        <tr>
            <td colspan="2">
                <?=$this->Form->input('note', [
                    'style' => 'width:330px; height:100px',
                    'type' => 'textarea',
                    'label' => false
                ])?>
            </td>
        </tr>
    </table>
    <?=$this->Form->end();?>
    <?=$this->Form->create('User', ['type' => 'file', 'target' => 'iframeProfilePicture', 'id' => 'formProfilePicture', 'action' => 'profile_picture_upload']) ?>
    <table class="table">
        <tr>
            <th width="130">
                Profielfoto
            </th>
            <td>
                <?=$this->Form->input('file', array('style' => 'width: 185px', 'type' => 'file', 'label' => false, 'onchange' => '$("#formProfilePicture").submit(); Loading.show();')) ?>
            </td>
        </tr>
    </table>
    <?=$this->Form->end();?>

    <iframe width="0" height="0" style="position:absolute;" name="iframeProfilePicture" id="iframeProfilePicture"></iframe>
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

    $('#UserAddForm').formify(
        {
            confirm : $('#btnAddUser'),
            onsuccess : function(result) {
                Popup.closeLast();
                Notify.notify("Gebruiker aangemaakt", "info");
                Navigation.refresh();
            },
            onfailure : function(result) {
                if(result.error != undefined && result.error == 'external_code') {
                    Notify.notify("Studentnummer is al in gebruik", "error");
                }else {
                    Notify.notify("Het opgegeven e-mailadres is al in gebruik of onvolledig", "error");
                }
            }
        }
    );

    $('#UserStudentSchoolClasses').select2();

    $('#UserStudentSchoolLocationId').change(function() {
        var classes = $.getJSON('/school_classes/get_for_location/' + $(this).val(),
            function(classes) {
                $('#UserStudentSchoolClasses').empty();

                $.each(classes, function(value,key) {
                    $('#UserStudentSchoolClasses').append($("<option></option>").attr("value", value).text(key));
                    $('#UserStudentSchoolClasses').trigger('change');
                });
            }
        );

        console.log(classes);
    }).change();
</script>