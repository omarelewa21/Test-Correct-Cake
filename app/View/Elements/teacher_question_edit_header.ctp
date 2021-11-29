<div class="question-editor-header">
    <div class="question-title">
        <div class="icon-arrow">
            <?= $this->element('edit', ['style' => 'position:relative;left:-5px;']) ?>
        </div>
        <h2><?= $question_type ?></h2>
    </div>
    <div class="question-test-name">
        <span><?= __('test') ?>:</span>
        <span class="bold"><?= $test_name ?></span>
    </div>
</div>

<script>
    (function () {
        $('#container').addClass('question-editor');
        $('#header, #tiles').hide();
    })()

</script>
