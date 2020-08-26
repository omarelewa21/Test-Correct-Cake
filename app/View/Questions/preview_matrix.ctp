<?= $this->element('preview_attachments2019',['questions' => $questions, 'hideExtra' => $hideExtra]);?>
<style>
    table.matrix {
        border:0px solid grey;
    }
    .matrix thead th {
        border:1px solid grey;
        border-bottom-width:0;
        border-left-width:1px;
        border-right-width:0;
    }
    .matrix thead th:first-child {
        border-top-width:0;
        border-left-width:0;
    }
    .matrix thead th:last-child {
        border-right-width:1px;
    }
    #question_load .matrix td {
        border:1px solid grey;
        border-bottom-width:0;
        border-left-width:1px;
        border-right-width:0;
    }
    #question_load .matrix tr > td:last-child {
        border-right-width:1px;
    }
    #question_load .matrix tr:last-child td {
        border-bottom-width:1px;
    }
</style>
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
