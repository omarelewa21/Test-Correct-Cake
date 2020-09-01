<?= $this->element('preview_attachments2019',['questions' => $questions, 'hideExtra' => $hideExtra]);?>
<?php
    $citoClass = '';
    if(substr_count($question['metadata'],'cito') > 0){
$citoClass = 'cito';
}
?>
<h1 class="question_type <?=$citoClass?>">Selectievraag<?=AppHelper::showExternalId($question);?></h1>
<div style="font-size: 20px;" id="multiCompletionQuestion">
    <?
    if(isset($question['question_group']['text']) && !empty($question['question_group']['text'])) {
        echo '<p>'. $question['question_group']['text'].'</p>';
    }

    $question_text = $question['question'];

    $tags = [];

    echo sprintf('<div class="answer_container %s">',$citoClass);

//    foreach($question['completion_question_answers'] as $tag) {
//        $tags[$tag['tag']] = $tag['answer'];
//    }

    $count = (object)['nr' => 0];
    $question_text = preg_replace_callback(
        '/\[([0-9]+)\]/i',
        function ($matches) use ($count) {
            $tag_id = $matches[1];
            return $this->Form->input('Answer.'.$tag_id ,['id' => 'answer_' . $tag_id, 'type' => 'select', 'label' => false, 'div' => false, 'style' => 'display:inline-block; width:130px', 'options' => ['Selecteer'], 'disabled' => true]);
        },
        $question_text
    );



//    foreach($tags as $tag_id => $answers) {
//        $question_text = str_replace('['.$tag_id.']', $this->Form->input('Answer.'.$tag_id ,['id' => 'answer_' . $tag_id, 'type' => 'select', 'label' => false, 'div' => false, 'style' => 'display:inline-block; width:130px', 'options' => ['Selecteer'], 'disabled' => true]), $question_text);
//    }

    echo $question_text;
        echo '</div>';
    ?>
</div>

<br clear="all" />

<? if(isset($next_question)) { ?>
    <br />
    <center>
        <a href="#" class="btn highlight large" onclick="TestPreview.loadQuestionPreview(<?=$test_id?>, <?=$next_question?>);">
            <span class="fa fa-check"></span>
            Volgende vraag
        </a>
    </center>
<? } ?>