<?=$this->element('take_attachments', ['question' => $question]);?>
<?php
    $citoClass = '';
    if(substr_count($question['metadata'],'cito') > 0){
$citoClass = 'cito';
}
?>
<?=$this->Form->create('Answer')?>
<h1 class="question_type <?=$citoClass?>"><?= $question['subtype'] == __("Matching") ? __("Combineervraag") : __("Rubriceer-vraag") ?> [<?=$question['score']?>pt]<?=AppHelper::showExternalId($question);?></h1>
<?=$this->element('take_question', ['question' => $question])?>

    <?

        echo sprintf('<div class="answer_container %s">',$citoClass);


    $listLeft = [];
    $listRight = [];

    foreach($question['matching_question_answers'] as $option) {
        if($option['type'] == 'LEFT') {
            $listLeft[] = $option;
        }elseif($option['type'] == 'RIGHT') {
            $listRight[] = $option;
        }
    }

    shuffle($listRight);

    if(empty($answer['json'])) {
        $answerJson = [];
    }else{
        $answerJson = json_decode($answer['json'], true);
    }

    echo sprintf('<div class="matching_item_container">');
    foreach($listRight as $item) {

        echo $this->Form->input('Answer.' . $item['id'], [
            'class' => 'matching_answer',
            'value' => isset($answerJson[$item['id']]) ? $answerJson[$item['id']] : '',
            'type' => 'hidden'
        ]);

        echo sprintf('<div id="%s" class="right_item matching_item">%s</div>',$item['id'],$item['answer']);
    }
    echo sprintf('</div>');
    echo sprintf('<div style="clear:both;height:25px;"></div>');

    foreach($listLeft as $item) {
            echo sprintf('<div class="matching_container"><div class="matching_container_name"><strong>%s</strong></div><div class="matching_drop_container left_item" id="%s"></div></div>',$item['answer'],$item['id']);
    }

    ?>

    <script>
        var c = $('.matching_item_container');
        c.height(c.height());;
        c.css({'overflow':'visible'});
    </script>
<?php

    $multiplier = [];
    foreach($listRight as $item) {
        if(isset($answerJson[$item['id']]) && !empty($answerJson[$item['id']])) {
                $id = $answerJson[$item['id']];
                if(isset($multiplier[$id])){
                    $multiplier[$id]++;
                }
                else {
                    $multiplier[$id] = 0;
                }
            ?>
                <script type="text/javascript">

                    var leftPos = $('#<?=$answerJson[$item['id']]?>').position();
                    var left = leftPos.left + 10;
                    var counter = <?=$multiplier?>;
                    if(counter > 0){

                    }
                    var dragItem = $('#<?=$item['id']?>');
                    var ownHeight = dragItem.outerHeight();
                    dragItem.css({
                        'position' : 'absolute',
                        'left' : left + 'px',
                        'top' : (leftPos.top + 10 + ((ownHeight+2)*<?=$multiplier[$id]?>)) + 'px',
                        'width' : '200px'
                    })
                </script>
            <?php
        }
    }
    ?>
</div>

<br clear="all" />
<?=$this->Form->end();?>
<?= $this->element('take_footer', ['has_next_question' => $has_next_question]); ?>

<script>
    var topHeights = {};
    var maxParentHeight = 250;
    $(function() {
        $( ".right_item" ).draggable();
        $( ".left_item" ).droppable({
            drop: function( event, ui ) {
                var left_id = this.id;
                var right_id = ui.helper[0].id;
                $('#Answer' + right_id).val(left_id);
                Answer.answerChanged = true;

            }
        });
    });


    function saveAnswer() {

        var answers = [];
        var error = false;

        if(Answer.answerChanged) {
            $.each($('.matching_answer'), function () {

                var val = $(this).val();

                if (val == '') {
                    Popup.message({
                        btnOk : '<?= __("Oke")?>',
                        title : '<?= __("Onvolledig")?>',
                        message : '<?= __("Niet alle items geplaatst")?>'
                    });
                    error = true;
                    return false;
                }

                <? if($question['subtype'] == 'Matching') {?>
                if ($.inArray(val, answers) != -1) {

                    Popup.message({
                        btnOk : '<?= __("Oke")?>',
                        title : '<?= __("Incorrect")?>',
                        message : '<?= __("Maar 1 item per blok plaatsen")?>'
                    });

                    error = true;
                    return false;
                }
                <? } ?>

                answers.push(val);
            });

            if (!error) {
                Answer.saveAnswer();
            }
        }else{
            Navigation.load(TestTake.nextUrl);
        }
    }

    Answer.answerChanged = false;
</script>
<?=$this->element('question_styling',['question' => $question]);?>