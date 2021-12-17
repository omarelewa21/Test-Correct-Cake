<?
foreach($questions as $question) {
    ?>
    <tr>
      <?php if($question['is_open_source_content']): ?>
        <td>
            <i class="fa fa-free" style="background-image:url('/img/ico/free.png'); display:block; width:32px; height:32px">
            </i>
        </td>
      <?php else: ?>
        <td></td>
      <?php endif; ?>
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
            <div style="width:575px; overflow: hidden;white-space: nowrap; ">
                <? if($question['type'] == 'GroupQuestion') {
                    echo strip_tags($question['name']) ;
                }else{
                    echo strip_tags($question['question']);
                }?>
            </div>
        </td>
        <td>
            <?
            switch($question['type']) {
                case 'MultipleChoiceQuestion':
                    if($question['subtype'] == 'TrueFalse') {
                        echo __("Juist / Onjuist");
                    }elseif($question['subtype'] == 'ARQ') {
                        echo __("ARQ");
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

                case 'GroupQuestion':
                    if($question['groupquestion_type'] == 'carousel') {
                        echo __("Groepvraag - carrousel ") . '('.$question['number_of_subquestions'].')';
                    }else{
                        echo __("Groepvraag");
                    }
                    break;
            }
            ?>
        </td>
        <td>
            <?
            foreach($question['authors'] as $author) {
                echo $author['name_first'] . ' ' . $author['name_suffix'] . ' ' . $author['name'] . ' <br />';
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
        </td>
    </tr>
<?
}
?>
