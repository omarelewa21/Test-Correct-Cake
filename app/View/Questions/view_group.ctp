<div id="buttons">
    <a href="#" class="btn white mr2" onclick="Popup.load('/questions/edit_group/<?=$test_id?>/<?=$group_id?>', 600); return false;">
        <span class="fa fa-edit mr5"></span>
        Wijzigen
    </a>
    <a href="#" class="btn white mr2" onclick="Navigation.back();">
        <span class="fa fa-backward mr5"></span>
        Terug
    </a>
</div>

<h1><?=$group['question']['name']?></h1>

<?
if(!empty($group['question']['question'])) {
    ?>
    <div class="block">
        <div class="block-head">Omschrijving</div>
        <div class="block-content">
            <?=$group['question']['question']?>
        </div>
    </div>
    <?
}

?>

<div class="block">
    <div class="block-head">Bijlages</div>
    <div class="block-content">
        <div id="groupAttachments"></div>
    </div>
</div>

<div class="block">
    <div class="block-head">Groep vragen
        <?
                if($group['question']['groupquestion_type']=='carousel') {
        ?>
                (Aantal te beantwoorden vragen: <?=$group['question']['number_of_subquestions']?>)
        <?
                }
        ?>
    </div>
    <div class="block-content">
        <table class="table table-striped" id="tableQuestions">
            <thead>
            <?php if($carouselGroupQuestionNotify){ ?>
            <tr>
                <td class="danger" colspan="5">
                    <?=$carouselGroupQuestionNotifyMsg?>
                </td>
            </tr>
            <? } ?>
            <tr>
                <th>#</th>
                <th>&nbsp;</th>
                <th>Vraag</th>
                <th>Soort</th>
                <th>Score</th>
                <th width="80"></th>
            </tr>
            </thead>
            <tbody>
                <?
                $i = 0;
                foreach($questions as $question) {
                    $i++;
                    ?>
                    <tr id="<?=getUUID($question, 'get');?>">
                        <td><?=$i?></td>
                        <td style="text-align:center">
                            <?php

                            if($question['question']['closeable'] == 1) {
                                $title = 'Deze vraag afsluiten';
                                if ($question['question']['type'] == 'GroupQuestion') {
                                    $title = 'Deze vraaggroep afsluiten';
                                }
                                printf ('<i title="%s" style="cursor:pointer" class="fa fa-lock"></i>', $title);
                            } else {
                                echo '&nbsp;';
                            }
                            ?>
                        </td>
                        <td>
                            <div class="cell_autowidth">
                                <?= $question['question']['question'] ?>
                            </div>
                        </td>
                        <td>
                            <?
                            switch($question['question']['type']) {
                                case 'MultipleChoiceQuestion':
                                    if($question['question']['subtype'] == 'TrueFalse') {
                                        echo 'Juist / Onjuist';
                                    }else{
                                        echo 'Meerkeuze';
                                    }
                                    break;

                                case 'OpenQuestion':
                                    switch($question['question']['subtype']){

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
                                    if($question['question']['subtype'] == 'multi') {
                                        echo 'Selectie';
                                    }else{
                                        echo 'Gatentekst';
                                    }
                                    break;

                                case 'RankingQuestion':
                                    echo 'Rangschik';
                                    break;

                                case 'MatchingQuestion':
                                    if($question['question']['subtype'] == 'Matching') {
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
                        <td>
                            <?= $question['question']['score'].'pt'; ?>
                        </td>
                        <td class="nopadding">
                            <a href="#" class="btn white pull-right dropblock-owner dropblock-left" id="question_<?=getUUID($question, 'get');?>">
                                <span class="fa fa-list-ul"></span>
                            </a>
                            <a href="#" class="btn white pull-right" onclick="Popup.load('/questions/edit/group/<?=$group_id?>/<?=$question['question']['type']?>/<?=getUUID($question, 'get');?>', 800);">
                                <span class="fa fa-folder-open-o"></span>
                            </a>

                            <div class="dropblock blur-close" for="question_<?=getUUID($question, 'get');?>">
                                <a href="#" class="btn highlight white" onclick="Popup.load('/questions/edit/group/<?=$group_id?>/<?=$question['question']['type']?>/<?=getUUID($question, 'get');?>', 800);">
                                    <span class="fa fa-edit mr5"></span>
                                    Wijzigen
                                </a>
                                <a href="#" class="btn highlight white" onclick="Questions.delete('group', '<?=$group_id?>',<?=getUUID($question, 'getQuoted');?>);">
                                    <span class="fa fa-remove mr5"></span>
                                    Verwijderen
                                </a>
                            </div>
                        </td>
                    </tr>
                    <?
                }
                ?>
            </tbody>
        </table>
    </div>
    <div class="block-footer">
        <a href="#" class="btn highlight mt5 mr5 pull-right" onclick="Popup.load('/questions/add_custom/group/<?=$group_id?>', 800); return false;">
            <i class="fa fa-plus mr5"></i> Nieuwe vraag toevoegen
        </a>
        <a href="#" class="btn highlight mt5 mr5 pull-right" onclick="Popup.load('/questions/add_existing_to_group/group/<?=$group_id?>', 1200); return false;">
                <i class="fa fa-clock-o mr5"></i> Bestaande vraag toevoegen
            </a>
    </div>
</div>

<script type="text/javascript">

    Questions.loadEditAttachments('group', '<?=$test_id?>', '<?=$group_id?>');

   $("#tableQuestions tbody").sortable({
        delay: 150,
        stop: function( event, ui ) {
            Questions.updateGroupIndex(ui.item[0].id, '<?=$group_id?>');
        }
    }).disableSelection();

    var winW = $(window).width();
    $('.cell_autowidth').css({
        'display': 'block',
        'width': (winW - 600) + 'px',
        'text-overflow': 'ellipsis',
        'white-space': 'nowrap',
        'overflow': 'hidden'
    });
</script>
