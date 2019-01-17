<? if(count($question['attachments']) > 0 && !$hideExtra) { ?>
    <div style="width:250px; padding:20px; background: #294409; margin-left: 30px; float: right;">
        <div style="color: white; text-align: center; font-size: 22px; margin-bottom: 10px;">
            Bronnen
        </div>
        <?
        $v = 0;
        $i = 0;
        foreach($question['attachments'] as $attachemnt) {
            if($attachemnt['type'] == 'file') {
                $i++;
                ?>
                <a href="#" class="btn white" style="margin-bottom: 2px;" onclick="Answer.loadAttachment(<?=$attachemnt['id']?>);">
                    Bijlage #<?=$i?>
                </a>
            <?
            }elseif($attachemnt['type'] == 'video') {
                $v++;
                ?>
                <a href="#" class="btn white" style="margin-bottom: 2px;" onclick="Answer.loadAttachment(<?=$attachemnt['id']?>);">
                    Bekijk video <?=$v?>
                </a>
            <?
            }
        }
        ?>
    </div>
<? } ?>

<h1>Selectievraag</h1>
<div style="font-size: 20px;" id="multiCompletionQuestion">
    <?
    if(isset($question['question_group']['text']) && !empty($question['question_group']['text'])) {
        echo '<p>'. $question['question_group']['text'].'</p>';
    }

    $question_text = $question['question'];

    $tags = [];

//    foreach($question['completion_question_answers'] as $tag) {
//        $tags[$tag['tag']] = $tag['answer'];
//    }

    $count = (object)['nr' => 0];
    $question_text = preg_replace_callback(
        '/\[([0-9])\]/i',
        function ($matches) use ($count) {
            $tag_id = $matches[1];
            return $this->Form->input('Answer.'.$tag_id ,['id' => 'answer_' . $tag_id, 'type' => 'select', 'label' => false, 'div' => false, 'style' => 'display:inline-block; width:130px', 'options' => ['Selecteer'], 'disabled' => true]);
        },
        $question_text
    );



//    foreach($tags as $tag_id => $answers) {
//        $question_text = str_replace('['.$tag_id.']', $this->Form->input('Answer.'.$tag_id ,['id' => 'answer_' . $tag_id, 'type' => 'select', 'label' => false, 'div' => false, 'style' => 'display:inline-block; width:130px', 'options' => ['Selecteer'], 'disabled' => true]), $question_text);
//    }

    echo $question_text;
    ?>
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