

<h1>QTI import Cito</h1>

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
                    <?=$this->Form->input('school_location_id', array('type' => 'select', 'label' => false, 'div' => false, 'options' => $schoolList))?>
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
                    <?=$this->Form->input('subject_id', array('type' => 'select', 'label' => false, 'div' => false, 'options' => []))?>
                </td>
            </tr>
            <tr>
                <td><label>Niveau</label></td>
                <td>
                    <?=$this->Form->input('education_level_id', array('type' => 'select', 'label' => false, 'div' => false, 'options' => $educationLevelList))?>
                </td>
            </tr>

            <tr>
                <td><label>Jaar</label></td>
                <td>
                    <?=$this->Form->input('education_level_year', array('type' => 'select', 'label' => false, 'div' => false, 'options' => []))?>
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
        // console.log(data);
        clearTimeout(wistjedatjeTimer);
      jQuery('#QtiImportBlock').html((data));
    };

    var wistjedatjeTime = 10*1000;
    var wistjedatjeTimer
    var wistjedatjeNr = 0;
    var wistjedatjes = [
      'Wist je dat je met de co-learning module de studenten zelf de toetsen kunt laten nakijken..',
      'Wist je dat we ook tekenvragen aanbieden waarme de student een tekening kan maken op z\'n device..',
      'Wist je dat we nu ook infoschermen kennen waarmee je de student informatie kunt verschaffen over de komende vragen in de toets...',
      'Wist je dat we een voorleesfunctie hebben waarmee studenten de tekst van de toets voorgelezen kunnen krijgen...'
    ];
    var wistjedatjesEl = jQuery('#wistjedatjes');


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
    var teachers = JSON.parse('<?php echo str_replace("'","`",json_encode($teacherList))?>');
    var teachers1 = JSON.parse('<?php echo str_replace("'","`",json_encode($teachers))?>');
    var subjects = JSON.parse('<?php echo json_encode($subjectList)?>');
    var educationLevels = JSON.parse('<?php echo json_encode($educationLevels)?>');

    var teacherSelect = jQuery('#QtiAuthorId');
    var subjectSelect = jQuery('#QtiSubjectId');
    var schoolLocationSelect = jQuery('#QtiSchoolLocationId');
    var educationLevelSelect = jQuery('#QtiEducationLevelId');
    var educationLevelYearSelect = jQuery('#QtiEducationLevelYear');

    function qtiImportSetup() {
        $(document).ready(function () {

            jQuery('body')
                .on('change', '#QtiSchoolLocationId', function () {
                    var schoolLocationId = $(this).val();

                    teacherSelect.find('option').remove();
                    jQuery.each(teachers1, function (key, t) {
                        if(t.school_location_id == schoolLocationId){
                            teacherSelect.append('<option value="'+t.uuid+'">'+t.name+'</option>');
                        }
                    });
                })
                .on('change', '#QtiAuthorId', function () {
                    var authorId = $(this).val();
                    if(authorId && teachers[authorId]) {
                        var teacherSubjects = teachers[authorId]['subject_ids'];
                        subjectSelect.find('option').remove();
                        jQuery.each(subjects, function (key, s) {
                            // console.log(s)
                            if (teacherSubjects.includes(s.id)) {
                                subjectSelect.append('<option value="' + s.id + '">' + s.name + '</option>');
                            }
                        });
                    }
                })
                .on('change', '#QtiEducationLevelId', function () {
                    var elId = $(this).val();
                    var maxYears = educationLevels.find(level => level.uuid == elId)['max_years'];
                    educationLevelYearSelect.find('option').remove();
                    for(i=1;i<=maxYears;i++){
                        educationLevelYearSelect.append('<option value="'+i+'">'+i+'</option>');
                    };
                });
        });
        qtiImportSetupRun = true;
    }
    if(!qtiImportSetupRun){
        qtiImportSetup();
    }
    schoolLocationSelect.trigger('change');
    teacherSelect.trigger('change');
    educationLevelSelect.trigger('change');

</script>