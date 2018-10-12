
<? if(count($question['attachments']) > 0) { ?>
    <div style="width:250px; padding:20px; background: #294409; margin-left: 30px; float: right;">
        <div style="color: white; text-align: center; font-size: 22px; margin-bottom: 10px;">
            Bronnen
        </div>
        <?
        $i = 0;
        foreach($question['attachments'] as $attachemnt) {
            $i++;
            ?>
            <a href="#" class="btn white" style="margin-bottom: 2px;" onclick="Answer.loadAttachment(<?=$attachemnt['id']?>);">
                Bijlage #<?=$i?>
            </a>
        <?
        }
        ?>
    </div>
<? } ?>

<h1>
    <?
    switch($question['type']) {
        case 'MultipleChoiceQuestion':
            if($question['subtype'] == 'TrueFalse') {
                echo 'Juist / Onjuist<br />';
            }else{
                echo 'Meerkeuze<br />';
            }
            break;

        case 'OpenQuestion':
            if($question['subtype'] == 'long') {
                echo 'Wiskunde vraag';
            }else {
                echo 'Open vraag';
            }
            break;

        case 'CompletionQuestion':
            if($question['subtype'] == 'multi') {
                echo 'Selectie<br />';
            }else{
                echo 'Gatentekst<br />';
            }
            break;

        case 'RankingQuestion':
            echo 'Rangschik<br />';
            break;

        case 'MatchingQuestion':
            if($question['subtype'] == 'Matching') {
                echo 'Combineer<br />';
            }else{
                echo 'Rubriceer<br />';
            }
            break;

        case 'DrawingQuestion':
            echo 'Teken<br />';
            break;
    }
    ?>
</h1>
<div style="font-size: 20px;">
    <?
    if(isset($question['question_group']['text']) && !empty($question['question_group']['text'])) {
        echo '<p>'. $question['question_group']['text'].'</p>';
    }
    ?>
    <?=$question['question']?>
</div>
