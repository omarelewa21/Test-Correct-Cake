<div class="question-editor-footer">
    <div class="question-editor-footer-button-container">
        <button type="button" onclick="closeQuestionEditor();"
                class="button text-button button-md">
            <span><?= __("Annuleer") ?></span>
        </button>

        <button type="button" onclick="<?= $saveAction ?>;" class="button cta-button button-sm">
            <span><?= __("Vraag opslaan") ?></span>
        </button>
    </div>
</div>
<script>
    function closeQuestionEditor() {
        Navigation.back();
        $('.question-editor-header').slideUp();
        $('#container').removeClass('question-editor');
        $('#header, #tiles').slideDown();
    }
</script>
