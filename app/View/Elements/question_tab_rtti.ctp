<?php
    $addEdit = (isset($question)) ? 'edit' : 'add';
    $rtti = (isset($question) && isset($question['question']) && isset($question['question']['rtti'])) ? $question['question']['rtti'] : '';
    $bloom = (isset($question) && isset($question['question']) && isset($question['question']['bloom'])) ? $question['question']['bloom'] : '';
    $miller = (isset($question) && isset($question['question']) && isset($question['question']['miller'])) ? $question['question']['miller'] : '';
?>

<div page="rtti" class="page" tabs="<?=$addEdit?>_question">
    Selecteer tot welke categorie deze vraag hoort binnen de RTTI-methode<br />
    <?=$this->Form->input('rtti', array('label' => false, 'type' => 'select', 'value' => $rtti, 'options' => ['null' => 'Geen', 'R' => 'R', 'T1' => 'T1', 'T2' => 'T2', 'I' => 'I'], 'style' => 'width:750px;'))?>
    Bloom<br />
    <?=$this->Form->input('bloom', array('label' => false, 'type' => 'select', 'value' => $bloom, 'options' => ['null' => 'Geen', 'Onthouden' => 'Onthouden', 'Begrijpen' => 'Begrijpen', 'Toepassen' => 'Toepassen', 'Analyseren' => 'Analyseren', 'Evalueren' => 'Evalueren', 'Creëren' => 'Creëren'], 'style' => 'width:750px;'))?>
    Miller<br />
    <?=$this->Form->input('miller', array('label' => false, 'type' => 'select', 'value' => $miller, 'options' => ['null' => 'Geen', 'Weten' => 'Weten', 'Weten hoe' => 'Weten hoe', 'Laten zien' => 'Laten zien', 'Doen' => 'Doen'], 'style' => 'width:750px;'))?>
</div>