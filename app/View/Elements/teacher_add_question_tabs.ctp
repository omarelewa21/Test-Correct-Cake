<?php
    $cloneRequest = $cloneRequest ?? false;
    $action = isset($edit) ? 'edit' : 'add';
    $infoscreen = $infoscreen ?? false;
?>
<div class="tabs" selid="tabs">
    <a href="#" class="btn grey highlight" page="question" tabs="<?= $action ?>_question" selid="tab-question">
        Opstellen
    </a>
    <?php if (!$infoscreen) { ?>
    <a href="#" class="btn grey" page="settings" tabs="<?= $action ?>_question" selid="tab-settings">
        Instellingen
    </a>
    <?php } ?>
</div>