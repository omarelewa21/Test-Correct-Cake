<?
foreach($questions as $question) {
    ?>
    <tr>
        <td>
          <? if($question['is_open_source_content'] == 1): ?>
            <i class="fa fa-free" style="background-image:url('/img/ico/free.png'); display:block; width:32px; height:32px">
            </i>
          <? endif;?>
        </td>
        <td>
            <div style="width:475px; overflow: hidden;white-space: nowrap; ">
                <? if($question['type'] == 'GroupQuestion') {
                    echo strip_tags($question['name']) ;
                }else{
                    echo $question['question'];
                }?>
            </div>
        </td>
        <td><?=$question['education_level_year']?></td>
        <td><?=$education_levels[$question['education_level_id']]?></td>
        <td>
            <?
            switch($question['type']) {
                case 'MultipleChoiceQuestion':
                    if($question['subtype'] == 'TrueFalse') {
                        echo 'Juist / Onjuist';
                    }else{
                        echo 'Meerkeuze';
                    }
                    break;

                case 'OpenQuestion':
                    switch($question['subtype']){
                        
                        case 'short':
                            echo 'Open vraag - kort<br />';
                            break;
                        case 'long':
                        case 'medium':
                            echo 'Open vraag - lang<br />';
                            break;
                        default:
                            echo 'Open vraag<br />';
                            break;
                    }
                    break;

                case 'CompletionQuestion':
                    if($question['subtype'] == 'multi') {
                        echo 'Selectie';
                    }else{
                        echo 'Gatentekst';
                    }
                    break;

                case 'RankingQuestion':
                    echo 'Rangschik';
                    break;

                case 'MatchingQuestion':
                    if($question['subtype'] == 'Matching') {
                        echo 'Combineer';
                    }else{
                        echo 'Rubriceer';
                    }
                    break;

                case 'DrawingQuestion':
                    echo 'Teken';
                    break;
            }
            ?>
        </td>
        <td><?= $question['score'] ?> pt</td>
        <td>
            <?
            if(count($question['tags']) == 0) {
                echo 'Geen';
            }else{
                foreach($question['tags'] as $tag) {
                    echo $tag['name']. " - ";
                }
            }

            ?>
        </td>
        <td class="nopadding">
            <a href="#" class="btn white pull-right" onclick="Popup.load('/questions/preview_single/<?=getUUID($question, 'get');?>', 1000);">
                <span class="fa fa-search"></span>
            </a>
            <a href="#" class="btn white pull-right" onclick="Questions.addExistingQuestion('<?=getUUID($question, 'get');?>', <?=$question['is_subquestion']?>);">
                <span class="fa fa-plus"></span>
            </a>
        </td>
    </tr>
<?
}
?>
