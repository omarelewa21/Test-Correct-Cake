<?php
switch ($answer['question']['type']) {
    case "OpenQuestion":
        if ($answer['question']['subtype'] == 'short') {
            $view = 'rate_open';
        } else {
            $lang = !is_null($answer['question']['lang']) ? $answer['question']['lang'] : 'nl_NL';
            $this->set('lang', $lang);
            $this->set('participantIdentifier', str_replace('-', '', $participant_id) . '_' . str_replace('-', '', $question_id));
            $spellCheckAvailable = ($answer['question']['subtype'] == 'writing' && $answer['question']['spell_check_available']);
            $this->set('spellCheckAvailable', $spellCheckAvailable);
            $view = 'rate_open_long';
        }
        break;

    case "CompletionQuestion":
        if ($answer['question']['subtype'] == 'completion') {
            $view = 'rate_completion';
        } elseif ($answer['question']['subtype'] == 'multi') {
            $view = 'rate_multi_completion';
        }
        break;

    case "MatchingQuestion":
        if ($answer['question']['subtype'] == 'Matching') {
            $view = 'rate_matching';
        } elseif ($answer['question']['subtype'] == 'Classify') {
            $view = 'rate_classify';
        }
        break;

    case "MultipleChoiceQuestion":
        if ($answer['question']['subtype'] == 'MultiChoice' || $answer['question']['subtype'] == 'MultipleChoice') {
            $view = 'rate_multiple_choice';
        } elseif ($answer['question']['subtype'] == 'TrueFalse') {
            $view = 'rate_true_false';
        } elseif ($answer['question']['subtype'] == 'ARQ') {
            $view = 'rate_arq';
        }
        break;

    case "RankingQuestion":
        $view = 'rate_ranking';
        break;

    case "DrawingQuestion":
//        $this->transformDrawingAnswer($answer);
//                $drawingAnswer = json_decode($answer['json'])->answer;
//
//                if (strpos($drawingAnswer, 'http') === false) {
//                    $drawingAnswerUrl = $this->TestTakesService->getDrawingAnswerUrl($drawingAnswer);
//                    $this->set('drawing_url', $drawingAnswerUrl);
//                }
        $view = 'rate_drawing';
        break;

    case "MatrixQuestion":
        $view = 'rate_matrix';
        break;

    default:

        break;
}

if (empty($answer['json'])) {
    $view = 'rate_empty';
} else {
    $a = $answer['json'];
    $a = json_decode($a, true);
//    $a['value'] = $this->getCorrectUrlsInString($a['value']);
    $answer['json'] = json_encode($a);
}

$answer['answer'] = $answer;

?>

<span><?= $view ?></span>