<style>
    body {
        font-family: Arial;
    }
</style>

<?
$i = 1;
foreach($participants as $participant) {
    $i++;
    if(count($participant['answers']) > 0) {
        ?>
        <h1>
            Antwoorden
            <?= $participant['user']['name_first'] ?>
            <?= $participant['user']['name_suffix'] ?>
            <?= $participant['user']['name'] ?>
        </h1>
        <?
        $q = 0;
        foreach($questions as $question) {

            $question = $question['question'];

            $q++;
            ?>
            <h2>Vraag #<?=$q?></h2>
            <?
            foreach ($participant['answers'] as $answer) {

                if($answer['question_id'] != $question['id']) {
                    continue;
                }

                $answerJson = json_decode($answer['json'], true);

                if(empty($answerJson)) {
                    continue;
                }

                if($question['type'] == 'OpenQuestion') {
                    echo $answerJson['value'];
                }

/*
                if($question['type'] == 'CompletionQuestion') {

                    $fullAnswer = $question['question'];
                    foreach($answerJson as $index => $value) {
                        $fullAnswer = str_replace('[' . ($index + 1) . ']', '<strong>'.$value . '</strong>', $fullAnswer);
                    }

                    echo $fullAnswer;
                }
*/
                if($question['type'] == 'CompletionQuestion') {
                    $fullAnswer = $question['question'];
                    foreach($answerJson as $index => $value) {
                        $tag_id = $index;
                        if($question['subtype'] == 'completion'){
                            $tag_id += 1;
                        }
                        $fullAnswer = str_replace('[' . ($tag_id) . ']', '<strong>'.$value . '</strong>', $fullAnswer);
                    }

                    echo $fullAnswer;
                }

                if($question['type'] == 'MatchingQuestion') {

                    $listLeft = [];
                    $listRight = [];

                    foreach($question['matching_question_answers'] as $option) {
                        if($option['type'] == 'LEFT') {
                            $listLeft[] = $option;
                        }elseif($option['type'] == 'RIGHT') {
                            $listRight[] = $option;
                        }
                    }

                    $questionAnswers = $question['matching_question_answers'];

                    foreach($listLeft as $listLeftItem) {
                        echo '<strong>'.$listLeftItem['answer'].":</strong> ";

                        if(!empty($answerJson)) {
                            foreach($answerJson as $item_right => $item_left) {
                                if($item_left == $listLeftItem['id']) {
                                    foreach($listRight as $listRightItem) {
                                        if($listRightItem['id'] == $item_right) {
                                            echo $listRightItem['answer'].', ';
                                        }
                                    }
                                }
                            }
                        }
                        echo '<Br />';
                    }
                }

                if($question['type'] == 'RankingQuestion') {
                    asort($answerJson);

                    foreach($answerJson as $answer_id => $order) {
                        foreach($question['ranking_question_answers'] as $answer) {
                            if($answer['id'] == $answer_id) {
                                echo $answer['answer'].'<Br />';
                            }
                        }
                    }
                }

                if($question['type'] == 'MultipleChoiceQuestion') {
                    foreach($question['multiple_choice_question_answers'] as $questionAnswer) {

                        foreach($answerJson as $answer_id => $value) {
                            if($answer_id == $questionAnswer['id']) {
                                if($value == '1') {
                                    echo '<strong>V</strong> ';
                                }
                            }
                        }

                        echo $questionAnswer['answer'].'<br />';
                    }
                }

                if($question['type'] == 'DrawingQuestion') {
                    ?>
                    <img src="<?=$answerJson['answer']?>" width="100%" />
                <?
                }
            }

        }
        ?>
        <?php if(count($participants)-1 !== $i): ?>
          <div style="page-break-after: always;"></div>
        <?php endif; ?>
    <?php
    }
}
?>
