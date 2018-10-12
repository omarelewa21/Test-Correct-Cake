<?=$this->element('take_attachments', ['question' => $question]);?>

<?=$this->Form->create('Answer')?>

<h1>Selectievraag [<?=$question['score']?>pt]</h1>
<div style="font-size: 20px;">
    <?
    if(isset($question['question_group']['text']) && !empty($question['question_group']['text'])) {
        echo '<p>'. $question['question_group']['text'].'</p>';
    }


    if(empty($answerJson)) {
        $answerJson = [];
    }

    $question_text = $question['question'];

    $tags = [];

    foreach($question['completion_question_answers'] as $tag_id => $tag) {

        $tags[$tag['tag']][$tag['answer']] = $tag['answer'];

        if(isset($answerJson[$tag_id])) {
            $value = $answerJson[$tag_id];

            if(!empty($value)) {
                $label = $answerJson[$tag_id];
            }
        }else{
            $value = '';
        }
    }

    foreach($tags as $tag_id => $answers) {

        $keys = array_keys($answers);
        shuffle($keys);
        $random = array(
            0 => 'Selecteer'
        );
        foreach ($keys as $key) {
            $random[$key] = $answers[$key];
        }

        $answers = $random;

        if(isset($answerJson[$tag_id])) {
            $value = $answerJson[$tag_id];
        }else{
            $value = 0;
        }

        $question_text = str_replace('['.$tag_id.']', $this->Form->input('Answer.'.$tag_id ,['id' => 'answer_' . $tag_id, 'class' => 'multi_selection_answer', 'onchange' => 'Answer.answerChanged = true', 'value' => $value, 'options' => $answers, 'label' => false, 'div' => false, 'style' => 'display:inline-block; width:150px']), $question_text);
    }


    echo $question_text;
    ?>
</div>

<br clear="all" />
<?=$this->Form->end();?>
<?= $this->element('take_footer', ['has_next_question' => $has_next_question]); ?>

<script type="text/javascript">

    function saveQuestion() {

        var ok = true;

        $.each($('.multi_selecyion_answer'), function() {
            if($(this).val() == 0) {
                ok = false;
            }
        });

        if(ok) {
            Answer.saveAnswer();
        }else{
            Notify.notify('Niet alle opties geselecteerd', 'error');
        }
    }

    Answer.answerChanged = false;
</script>