<?=$this->element('take_attachments', ['question' => $question]);?>

<?=$this->Form->create('Answer')?>
<h1><?= $question['subtype'] == 'Matching' ? 'Combineervraag' : 'Rubriceer-vraag' ?> [<?=$question['score']?>pt]</h1>
<?=$this->element('take_question', ['question' => $question])?>

<div style="font-size: 20px;">
    <?

    $listLeft = [];
    $listRight = [];

    foreach($question['matching_question_answers'] as $option) {
        if($option['type'] == 'LEFT') {
            $listLeft[] = $option;
        }elseif($option['type'] == 'RIGHT') {
            $listRight[] = $option;
        }
    }
    ?>
</div>

<div style="width:300px; padding:20px; float:left;">
    <?
    foreach($listLeft as $item) {
        ?>
        <div style="margin-bottom: 5px; border:1px grey dotted; text-align: center; height:130px; padding:20px;" class="left_item" id="<?=$item['id']?>">
            <strong><?=$item['answer']?></strong>
        </div>
    <?
    }
    ?>
</div>

<div style="width:200px; float:left; margin-left: 20px;">
    <?

    if(empty($answer['json'])) {
        $answerJson = [];
    }else{
        $answerJson = json_decode($answer['json'], true);
    }

    shuffle($listRight);
    foreach($listRight as $item) {

        echo $this->Form->input('Answer.' . $item['id'], [
            'class' => 'matching_answer',
            'value' => isset($answerJson[$item['id']]) ? $answerJson[$item['id']] : '',
            'type' => 'hidden'
        ]);

        ?>
        <div style="background: grey; padding:10px; margin: 2px;" id="<?=$item['id']?>" class="right_item">
            <?=$item['answer']?>
        </div>

        <?
        if(isset($answerJson[$item['id']]) && !empty($answerJson[$item['id']])) {
            ?>
            <script type="text/javascript">

                var leftPos = $('#<?=$answerJson[$item['id']]?>').position();

                $('#<?=$item['id']?>').css({
                    'position' : 'absolute',
                    'left' : leftPos.left + 'px',
                    'top' : (leftPos.top + 40) + 'px',
                    'width' : '200px'
                })
            </script>
           <?
        }
    }
    ?>
</div>

<br clear="all" />
<?=$this->Form->end();?>
<?= $this->element('take_footer', ['has_next_question' => $has_next_question]); ?>

<script>
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
                        btnOk : 'Oke',
                        title : 'Onvolledig',
                        message : 'Niet alle items geplaatst'
                    });
                    error = true;
                    return false;
                }

                <? if($question['subtype'] == 'Matching') {?>
                if ($.inArray(val, answers) != -1) {

                    Popup.message({
                        btnOk : 'Oke',
                        title : 'Incorrect',
                        message : 'Maar 1 item per blok plaatsen'
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