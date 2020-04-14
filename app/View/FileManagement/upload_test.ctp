
<div class="popup-head">Toets uploaden</div>
<div class="popup-content">

    <div class=" " id="FileTestBlock">
        <div id="FileTestContainer" style="display:none;overflow:scroll;padding: 8px;">
            Een moment dit kan even duren...
            <h4 style="color:green;" id="wistjedatjes"></h4>
        </div>
        <?=$this->Form->create('FileTest', array('id' => 'FileTestForm', 'type' => 'file', 'method' => 'post', 'target' => 'frameUploadAttachment'))?>
        <div class="block-content" id="testsContainer">
            <table class='table'>
                <tr>
                    <td><label>Niveau</label></td>
                    <td>
                        <?=$this->Form->input('education_level_id', array('type' => 'select', 'label' => false, 'div' => false, 'options' => $educationLevelOptions))?>
                    </td>
                </tr>
                <tr>
                    <td><label>Jaar</label></td>
                    <td>
                        <?=$this->Form->input('education_level_year', array('type' => 'select', 'label' => false, 'div' => false, 'options' => []))?>
                    </td>
                </tr>
                <tr>
                    <td>
                        <label>Vak</label>
                    </td>
                    <td>
                        <?=$this->Form->input('subject', array('value' => '', 'label' => false, 'verify' => 'notempty'))?>
                    </td>
                </tr>
                <tr>
                    <td><label>Type toets</label></td>
                    <td>
                        <?=$this->Form->input('test_kind_id', array('type' => 'select', 'label' => false, 'div' => false, 'options' => $testKindOptions))?>
                    </td>
                </tr>
                <tr>
                    <td>
                        <label>Toets naam</label>
                    </td>
                    <td>
                        <?=$this->Form->input('name', array('value' => '', 'label' => false, 'verify' => 'notempty'))?>
                    </td>
                </tr>
                <tr>
                    <td>Kies 1 of meerdere bestanden</td>
                    <td>
                        <?=$this->Form->input('file.', array('type' => 'file', 'multiple', 'label' => false, 'div' => false, 'onchange' => '')) ?>
                    </td>
                </tr>
            </table>

            <iframe id="frameUploadAttachment" name="frameUploadAttachment" width="0" height="0" frameborder="0" style="position: absolute"></iframe>
        </div>
        <div class="block-footer">
            <a href="#" class="btn highlight mt5 mr5 pull-right" onclick="handleSubmit()">
                Toets uploaden
            </a>
            <a href="#" class="btn grey mt5 mr5 pull-right" onclick="Popup.closeLast();Navigation.refresh();">
                Annuleer
            </a>
        </div>
        <?=$this->Form->end?>
    </div>
</div>

<style>
    .text-danger {
        color : #d9534f;
    }
</style>

<script>

    function handleUploadError(error){
        clearTimeout(wistjedatjeTimer);
        Notify.notify(error,'error');
        $('#FileTestContainer').hide();
        $('#FileTestForm').show();
    }

    function handleUploadResponse(data){
        clearTimeout(wistjedatjeTimer);
        Notify.notify(data,'info');
        Navigation.refresh();
        Popup.closeLast();
    };

    let wistjedatjeTime = 10*1000;
    var wistjedatjeTimer
    var wistjedatjeNr = 0;
    let wistjedatjes = [
      'Wist je dat je met de co-learning module de studenten zelf de toetsen kunt laten nakijken..',
      'Wist je dat we ook tekenvragen aanbieden waarme de student een tekening kan maken op z\'n device..',
      'Wist je dat we nu ook infoschermen kennen waarmee je de student informatie kunt verschaffen over de komende vragen in de toets...',
      'Wist je dat we een voorleesfunctie hebben waarmee studenten de tekst van de toets voorgelezen kunnen krijgen...'
    ];
    let wistjedatjesEl = jQuery('#wistjedatjes');

    let educationLevels = JSON.parse('<?php echo json_encode($educationLevels)?>');


    function handleSubmit(){
        $('#FileTestBlock').height($('#FileTestBlock').height()).css('overflow','scroll').css('padding','8px');
        $('#FileTestContainer').show();
        $('#FileTestForm').hide().submit();
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

    var educationLevelSelect = jQuery('#FileTestEducationLevelId');
    var educationLevelYearSelect = jQuery('#FileTestEducationLevelYear');

    function FileTestSetup() {
        $(document).ready(function () {
            jQuery('body')
                .on('change', '#FileTestEducationLevelId', function () {
                    var elId = $(this).val();
                    var maxYears = educationLevels.find(level => level.id == elId)['max_years'];
                    educationLevelYearSelect.find('option').remove();
                    for(i=1;i<=maxYears;i++){
                        educationLevelYearSelect.append('<option value="'+i+'">'+i+'</option>');
                    };
                    console.log('changed');
                });
        });
        FileTestSetupRun = true;
    }

    if(typeof FileTestSetupRun == 'undefined' || !FileTestSetupRun){
        FileTestSetup();
    }

    educationLevelSelect.trigger('change');
</script>