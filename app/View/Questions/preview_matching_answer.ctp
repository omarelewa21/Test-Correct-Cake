<?php
    $citoClass = '';
    if(AppHelper::isCitoQuestion($question)){
        $citoClass = 'cito';
    }

    echo sprintf('<div class="answer_container %s">',$citoClass);
?>
<div style="font-size: 20px;">
    <?

    $listLeft = [];
    $listRight = [];

    foreach($question['matching_question_answers'] as $option) {
        if($option['type'] == 'LEFT') {
            ?>
            <div style="margin-bottom: 5px; border:1px grey dotted; text-align: center;  padding:20px;">
                <strong><?= $option['answer'] ?></strong>
                <?
                foreach ($question['matching_question_answers'] as $rightOption) {

                    if ($rightOption['type'] == 'RIGHT' && $rightOption['correct_answer_id'] == $option['id'] && !empty($rightOption['answer'])) {
                        ?>
                        <div style="background: grey; padding:10px; margin: 2px;">
                            <?= $rightOption['answer'] ?>
                        </div>
                    <?
                    }
                }
                ?>
            </div>
        <?
        }
    }
    ?>
</div>
</div>
<?=$this->element('question_styling',['question' => $question]);?>