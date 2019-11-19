

<h1>QTI import</h1>

<div class="block " id="QtiImportBlock">
    <div id="QtiImportContainer" style="display:none;overflow:scroll;padding: 8px;">
        Een moment dit kan enige tijd duren (als in een paar minuten);
        <h4 style="color:green;" id="wistjedatjes"></h4>
    </div>
    <?=$this->Form->create('Qti', array('id' => 'QtiImportForm', 'type' => 'file', 'method' => 'post', 'target' => 'frameUploadAttachment', 'url' => 'import'))?>
    <div class="block-content" id="testsContainter">
        <table class='table'>
            <tr>
                <td>
                    <label>School</label>
                </td>
                <td>
                    <?=$this->Form->input('school_id', array('type' => 'select', 'label' => false, 'div' => false, 'options' => $schoolList))?>
                </td>
            </tr>
            <tr>
                <td><label>Docent</label></td>
                <td>
                    <?=$this->Form->input('author_id', array('type' => 'select', 'label' => false, 'div' => false, 'options' => []))?>
                </td>
            </tr>
            </tr>
            <tr>
                <td><label>Vakgebied</label></td>
                <td>
                    <?=$this->Form->input('subject_id', array('type' => 'select', 'label' => false, 'div' => false, 'options' => $subjectList))?>
                </td>
            </tr>
            <tr>
                <td><label>Niveau</label></td>
                <td>
                    <?=$this->Form->input('education_level_id', array('type' => 'select', 'label' => false, 'div' => false, 'options' => $educationLevelList))?>
                </td>
            </tr>
            <tr>
                <td><label>Periode</label></td>
                <td>
                    <?=$this->Form->input('period_id', array('type' => 'select', 'label' => false, 'div' => false, 'options' => $periodList))?>
                </td>
            </tr>
            <tr>
                <td><label>Type toetsen</label></td>
                <td>
                    <?=$this->Form->input('test_kind_id', array('type' => 'select', 'label' => false, 'div' => false, 'options' => $testKindList))?>
                </td>
            </tr>
            <tr>
                <td><label>Voorloop afkorting voor de toets namen (max 3 karakters)</label></td>
                <td>
                    <?=$this->Form->input('abbr', array('style' => 'width:50px;', 'value' => '', 'label' => false, 'verify' => 'notempty'))?>
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
            Vraag opslaan
        </a>
    </div>
    <?=$this->Form->end?>
</div>

<script>

    function handleQtiImportResponse(data){
        console.log(data);
        clearTimeout(wistjedatjeTimer);
      jQuery('#QtiImportBlock').html((data));
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


    function handleSubmit(){
        $('#QtiImportBlock').height($('#QtiImportBlock').height()).css('overflow','scroll').css('padding','8px');
        $('#QtiImportContainer').show();
        $('#QtiImportForm').hide().submit();
        setWistjedatjesTimer();
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

    var qtiImportSetupRun = false;
    let teachers = JSON.parse('<?php echo json_encode($teachers)?>');
    function qtiImportSetup() {
        $(document).ready(function () {
            let teacherSelect = jQuery('#QtiAuthorId');
            let schoolLocationSelect = jQuery('#QtiSchoolId');
            jQuery('body')
                .on('change', '#QtiSchoolId', function () {
                    var schoolLocationId = $(this).val();

                    teacherSelect.find('option').remove();
                    jQuery.each(teachers, function (key, t) {
                        if(t.school_location_id == schoolLocationId){
                            teacherSelect.append('<option value="'+t.id+'">'+t.name+'</option>');
                        }
                    });
                });
            schoolLocationSelect.trigger('change');
        });
        qtiImportSetupRun = true;
    }
    if(!qtiImportSetupRun){
        qtiImportSetup();
    }


</script>