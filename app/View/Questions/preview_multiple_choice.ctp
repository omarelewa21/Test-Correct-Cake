<?= $this->element('preview_attachments2019',['questions' => $questions, 'hideExtra' => $hideExtra]);?>

<h1>
    <?
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

    <?

    $first = true;
    $radioOptions = [];
    $useRadio = false;
    $default = [];
    if($question['subtype'] == 'TrueFalse' || $question['selectable_answers'] == 1){
        $useRadio = true;
        $label = '<div class="radio_'.$question['id'].'">';
    }

    foreach($question['multiple_choice_question_answers'] as $answer) {
        if($useRadio){
            if($first) $default = $answer['id'];
            $radioOptions[$answer['id']] = ' '.$answer['answer'];
        } else {
            echo '<div>'.$this->Form->input('Answer.'.$answer['id'], [
                'value' => 1,
                'div' => false,
                'type' => 'checkbox',
                'checked' => $first,
                'label' => false,
                'class' => 'multiple_choice_option'
            ]);
            echo '&nbsp;'.$answer['answer'].'</div><br />';
            $first = false;
        }
    }

    if($useRadio){
        echo $this->Form->input('Question.'.$question['id'], [
            'type' => 'radio',
            'legend'=> false,
            'label' => false,
            'div' => [], //array('class' => 'btn-group', 'data-toggle' => 'buttons'),
            'class' => 'multiple_choice_option single_choice_option input_'.$question['id'],
            'default'=> $default,
            'before' => $label,//'<div class="btn btn-primary">',
            'separator' => '</div><br/>'.$label,//'</label><div class="btn btn-primary">',
            'after' => '</div>',
            'options' => $radioOptions,//array('1' => 'Radio 1', '2' => 'Radio 2'),
        ]).'<br/>';
    }
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
