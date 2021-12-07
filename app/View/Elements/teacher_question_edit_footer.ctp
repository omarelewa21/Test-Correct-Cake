<?php
$withSaving = $withSaving ?? true;
$editable = $editable ?? true;

if ($editable) {
    ?>
    <div class="question-editor-footer">
        <div class="question-editor-footer-button-container">
            <button type="button" onclick="Questions.closeQuestionEditor();"
                    class="button text-button button-md">
                <span><?= __("Annuleer") ?></span>
            </button>
            <button type="button" onclick="<?= $saveAction ?>;" class="button cta-button button-sm">
                <span><?= __("Vraag opslaan") ?></span>
            </button>
        </div>
    </div>
<?php } else { ?>
    <div class="popup-footer" style="display: flex; width: 100%; height: auto">
        <div style="display: flex; width: 100%; padding: 5px 2rem;">
            <button onclick="Popup.closeLast();"
                    class="button primary-button button-sm" style="margin-left: auto">
                <span><?= __("Annuleer") ?></span>
            </button>
        </div>
    </div>
<?php } ?>