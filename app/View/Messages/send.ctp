<div class="popup-head"><?= __("Bericht versturen")?></div>
<div class="popup-content">
    <?=$this->Form->create('Message')?>
    <?=$this->Form->input('subject', ['type' => 'text', 'placeholder' => __("Onderwerp"), 'label' => false, 'verify' => 'notempty'])?>
    <?=$this->Form->input('message', ['type' => 'textarea', 'placeholder' => __("Bericht"), 'style' => 'width:438px; height:150px', 'label' => false, 'verify' => 'notempty'])?>
    <?=$this->Form->end()?>
</div>
<div class="popup-footer">
    <a href="#" class="btn grey mt5 mr5 pull-right" onclick="Popup.closeLast();">
    <?= __("Sluiten")?>
    </a>
    <a href="#" class="btn grey mt5 mr5 highlight pull-right" id="btnSendMessage">
    <?= __("Bericht versturen")?>
    </a>
</div>

<script type="text/javascript">
    $('#MessageSendForm').formify(
        {
            confirm : $('#btnSendMessage'),
            onsuccess : function(result) {

                Popup.closeLast();
                Notify.notify('<?= __("Bericht verstuurd")?>', "info");
            },
            onfailure : function(result) {
                alert('error');
            }
        }
    );
</script>