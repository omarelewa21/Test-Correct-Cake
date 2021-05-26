<div id="buttons">
    <a href="#" class="btn white mr2" onclick="Navigation.load('/rttiimport/index')">
        <span class="fa fa-backward mr5"></span>
        <?= __("Terug")?>
    </a>
</div>

<h1><?= __("RTTI import")?></h1>

<div class="block " id="RttiImportBlock">
    <div id="RttiImportContainer" style="display:none;overflow:scroll;padding: 8px;">
    <?= __("Een moment dit kan enige tijd duren (als in een paar minuten);")?>

    </div>
    <?= $this->Form->create('Rtti', array('id' => 'RttiImportForm', 'type' => 'file', 'method' => 'post', 'target' => 'frameUploadAttachment', 'url' => 'import')) ?>
    <div class="block-content" id="testsContainter">
        <table class='table'>
            <tr>
                <td><label><?= __("Email domein")?></label></td>
                <td>
                    <?= $this->Form->input('email_domain', array('style' => 'width:150px;', 'type' => 'email', 'placeholder' => __("Domein.nl"), 'value' => '', 'label' => false, 'verify' => 'notempty')) ?>
                </td>
            </tr>
            <tr>
                <td><label><?= __("Scheidingsteken")?></label></td>
                <td>
                    <?= $this->Form->input('separator', array('label' => false, 'type' => 'select', 'onchange' => '', 'options' => [';'=>'; puntkomma',','=>', komma'], 'style' => 'width:150px;', 'value' => ';')) ?>       </td>
            </tr>
            <tr>
                <td></td>
                <td>
                    <?= $this->Form->input('file', array('type' => 'file', 'label' => false, 'div' => false, 'onchange' => '')) ?>
                </td>
            </tr>
        </table>
        <iframe id="frameUploadAttachment" name="frameUploadAttachment" width="0" height="0" frameborder="0" style="position: absolute"></iframe>
    </div>
    <div class="block-footer">
        <a href="#" class="btn highlight mt5 mr5 pull-right" onclick="handleSubmit()">
        <?= __("Gegevens importeren")?>
        </a>
    </div>
    <?= $this->Form->end ?>
</div>
<script>

    function handleRttiImportResponse(data) {
        //clearTimeout(wistjedatjeTimer);
        jQuery('#RttiImportBlock').html((data));
    }
    ;

    function handleSubmit() {
        console.log('handle submit');
        $('#RttiImportBlock').height($('#RttiImportBlock').height()).css('overflow', 'scroll').css('padding', '8px');
        $('#RttiImportContainer').show();
        $('#buttons').show();
        $('#RttiImportForm').hide().submit();
    }

    var RttiImportSetupRun = false;

    function RttiImportSetup() {
        $(document).ready(function () {
            console.log('import setup');
            $('#buttons').hide();
        });
        RttiImportSetupRun = true;
    }

    if (!RttiImportSetupRun) {
        RttiImportSetup();
    }


</script>