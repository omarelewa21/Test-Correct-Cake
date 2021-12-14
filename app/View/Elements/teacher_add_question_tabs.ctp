<?php
    $cloneRequest = $cloneRequest ?? false;
    $action = isset($edit) ? 'edit' : 'add';
    $infoscreen = $infoscreen ?? false;
?>
<div class="tabs">
    <a href="#" class="btn grey highlight" page="question" tabs="<?= $action ?>_question">
        Opstellen
    </a>
    <?php if (!$infoscreen) { ?>
    <a href="#" class="btn grey" page="settings" tabs="<?= $action ?>_question">
        Instellingen
    </a>
    <?php } ?>
</div>