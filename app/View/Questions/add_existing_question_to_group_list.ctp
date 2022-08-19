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
        <td style="text-align:center">
            <?php

            if($question['closeable'] == 1) {
                $title = __("Deze vraag afsluiten");
                if ($question['type'] == 'GroupQuestion') {
                    $title = __("Deze vraaggroep afsluiten");
                }
                printf ('<i title="%s" style="cursor:pointer" class="fa fa-lock"></i>', $title);
            } else {
                echo '&nbsp;';
            }
            ?>
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
                        echo __("Juist / Onjuist");
                    }else{
                        echo __("Meerkeuze");
                    }
                    break;

                case 'OpenQuestion':
                    switch($question['subtype']){

                        case 'short':
                            echo __("Open vraag - kort<br />");
                            break;
                        case 'long':
                        case 'medium':
                            echo __("Open vraag - lang<br />");
                            break;
                        case 'writing':
                            echo __("Schrijf opdracht<br />");
                            break;
                        default:
                            echo __("Open vraag<br />");
                            break;
                    }
                    break;

                case 'CompletionQuestion':
                    if($question['subtype'] == 'multi') {
                        echo __("Selectie");
                    }else{
                        echo __("Gatentekst");
                    }
                    break;

                case 'RankingQuestion':
                    echo __("Rangschik");
                    break;

                case 'MatchingQuestion':
                    if($question['subtype'] == 'Matching') {
                        echo __("Combineer");
                    }else{
                        echo __("Rubriceer");
                    }
                    break;

                case 'DrawingQuestion':
                    echo __("Teken");
                    break;
            }
            ?>
        </td>
        <td><?= $question['score'] ?> pt</td>
        <td>
            <?
            if(count($question['tags']) == 0) {
                echo __("Geen");
            }else{
                foreach($question['tags'] as $tag) {
                    echo $tag['name']. " - ";
                }
            }

            ?>
        </td>
        <td class="nopadding">
            <a href="#" class="btn white pull-right" onclick="Popup.load('/questions/preview_single/<?=getUUID($question, 'get');?>', 1200);">
                <span class="fa fa-search"></span>
            </a>
            <a href="#" class="btn white pull-right" onclick="Questions.addExistingQuestionToGroup('<?=getUUID($question, 'get');?>', <?=$question['is_subquestion']?>);">
                <span class="fa fa-plus"></span>
            </a>
        </td>
    </tr>
<?
}
?>
