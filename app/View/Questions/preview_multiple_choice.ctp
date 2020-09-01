<?= $this->element('preview_attachments2019',['questions' => $questions, 'hideExtra' => $hideExtra]);?>

<h1>
    <?php
    if($question['subtype'] == 'TrueFalse') {
        ?>Juist / Onjuist<?
    }else{
        ?>Multiple choice<?
    }
    ?>
</h1>

<div style="font-size: 20px;">
    <?
    if(isset($question['question_group']['text']) && !empty($question['question_group']['text'])) {
        echo '<p>'. $question['question_group']['text'].'</p>';
    }
    ?>
    <?=$question['question']?><br />

    <?php
        $citoClass = '';
        if(AppHelper::isCitoQuestion($question)){
            $citoClass = 'cito';
        }

        echo sprintf('<div class="answer_container %s">',$citoClass);


        $first = true;
        $radioOptions = [];
        $useRadio = false;
        $default = [];
        if($question['subtype'] == 'TrueFalse' || $question['selectable_answers'] == 1){
            $useRadio = true;
            $label = '<div class="radio_'.$question['id'].'">';
        }

        if($useRadio){
            echo $this->element('question_multiple_choice_radio_answers',['question' => $question,'rating' => true]);
        } else {
            echo $this->element('question_multiple_choice_regular_answers',['question' => $question,'rating' => true]);
        }

    ?>
    </div>
</div>
<div style="clear:both;"></div>

<?php if(isset($next_question)) { ?>
    <br />
    <center>
        <a href="#" class="btn highlight large" onclick="TestPreview.loadQuestionPreview(<?=$test_id?>, <?=$next_question?>);">
            <span class="fa fa-check"></span>
            Volgende vraag
        </a>
    </center>
<?php } ?>
