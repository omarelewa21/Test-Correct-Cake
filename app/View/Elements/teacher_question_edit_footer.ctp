<div
    style="padding: 0 20px; font-family: Nunito, sans-serif;box-shadow: 0 3px 8px 0 rgba(4,31,116,.3); align-items: center; box-sizing: border-box ;height:70px; background-color:white; position: fixed; bottom:0; width:100%; left:0; display:flex;  justify-content:end"
    class="footer">
    <div style="margin-left:auto">
        <button type="button" onclick="closeQuestionEditor();"
                class="button text-button button-md">
            <span><?= __("Annuleer") ?></span>
        </button>

        <button type="button" onclick="<?= $saveAction ?>; closeQuestionEditor()" class="button cta-button button-sm">
            <span><?= __("Vraag opslaan") ?></span>
        </button>
    </div>
</div>
<script>
    function closeQuestionEditor() {
        $('#header, #tiles').slideDown();Navigation.back();
    }
</script>
