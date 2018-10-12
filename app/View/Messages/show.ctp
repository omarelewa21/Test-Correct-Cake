<div class="popup-head"><?=$message['subject']?></div>
<div class="popup-content">
    <?=$message['message']?>
</div>
<div class="popup-footer">
    <a href="#" class="btn grey mt5 mr5 pull-right" onclick="Popup.closeLast();">
        Sluiten
    </a>
    <?if($message['user_id'] != AuthComponent::user('id')) { ?>
        <a href="#" class="btn highlight mt5 mr5 pull-right" onclick="Message.reply(<?=$message['user_id']?>)">
            Reageren
        </a>
    <? } ?>
</div>
