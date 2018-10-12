<div style="font-size: 20px;">
    <?
    if(isset($question['answer_parent_questions'][0]['group_question']['question']) && !empty($question['answer_parent_questions'][0]['group_question']['question'])) {
        ?>
        <p><strong><?=$question['answer_parent_questions'][0]['group_question']['name']?></strong></p>
        <p><?=$question['answer_parent_questions'][0]['group_question']['question']?></p>
        <?
    }
    ?>
    <?=$question['question']?>
</div>