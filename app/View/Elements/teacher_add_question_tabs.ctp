<?php
    $cloneRequest = $cloneRequest ?? false;
    $action = isset($edit) ? 'edit' : 'add';
?>
<div class="tabs">
    <a href="#" class="btn grey highlight" page="question" tabs="<?= $action ?>_question">
        Opstellen
    </a>
    <a href="#" class="btn grey highlight" page="settings" tabs="<?= $action ?>_question">
        Instellingen
    </a>
    <?php if ($cloneRequest) { ?>
        <a href="#" class="btn grey highlight" page="info" tabs="<?= $action ?>_question">
            Info
        </a>
    <?php } ?>
</div>