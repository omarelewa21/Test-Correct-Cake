<?php
    $addEdit = (isset($question)) ? 'edit' : 'add';
    $rtti = (isset($question) && isset($question['question']) && isset($question['question']['rtti'])) ? $question['question']['rtti'] : '';
    $bloom = (isset($question) && isset($question['question']) && isset($question['question']['bloom'])) ? $question['question']['bloom'] : '';
    $miller = (isset($question) && isset($question['question']) && isset($question['question']['miller'])) ? $question['question']['miller'] : '';
?>

<div page="rtti" class="page" tabs="<?=$addEdit?>_question">
<?= __("Selecteer tot welke categorie deze vraag hoort binnen de RTTI-methode")?><br />
    <?=$this->Form->input('rtti', array('label' => false, 'type' => 'select', 'value' => $rtti, 'options' => ['null' => __("Geen"), 'R' => 'R', 'T1' => 'T1', 'T2' => 'T2', 'I' => 'I'], 'style' => 'width:750px;'))?>
    <?= __("Bloom")?><br />
    <?=$this->Form->input('bloom', array('label' => false, 'type' => 'select', 'value' => $bloom, 'options' => ['null' => __("Geen"), 'Onthouden' => __("Onthouden"), 'Begrijpen' => __("Begrijpen"), 'Toepassen' => __("Toepassen"), 'Analyseren' => __("Analyseren"), 'Evalueren' => __("Evalueren"), 'Creëren' => __("Creëren")], 'style' => 'width:750px;'))?>
    <?= __("Miller")?><br />
    <?=$this->Form->input('miller', array('label' => false, 'type' => 'select', 'value' => $miller, 'options' => ['null' => __("Geen"), 'Weten' => __("Weten"), 'Weten hoe' => __("Weten hoe"), 'Laten zien' => __("Laten zien"), 'Doen' => __("Doen")], 'style' => 'width:750px;'))?>
</div>