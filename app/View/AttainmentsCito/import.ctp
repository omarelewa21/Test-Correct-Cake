

<h1><?= __("Leerdoelen aan CITO vragen koppelen")?></h1>

<div class="block " id="AttainmentsImportBlock">
    <div id="AttainmentsImportContainer" style="display:none;overflow:scroll;padding: 8px;">
    <?= __("Een moment dit kan enige tijd duren (als in een paar minuten);")?>
        <h4 style="color:green;" id="wistjedatjes"></h4>
    </div>
    <?=$this->Form->create('Attainments', array('id' => 'AttainmentsImportForm', 'type' => 'file', 'method' => 'post', 'target' => 'frameUploadAttachment', 'url' => 'import'))?>
    <div class="block-content" id="testsContainter">
        <table class='table'>
            <tr>
                <td>
                <?= __("Vak")?>
                </td>
                <td>
                    <?=$this->Form->input('subject_id', array('type' => 'select', 'label' => false, 'div' => false, 'options' => $subjects))?>
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
        <?= __("Import verwerken")?>
        </a>
    </div>
    <?=$this->Form->end?>
</div>

<script>

    function handleAttainmentsImportResponse(data){
        clearTimeout(wistjedatjeTimer);
      jQuery('#AttainmentsImportBlock').html((data));
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
        $('#QAttainmentsImportBlock').height($('#AttainmentsImportBlock').height()).css('overflow','scroll').css('padding','8px');
        $('#AttainmentsImportContainer').show();
        $('#AttainmentsImportForm').hide().submit();
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