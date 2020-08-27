<?= $this->element('preview_attachments2019',['questions' => $questions, 'hideExtra' => $hideExtra]);?>
<h1>
    <?
    if($question['subtype'] == 'SingleChoice') {
        ?>Matrix<?
    }else{
        ?>Matrix subtype ONBEKEND<?
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
        $answerSubQuestionReference = [];
        foreach($question['matrix_question_answer_sub_questions'] as $a){
            $answerSubQuestionReference[$a['matrix_question_sub_question_id']] = $a['matrix_question_answer_id'];
        }

        echo $this->element('question_matrix',[
            'question' => $question,
            'answerSubQuestionReference' => $answerSubQuestionReference
        ]);
    ?>
</div>



<? if(isset($next_question)) { ?>
    <br />
    <center>
        <a href="#" class="btn highlight large" onclick="TestPreview.loadQuestionPreview(<?=$test_id?>, <?=$next_question?>);">
            <span class="fa fa-check"></span>
            Volgende vraag
        </a>
    </center>
<? } ?>
