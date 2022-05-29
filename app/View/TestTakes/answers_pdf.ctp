<style>
    body {
        font-family: Helvetica;
    }
    .questionContainer table{
        border-spacing: 0 !important;
    }
    .questionContainer table, .questionContainer th, .questionContainer td {
        border: 1px solid rgb(100, 99, 99);
    }
    .questionContainer th, .questionContainer td {
        padding: 0.1rem 0.2rem;
    }
</style>

<?
$i = 1;
foreach($participants as $participant) {
    $i++;
    if(count($participant['answers']) > 0) {
        ?>
        <h1>
        <?= __("Antwoorden")?>
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
            <h2><?= __("Vraag")?> #<?=$q?></h2>
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
                    echo  
                        '<div class="questionContainer">' .
                            $answerJson['value'] . 
                        '<div>';

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
                        echo '<br />';
                    }
                }

                if($question['type'] == 'RankingQuestion') {
                    asort($answerJson);

                    foreach($answerJson as $answer_id => $order) {
                        foreach($question['ranking_question_answers'] as $answer) {
                            if($answer['id'] == $answer_id) {
                                echo $answer['answer'].'<br />';
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
                    $src = trim(htmlspecialchars_decode($answerJson['answer']));

                    if (substr($src, 0, 4) == '<svg') {
                        // when a svg file is the answer we can download an image from the filesystem;
                        $src = $service->getBase64EncodedDrawingQuestionGivenAnswerPng($answer['uuid']);
                    }

                    ?>
                    <img width="100%" src="<?=$src?>" />
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
