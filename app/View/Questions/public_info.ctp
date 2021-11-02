<div class="popup-head">
<?= __("Openbaar maken")?> <span class="fa fa-info-circle" onclick="Popup.load('/questions/public_info', 500);" style="cursor:pointer"></span>
</div>
<div class="popup-content">
    <p>
    <?= __("In de nabije toekomst zullen wij Test-Correct uitbreiden met de Nationale Itembank. U krijgt dan de mogelijkheid om items van uw vakcollega’s uit het hele land te gebruiken.")?>
    </p>
    <p>
    <?= __("Als deze optie is aangevinkt geeft u uw vakcollega’s (buiten uw school) de mogelijkheid om dit item te vinden in de Nationale Itembank en te gebruiken binnen hun eigen toets.")?>
    </p>
    <p>
    <?= __("U blijft overigens auteur van dit item. U blijft de zeggenschap houden, ook als u wenst te delen met uw collega’s.")?>
    </p>
</div>
<div class="popup-footer">
    <a href="#" class="btn grey mt5 mr5 pull-right" onclick="Popup.closeLast();">
    <?= __("Sluiten")?>
    </a>
</div>