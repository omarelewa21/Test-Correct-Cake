<?php $action = isset($edit) ? 'edit' : 'add'; ?>
<div page="settings" class="page" tabs="<?= $action ?>_question">
    <span class="title"><?= __('Tags') ?></span>
    <?=$this->Form->input('tags', array('label' => false, 'type' => 'select', 'multiple' => true, 'style' => 'width:750px;'))?>
</div>