

<h1><?= __("School & School locations import")?></h1>

<div class="block " id="SchoolAndSchoolLocationsImportBlock">
    <div id="SchoolAndSchoolLocationsImportContainer" style="display:none;overflow:scroll;padding: 8px;">
    <?= __("Een moment dit kan enige tijd duren (als in een paar minuten);")?>
        <h4 style="color:green;" id="wistjedatjes"></h4>
    </div>
    <?=$this->Form->create('SchoolAndSchoolLocations', array('id' => 'SchoolAndSchoolLocationsImportForm', 'type' => 'file', 'method' => 'post', 'target' => 'frameUploadAttachment', 'url' => 'import'))?>
    <?=$this->Form->input('skipCheck',['type' => 'hidden','id' => 'skipCheck', 'value' => 0]); ?>
    <div class="block-content" id="testsContainter">
        <table class='table'>
            <tr>
                <td id="restartError"></td>
            </tr>
            <tr>
                <td>
                    <?=$this->Form->input('file', array('type' => 'file', 'id' => 'fileuploadinput', 'label' => false, 'div' => false, 'onchange' => '')) ?>
                </td>
            </tr>
        </table>

        <iframe id="frameUploadAttachment" name="frameUploadAttachment" width="0" height="0" frameborder="0" style="position: absolute"></iframe>
    </div>
    <div class="block-footer">

        <a href="#" class="btn highlight mt5 mr5 pull-right" style="display:none;" id="newFile" onclick="Navigation.refresh()">
            <?= __("Nieuw bestand verwerken")?>
        </a>

        <a href="#" class="btn highlight mt5 mr5 pull-right" id="handleSubmit" onclick="handleSubmit()">
        <?= __("Import verwerken")?>
        </a>
    </div>
    <?=$this->Form->end?>
</div>

<script>

    function clearRestartError(){
        $('#restartError').html('');
    }

    function setSkipCheck(val = 1, fatalError = false) {
        $('#skipCheck').val(val);
        if(val == 1) {
            $('#handleSubmit').addClass('label-danger').removeClass('highlight').css('color', 'white');
        }
    }

    function setHandleSubmitText(text = '<?= __("Import verwerken")?>') {
        $('#handleSubmit').html(text);
    }

    function handleFatalError(){
        $('#handleSubmit').hide();
        $('#fileuploadinput').remove();
    }

    function handleSchoolAndSchoolLocationsImportResponse(data, nextFase, fatalError){
        // console.log(data);
        clearTimeout(wistjedatjeTimer);
        $('#newFile').hide();
        $('#SchoolAndSchoolLocationsImportBlock').height('auto').css('overflow','auto');
        $('#SchoolAndSchoolLocationsImportContainer').hide();
        $('#SchoolAndSchoolLocationsImportForm').show();


        clearRestartError();
        setSkipCheck(0);
        setHandleSubmitText();

        if(nextFase == 'start'){
            Navigation.refresh();
            return true;
        }
        if(nextFase == 'restartStartWithError'){
            $('#restartError').html(data);
            return true;
        }

        if(nextFase == 'showSkipValidation') {
            jQuery('#restartError').html((data));
            setSkipCheck(1);
            setHandleSubmitText('<?= __("Import verwerken zonder validatie")?>')
            $('#newFile').show();
        }

        if(nextFase == 'finish'){
            jQuery('#SchoolAndSchoolLocationsImportBlock').html((data));
        }

        if(fatalError){
            handleFatalError();
        }
    };

    var wistjedatjeTime = 10*1000;
    var wistjedatjeTimer
    var wistjedatjeNr = 0;
    var wistjedatjes = [
        '<?= __("Wist je dat je met de CO-Learning module de studenten zelf de toetsen kunt laten nakijken..")?>',
        '<?= __("Wist je dat we ook tekenvragen aanbieden waarme de student een tekening kan maken op het device..")?>',
        '<?= __("Wist je dat we nu ook infoschermen kennen waarmee je de student informatie kunt verschaffen over de komende vragen in de toets...")?>',
        '<?= __("Wist je dat we een voorleesfunctie hebben waarmee studenten de tekst van de toets voorgelezen kunnen krijgen...")?>'
    ];
    var wistjedatjesEl = jQuery('#wistjedatjes');


    function handleSubmit(){
        clearRestartError();
        $('#SchoolAndSchoolLocationsImportBlock').height($('#SchoolAndSchoolLocationsImportBlock').height()).css('overflow','scroll').css('padding','8px');
        $('#SchoolAndSchoolLocationsImportContainer').show();
        $('#SchoolAndSchoolLocationsImportForm').hide().submit();
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


</script>