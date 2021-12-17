<?php
$action = isset($edit) ? 'edit' : 'add';
$owner = $owner ?? 'test';

if ($owner != 'group') {
?>
<div page="question" class="page active" tabs="<?= $action ?>_question" sources>
    <span class="title"><?= __('Bronnen') ?></span>
    <div class="loadhere"></div>
</div>
<?php } ?>