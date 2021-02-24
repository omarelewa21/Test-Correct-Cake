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

                $title = 'Deze vraag afsluiten';
                if ($question['type'] == 'GroupQuestion') {
                    $title = 'Deze vraaggroep afsluiten';
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
                        echo 'Juist / Onjuist';
                    }elseif($question['subtype'] == 'ARQ') {
                        echo 'ARQ';
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

                case 'GroupQuestion':
                    if($question['groupquestion_type'] == 'carousel') {
                        echo 'Groepvraag - carrousel ('.$question['number_of_subquestions'].')';
                    }else{
                        echo 'Groepvraag';
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
        </td>
    </tr>
<?
}
?>
