<?= $this->element('preview_attachments2019',['questions' => $questions, 'hideExtra' => $hideExtra]);?>

<h1>Gatentekst<?=AppHelper::showExternalId($question);?></h1>
<div style="font-size: 20px;">
    <?
    if(isset($question['question_group']['text']) && !empty($question['question_group']['text'])) {
        echo '<p>'. $question['question_group']['text'].'</p>';
    }

    $question_text = $question['question'];
/*
    $tags = [];

    foreach($question['completion_question_answers'] as $tag) {
        $tags[$tag['tag']] = $tag['answer'];
    }
*/
    $searchPattern = '/\[([0-9]+)\]/i';
    $replacementFunction = function($matches){
        $input_id = $matches[1]-1; // we need a input list which are 0 based
//        if(isset($tags[$tag_id])){
            return $this->Form->input('Answer.'.$input_id ,['id' => 'answer_' . $input_id, 'label' => false, 'div' => false, 'style' => 'display:inline-block; width:130px']);
//        }
    };

    $question_text = preg_replace_callback($searchPattern,$replacementFunction,$question_text);

//    foreach($question['completion_question_answers'] as $tag_id => $tag) {
//      $question_text = str_replace('['.$tag['tag'].']', $this->Form->input('Answer.'.$tag_id ,['id' => 'answer_' . $tag_id, 'label' => false, 'div' => false, 'style' => 'display:inline-block; width:130px']), $question_text);
//  }


    $citoClass = '';
    if(AppHelper::isCitoQuestion($question)){
        $citoClass = 'cito';
    }

    echo sprintf('<div class="answer_container %s">',$citoClass);


    echo $question_text;
    ?>
    </div>
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