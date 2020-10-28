<?=$this->element('take_attachments', ['question' => $question]);?>

<?=$this->Form->create('Answer')?>
<?php
    $citoClass = '';
    $isCitoQuestion = false;
    if((new AppController())->isCitoQuestion($question)){
        $citoClass = 'cito';
        $isCitoQuestion = true;
    }
?>
<h1 class="question_type <?=$citoClass?>">Rangschikvraag [<?=$question['score']?>pt]</h1>
<?=$this->element('take_question', ['question' => $question])?>

<?php
    echo sprintf('<div class="answer_container %s">',$citoClass);
?>

<div id="answers" style="">
    <?
    $answers = $question['ranking_question_answers'];

    if(!empty($answerJson)) {
        asort($answerJson);
        $newArray = [];
        foreach($answerJson as $id => $index) {
            foreach ($answers as $answer) {
                if($answer['id'] == $id) {
                    $newArray[] = $answer;
                }
            }
        }

        $answers = $newArray;
    }else{
        if(!$iCitoQuestion) {
            shuffle($answers);
        }
    }

    $i = -1;
    foreach($answers as $answer) {
        $i++;
        echo $this->Form->input('Answer.'.$answer['id'],
            [
                'value' => $i,
                'type' => 'hidden'
            ]
        );
        ?>
        <div style="padding:10px; margin-bottom: 2px; background: grey;" id="<?=$answer['id']?>" class="ranking_answer">
            <?=$answer['answer']?>
        </div>
        <?
    }
    ?>
</div></div>
<?=$this->Form->end();?>
<?= $this->element('take_footer', ['has_next_question' => $has_next_question]); ?>
<script type="text/javascript">
    $('#answers').sortable({
        stop: function( event, ui ) {
            var i = -1;
            $.each($('.ranking_answer'),
                function() {
                    i++;
                    var id = $(this).attr('id');
                    $('#Answer' + id).val(i);
                }
            );
        }
    });
    Answer.answerChanged = true;
</script>
<?=$this->element('question_styling',['question' => $question]);?>
