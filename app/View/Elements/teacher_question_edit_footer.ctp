<?php
    $withSaving = $withSaving ?? true;
?>
<div class="question-editor-footer">
    <div class="question-editor-footer-button-container">
        <button type="button" onclick="Questions.closeQuestionEditor();"
                class="button text-button button-md">
            <span><?= __("Annuleer") ?></span>
        </button>
        <?php if ($withSaving) { ?>
        <button type="button" onclick="<?= $saveAction ?>;" class="button cta-button button-sm">
            <span><?= __("Vraag opslaan") ?></span>
        </button>
        <?php } ?>
    </div>
</div>
