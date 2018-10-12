<div class="popup-head">Geluidsfragment</div>
<div class="popup-content">

    <?
    if($type == 'add') {
        $action = '/upload_attachment/add';
    }else{
        $action = '/upload_attachment/edit/'.$owner . '/' . $owner_id . '/' . $id;
    }
    ?>

    <?=$this->Form->create('Question', ['type' => 'file', 'target' => 'soundAttachmentFrame', 'action' => $action, 'id' => 'QuestionUploadAttachment'])?>
    <table class="table table-striped">
        <tr>
            <th>Bestand</th>
            <td>
                <?=$this->Form->input('file', ['type' => 'file', 'label' => false])?>
            </td>
        </tr>
        <tr>
            <th>Pauzeerbaar</th>
            <td>
                <?=$this->Form->input('pausable', ['type' => 'checkbox', 'label' => false]) ?>
            </td>
        </tr>
        <tr>
            <th>Eenmalig afspelen</th>
            <td>
                <?=$this->Form->input('play_once', ['type' => 'checkbox', 'label' => false]) ?>
            </td>
        </tr>
        <tr>
            <th>Seconden voor antwoord</th>
            <td>
                <?=$this->Form->input('timeout', ['type' => 'number', 'label' => false, 'style' => 'width:50px;']) ?>
            </td>
        </tr>
    </table>
    <iframe width="1" height="1" style="visibility: hidden;" name="soundAttachmentFrame" id="soundAttachmentFrame"></iframe>
    <?=$this->Form->end();?>
</div>
<div class="popup-footer">
    <a href="#" class="btn grey mt5 mr5 pull-right" onclick="Popup.closeLast();">
        Annuleer
    </a>
    <a href="#" class="btn highlight mt5 mr5 pull-right" onclick="Loading.show(); $('#QuestionUploadAttachment').submit();">
        Opslaan
    </a>
</div>