?>
        </div>

<?
$answer = $rating['answer']['json'];
$answer = json_decode($answer, true);

$question = $rating['answer']['question'];

$answerSubQuestionReference = $answer;

    $citoClass = '';
    if(AppHelper::isCitoQuestion($question)){
        $citoClass = 'cito';
    }
    echo sprintf('<div class="answer_container %s">',$citoClass);

            echo $this->element('question_matrix',[
            'question' => $question,
            'answerSubQuestionReference' => $answerSubQuestionReference,
            'rating' => true
            ]);

            foreach($question['multiple_choice_question_answers'] as $option) {
            if($answer[$option['id']] == 0) {
            ?>
            <div><span class="fa fa-square-o"></span>
                <?
    }else{
        ?>
                <div><span class="fa fa-check-square-o"></span>
                    <?
    }

    echo $option['answer'] . '</div><br />';
                    }
                    ?>
                </div>