
<div class="popup-head"><?= __("Klas uploaden")?></div>
<div class="popup-content">

    <div class=" " id="FileClassBlock">
        <div id="FileClassContainer" style="display:none;overflow:scroll;padding: 8px;">
        <?= __("Een moment dit kan even duren...")?>
            <h4 style="color:green;" id="wistjedatjes"></h4>
        </div>
        <?=$this->Form->create('FileClass', array('id' => 'FileClassForm', 'type' => 'file', 'method' => 'post', 'target' => 'frameUploadAttachment'))?>
        <div class="block-content" id="testsContainter">
        <?= __("Hier kunt u de klassen in een Excel-format uploaden (of een ander spreadsheet format).")?><br />
            <b><?= __("Let op")?></b><?= __(": het is belangrijk dat het bestand voldoet aan de volgende voorwaarden: (de gegevens ieder in een eigen kolom).")?>
            <ul>
                <li><?= __("Voornaam")?></li>
                <li><?= __("Tussenvoegsel")?></li>
                <li><?= __("Achternaam")?></li>
                <li><?= __("Stamnummer")?></li>
                <li><?= __("E-mailadres")?></li>
            </ul>
            <?= __("Optioneel maar niet verplicht:")?>
            <ul>
                <li><?= __("Recht op tijdsdispensatie ja of nee")?></li>
                <li><?= __("Tekst2speech-functie aan of uit (er zijn hier additionele kosten aan verbonden)")?></li>
            </ul>
            <table class='table'>
                <tr>
                    <td>
                        <label><?= __("Klas")?></label>
                    </td>
                    <td>
                        <?=$this->Form->input('class', array('value' => '', 'label' => false, 'verify' => 'notempty'))?>
                    </td>
                </tr>
                <tr>
                    <td><label><?= __("Niveau")?></label></td>
                    <td>
                        <?=$this->Form->input('education_level_id', array('type' => 'select', 'label' => false, 'div' => false, 'options' => $educationLevelOptions))?>
                    </td>
                </tr>

                <tr>
                    <td><label><?= __("Jaar")?></label></td>
                    <td>
                        <?=$this->Form->input('education_level_year', array('type' => 'select', 'label' => false, 'div' => false, 'options' => []))?>
                    </td>
                </tr>

                <tr>
                    <td><label><?= __("Stamklas")?></label></td>
                    <td>
                        <?=$this->Form->input('is_main_school_class', array('type' => 'select', 'label' => false, 'div' => false, 'options' => [0 => __("Nee"), 1 => __("Ja")]))?>
                    </td>
                </tr>
                <tr>
                    <td>
                        <label><?= __("Vak")?></label>
                    </td>
                    <td>
                        <?=$this->Form->input('subject', array('value' => '', 'label' => false, 'verify' => 'notempty'))?>
                    </td>
                </tr>

                <tr>
                    <td></td>
                    <td>
                        <?=$this->Form->input('file', array('type' => 'file',  'label' => false, 'div' => false, 'onchange' => '')) ?>
                    </td>
                </tr>
            </table>

            <iframe id="frameUploadAttachment" name="frameUploadAttachment" width="0" height="0" frameborder="0" style="position: absolute"></iframe>
        </div>
        <div class="block-footer">
            <a href="#" class="btn highlight mt5 mr5 pull-right" onclick="handleSubmit()">
            <?= __("Klas uploaden")?>
            </a>
            <a href="#" class="btn grey mt5 mr5 pull-right" onclick="Popup.closeLast();">
            <?= __("Annuleer")?>
            </a>
        </div>
        <?=$this->Form->end?>
    </div>
</div>

<script>

    function handleUploadError(error){
        clearTimeout(wistjedatjeTimer);
        Notify.notify(error,'error');
        $('#FileClassContainer').hide();
        $('#FileClassForm').show();
    }

    function handleUploadResponse(data){
        clearTimeout(wistjedatjeTimer);
        Notify.notify(data,'info');
        Navigation.load('/file_management/classuploads');
        Popup.closeLast();
    };

    var wistjedatjeTime = 10*1000;
    var wistjedatjeTimer
    var wistjedatjeNr = 0;
    var wistjedatjes = [
        '<?= __("Wist je dat je met de co-learning module de studenten zelf de toetsen kunt laten nakijken..")?>',
        '<?= __("Wist je dat we ook tekenvragen aanbieden waarme de student een tekening kan maken op het device..")?>',
        '<?= __("Wist je dat we nu ook infoschermen kennen waarmee je de student informatie kunt verschaffen over de komende vragen in de toets...")?>',
        '<?= __("Wist je dat we een voorleesfunctie hebben waarmee studenten de tekst van de toets voorgelezen kunnen krijgen...")?>'
    ];
    var wistjedatjesEl = jQuery('#wistjedatjes');

    var educationLevels = JSON.parse('<?php echo json_encode($educationLevels)?>');

    var requiredFields = ['subject','class'];

    function handleSubmit(){
        jQuery('#FileClassForm :input').removeClass('verify-error');
        var hasErrors = false;
        requiredFields.forEach(function(r){
            var id = 'FileClass'+r.charAt(0).toUpperCase() + r.slice(1);
            if(jQuery('#'+id).val().length < 1){
                jQuery('#'+id).addClass('verify-error');
                hasErrors = true;
            }
        });
        if(hasErrors){
            Notify.notify('<?= __("Niet alle velden zijn correct ingevuld")?>','error');
            return false;
        }

        $('#FileClassBlock').height($('#FileClassBlock').height()).css('overflow','scroll').css('padding','8px');
        $('#FileClassContainer').show();
        $('#FileClassForm').hide().submit();
        showWistJeDatJe();
    }

    function showWistJeDatJe(){
        if(wistjedatjeNr >= wistjedatjes.length){
            wistjedatjeNr = 0;
        }
        wistjedatjesEl.html(wistjedatjes[wistjedatjeNr]);
        wistjedatjeNr++;
        setWistjedatjesTimer();
    }

    function setWistjedatjesTimer(){
        wistjedatjeTimer = setTimeout(function(){
                showWistJeDatJe();
            },
            wistjedatjeTime);
    }

    var educationLevelSelect = jQuery('#FileClassEducationLevelId');
    var educationLevelYearSelect = jQuery('#FileClassEducationLevelYear');

    function fileClassSetup() {
        $(document).ready(function () {
            jQuery('body')
                .on('change', '#FileClassEducationLevelId', function () {
                    var elId = $(this).val();
                    var maxYears = educationLevels.find(level => level.uuid == elId)['max_years'];
                    educationLevelYearSelect.find('option').remove();
                    for(i=1;i<=maxYears;i++){
                        educationLevelYearSelect.append('<option value="'+i+'">'+i+'</option>');
                    };
                    // console.log('changed');
                });
        });
        fileClassSetupRun = true;
    }

    if(typeof fileClassSetupRun == 'undefined' || !fileClassSetupRun){
        fileClassSetup();
    }

    educationLevelSelect.trigger('change');
</script>