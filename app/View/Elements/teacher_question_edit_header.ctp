<?php
$icon = isset($icon) && $icon != null ? $icon : 'edit';
$editable = $editable ?? true;

if ($editable) {
    ?>
    <div class="question-editor-header">
        <div class="question-title">
            <div class="icon-arrow">
                <?= $this->element($icon, ['style' => 'position:relative;left:-5px;']) ?>
            </div>
            <h2><?= $question_type ?></h2>
        </div>
        <div class="question-test-name">
            <?php if (!empty($test_name)) { ?>
                <span><?= __('Toets') ?>:</span>
                <span class="bold"><?= $test_name ?></span>
            <?php } ?>
        </div>
    </div>

    <script>
        (function () {
            $('#container').addClass('question-editor');
            $('#header, #tiles').hide();
        })()

    </script>
<?php } else { ?>
    <div class="popup-head"><?= $question_type ?></div>
<?php } ?>