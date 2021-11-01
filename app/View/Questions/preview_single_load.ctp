
<? if(count($question['attachments']) > 0) { ?>
    <div style="width:250px; padding:20px; background: #294409; margin-left: 30px; float: right;">
        <div style="color: white; text-align: center; font-size: 22px; margin-bottom: 10px;">
        <?= __("Bronnen")?>
        </div>
        <?
        $i = 0;
        foreach($question['attachments'] as $attachemnt) {
            $i++;
            ?>
            <a href="#" class="btn white" style="margin-bottom: 2px;" onclick="Answer.loadAttachment('<?=getUUID($attachemnt, 'get');?>');">
            <?= __("Bijlage")?> #<?=$i?>
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
                echo __("Juist / Onjuist<br />");
            }else{
                echo __("Meerkeuze<br />");
            }
            break;

        case 'OpenQuestion':
            echo __("Open vraag");
            break;

        case 'CompletionQuestion':
            if($question['subtype'] == 'multi') {
                echo __("Selectie<br />");
            }else{
                echo __("Gatentekst<br />");
            }
            break;

        case 'RankingQuestion':
            echo __("Rangschik<br />");
            break;

        case 'MatchingQuestion':
            if($question['subtype'] == 'Matching') {
                echo __("Combineer<br />");
            }else{
                echo __("Rubriceer<br />");
            }
            break;

        case 'DrawingQuestion':
            echo __("Teken<br />");
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
