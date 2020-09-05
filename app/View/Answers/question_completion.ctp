<?=$this->element('take_attachments', ['question' => $question]);?>

<?=$this->Form->create('Answer')?>
<?php
    $citoClass = '';
    if(substr_count($question['metadata'],'cito') > 0){
$citoClass = 'cito';
}
?>
<h1 class="question_type<?=$citoClass?>">Gatentekst [<?=$question['score']?>pt]<?=AppHelper::showExternalId($question);?></h1>
<div style="font-size: 20px;">
    <?
    if(isset($question['question_group']['text']) && !empty($question['question_group']['text'])) {
        echo '<p>'. $question['question_group']['text'].'</p>';
    }


    if(empty($answerJson)) {
        $answerJson = [];
    }

    $question_text = $question['question'];

    $searchPattern = "/\[([0-9]+)\]/i";
    $replacementFunction = function($matches) use ($question, $answerJson){
        $tag_id = $matches[1]-1; // the completion_question_answers list is 1 based but the inputs need to be 0 based
//        if(isset($question['completion_question_answers'][$tag_id])){
            $value = '';
            if(isset($answerJson[$tag_id])) {
                $value = $answerJson[$tag_id];
            }
            return $this->Form->input('Answer.'.$tag_id ,['id' => 'answer_' . $tag_id, 'value' => $value, 'spellcheck' => 'false', 'onchange' => 'Answer.answerChanged = true;', 'label' => false, 'div' => false, 'style' => 'display:inline-block; width:130px']);
//        }
    };

    $question_text = preg_replace_callback($searchPattern,$replacementFunction,$question_text);

/*
    foreach($question['completion_question_answers'] as $tag_id => $tag) {

        if(isset($answerJson[$tag_id])) {
            $value = $answerJson[$tag_id];

            if(empty($value)) {
            }else{
                $label = $answerJson[$tag_id];
            }
        }else{
            $value = '';
        }

        $question_text = str_replace('['.$tag['tag'].']', $this->Form->input('Answer.'.$tag_id ,['id' => 'answer_' . $tag_id, 'value' => $value, 'spellcheck' => 'false', 'onchange' => 'Answer.answerChanged = true;', 'label' => false, 'div' => false, 'style' => 'display:inline-block; width:130px']), $question_text);
    }
*/
    echo $question_text;
    ?>
</div>

<br clear="all" />
<?=$this->Form->end();?>
<?= $this->element('take_footer', ['has_next_question' => $has_next_question]); ?>

<script type="text/javascript">

    $(document).ready(function(){
      $('input').each(function(){
        $(this).on('keyup keypress',function(e){
          var keycode = e.keyCode || e.which;
          if(keycode === 13) {
            e.preventDefault();
            return false;
          }
        })
      });
    });

    Answer.answerChanged = false;
</script>
