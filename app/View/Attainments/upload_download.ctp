

<h1>Leerdoelen upload</h1>
<h3>Upload hier een excel met bestaande of nieuwe Eindtermen. Voer op de commandline de import uit.</h3>

<div class="block " id="AttainmentsImportBlock">
    <div id="AttainmentsImportContainer" style="display:none;overflow:scroll;padding: 8px;">
        Een moment dit kan enige tijd duren (als in een paar minuten);
        <h4 style="color:green;" id="wistjedatjes"></h4>
    </div>
    <?=$this->Form->create('Attainments', array('id' => 'AttainmentsImportForm', 'type' => 'file', 'method' => 'post', 'target' => 'frameUploadAttachment', 'url' => 'upload'))?>
    <div class="block-content" id="testsContainter">
        <table class='table'>
            <tr>
                <td></td>
                <td>
                    <?=$this->Form->input('file', array('type' => 'file',  'label' => false, 'div' => false, 'onchange' => '')) ?>
                </td>
            </tr>
        </table>

        <iframe id="frameUploadAttachment" name="frameUploadAttachment" width="0" height="0" frameborder="0" style="position: absolute"></iframe>
    </div>
    <div class="block-content">
        <table class='table'>
            <tr>
                <td></td>
                <td>
                    <a href="#" class="btn highlight mt5 mr5 pull-left" onclick="handleSubmit()">
                        Upload
                    </a>
                </td>
            </tr>
        </table>
    </div>
    <?=$this->Form->end?>
</div>
<h1>Eindtermen download</h1>
<div class="block " id="AttainmentsExportBlock">

     <div class="block-content" >
        <table class='table'>
            <tr>
                <td></td>
                <td>
                    <a href="#" class="btn highlight mt5 mr5 pull-left" onclick="handleDownload()">
                        Download
                    </a>
                </td>
            </tr>
        </table>
    </div>

</div>

<script>

    function handleAttainmentsImportResponse(data){
        console.log(data);
        jQuery('#AttainmentsImportBlock').html((data));
    };

    function handleDownload(){
        window.open('attainments/download','_blank');
    }



    function handleSubmit(){
        $('#QAttainmentsImportBlock').height($('#AttainmentsImportBlock').height()).css('overflow','scroll').css('padding','8px');
        $('#AttainmentsImportContainer').show();
        $('#AttainmentsImportForm').hide().submit();
    }



</script>