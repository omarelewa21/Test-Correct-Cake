<div id="buttons">
    <a href="#" class="btn white mr2" onclick="Navigation.back();">
        <span class="fa fa-backward mr5"></span>
        Terug
    </a>
    <? if ($test['has_duplicates']) { ?>

    <a href="#" class="btn grey mr2" >
        <span class="fa fa-calendar mr5"></span>
        Inplannen
    </a>
    <? } else { ?>
    <a href="#" class="btn white mr2" 
            <? if($carouselGroupQuestionNotify){ ?>
            onclick="Notify.notify('<? echo($carouselGroupQuestionNotifyMsg) ?>', 'error');"
            <? }else{ ?>
            onclick="Popup.load('/test_takes/add/<?=$test_id?>',1000);"
            <? } ?>
            >
        <span class="fa fa-calendar mr5"></span>
        Inplannen
    </a>
    <? } ?>
    <a href="#" class="btn blue mr2" onclick="Popup.load('/test_takes/start_direct/<?=getUUID($test, 'get');?>',1000);">
        <?php echo $this->element('schedule_now') ?>
        Direct afnemen
    </a>
    <a href="#" class="btn white mr2" onclick="Popup.load('/tests/preview_popup/<?=$test_id?>', 1200);">
        <span class="fa fa-search mr5"></span>
        Voorbeeld
    </a>
    <a href="#" onclick="Popup.load('/tests/pdf_showPDFAttachment/<?=$test_id?>', 1000)" class="btn white mr2">
        <span class="fa fa-print mr5"></span>
        PDF
    </a>
    <? if($test['author']['id'] == AuthComponent::user('id') && !AppHelper::isCitoTest($test)) { ?>
        <a href="#" class="btn white mr2" onclick="Test.delete('<?=$test_id?>', true);">
            <span class="fa fa-remove mr5"></span>
            Verwijderen
        </a>

        <a href="#" class="btn white" onclick="Popup.load('/tests/edit/<?=$test_id?>', 1000);">
            <span class="fa fa-edit mr5"></span>
            Gegevens wijzigen
        </a>
    <? } ?>
</div>

<h1><?=$test['name']?></h1>

<div class="block">
    <div class="block-head">Toets informatie</div>
    <div class="block-content">
        <table class="table table-striped">
            <tr>
                <th width="12%">Afkorting</th>
                <td width="21%"><?=$test['abbreviation']?></td>
                <th width="12%">Auteur</th>
                <td width="21%">
                    <?=$test['author']['name_first']?>
                    <?=$test['author']['name_suffix']?>
                    <?=$test['author']['name']?>
                </td>
                <th width="12%">Eigenaar</th>
                <td>
                    <?
                    if(!empty($test['author']['school']['name'])) {
                        echo $test['author']['school']['name'];
                    }else{
                        echo $test['author']['school_location']['name'];
                    }
                    ?>
                </td>
            </tr>
            <tr>
                <th>Vak</th>
                <td><?=$test['subject']['name']?></td>
                <th>Periode</th>
                <td><?=$test['period']['name']?></td>
                <th>Soort</th>
            <td><?=$test['test_kind']['name']?></td>
            </tr>
            <tr>
                <th>Niveau</th>
                <td><?=$test['education_level_year']?> <?=$test['education_level']['name']?></td>
                <th>Maximale score</th>
                <td colspan="3"><?=$totalScore?></td>
            </tr>
        </table>
    </div>
</div>


<div class="block">
    <div class="block-head">Toetsvragen</div>
    <div class="block-content">
        <table class="table table-striped" id="tableQuestions">
            <thead>
            <? if ($test['has_duplicates']) { ?>
            <tr>
                <? if($test['author']['id'] == AuthComponent::user('id')) { ?>
                <td class="danger" colspan="6">
                    <? } else { ?>
                <td class="danger" colspan="5">
                    <? } ?>
                    E&eacute;n of meerdere vragen staan dubbel in deze toets. Pas de toets aan om het inplannen mogelijk te maken.
                </td>
            </tr>
            <? } ?>

            <?php if($totalScore === 0){ ?>
            <tr>
                <? if($test['author']['id'] == AuthComponent::user('id')) { ?>
                <td class="danger" colspan="6">
                    <? } else { ?>
                <td class="danger" colspan="5">
                    <? } ?>
                    Let op: alle vragen hebben een score 0, na de toetsafname kan hierdoor geen score/cijfer berekend worden.
                </td>
            </tr>
            <? } ?>
            <?php if($carouselGroupQuestionNotify){  ?>
            <tr>
                <? if($test['author']['id'] == AuthComponent::user('id')) { ?>
                <td class="danger" colspan="6">
                    <? } else { ?>
                <td class="danger" colspan="5">
                    <? } ?>
                    <? echo($carouselGroupQuestionNotifyMsg) ?>
                </td>
            </tr>
            <? } ?>
            <tr>
                <th>#</th>
                <th width="40">&nbsp;</th>
                <th>Vraag</th>
                <th>Soort</th>

                <th width="80">Score</th>
                <th width="80">Besprk.</th>
                <? if($test['author']['id'] == AuthComponent::user('id')) { ?>
                    <th width="80"></th>
                <? } ?>
            </tr>
            </thead>
            <tbody>
                <?
                $i = 0;
                foreach($questions as $question) {
                    $i++;

                    if($question['question']['type'] == 'GroupQuestion') {
                        $type = 'group';

                        $subquestions = $question['question']['group_question_questions'];
                        usort($subquestions, function($a, $b) {
                            $a = $a['order'];
                            $b = $b['order'];
                            if ($a == $b) {
                                return 0;
                            }
                            return ($a < $b) ? -1 : 1;
                        });

                    }else{
                        $type = 'question';
                    }
                    ?>
                    <tr id="<?=$type."_".getUUID($question, 'get')?>">
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
                            <?
                            if($question['question']['type'] == 'GroupQuestion') {
                                ?>
                                <div class="cell_autowidth" style="font-weight:bold;">
                                    <?=$question['question']['name']?>
                                </div>
                                <?
                                $a = 0;
                                foreach($subquestions as $subquestion) {
                                    $a ++;
                                    ?>
                                    <div class="cell_autowidth">
                                        <?= $a . '. '. $subquestion['question']['question']; ?>
                                    </div>
                                    <?
                                }
                            }else{
                                ?>
                                <div class="cell_autowidth">
                                    <?php $q = $question['question']['question']; ?>
                                    <?php echo $q?>
                                </div>
                                <?
                            }
                            ?>
                        </td>
                        <td>
                            <?
                            if($question['question']['type'] == 'GroupQuestion') {
                                foreach($subquestions as $subquestion) {
                                    switch($subquestion['question']['type']) {
                                        case 'InfoscreenQuestion':
                                            echo 'Infoscherm';
                                            break;
                                        case 'MultipleChoiceQuestion':
                                            if($subquestion['question']['subtype'] == 'TrueFalse') {
                                                echo 'Juist / Onjuist<br />';
                                            }else{
                                                echo 'Meerkeuze<br />';
                                            }
                                            break;

                                        case 'OpenQuestion':
                                            switch($subquestion['question']['subtype']){

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
                                            if($subquestion['question']['subtype'] == 'multi') {
                                                echo 'Selectie<br />';
                                            }else{
                                                echo 'Gatentekst<br />';
                                            }
                                            break;

                                        case 'RankingQuestion':
                                            echo 'Rangschik<br />';
                                            break;

                                        case 'MatchingQuestion':
                                            if($subquestion['question']['subtype'] == 'Matching') {
                                                echo 'Combineer<br />';
                                            }else{
                                                echo 'Rubriceer<br />';
                                            }
                                            break;

                                        case 'MatrixQuestion':
                                            if($subquestion['question']['subtype'] == 'SingleChoice'){
                                                echo 'MatrixQuestion';
                                            } else {
                                                echo 'MatrixQuestion ONBEKEND';
                                            }
                                        break;

                                        case 'DrawingQuestion':
                                            echo 'Teken<br />';
                                            break;
                                    }


                                }
                            }else{
                                switch($question['question']['type']) {
                                    case 'InfoscreenQuestion':
                                        echo 'Infoscherm';
                                        break;
                                    case 'MultipleChoiceQuestion':
                                        if($question['question']['subtype'] == 'TrueFalse') {
                                            echo 'Juist / Onjuist';
                                        }elseif($question['question']['subtype'] == 'ARQ') {
                                            echo 'ARQ';
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

                                    case 'MatrixQuestion':
                                        if($question['question']['subtype'] == 'SingleChoice'){
                                            echo 'MatrixQuestion';
                                        } else {
                                            echo 'MatrixQuestion ONBEKEND';
                                        }
                                        break;

                                    case 'DrawingQuestion':
                                        echo 'Teken';
                                        break;
                                }
                            }
                            ?>
                        </td>

                        <td>
                            <?
                            if($question['question']['type'] == 'GroupQuestion') {
                                foreach($question['question']['group_question_questions'] as $subquestion) {
                                    echo $subquestion['question']['score'].'pt<br />';
                                }
                            }else{
                                echo $question['question']['score'].'pt';
                            }
                            ?>
                        </td>

                        <td>
                            <?
                            if($question['question']['type'] == 'GroupQuestion') {
                                foreach($question['question']['group_question_questions'] as $subquestion) {
                                    if($subquestion['discuss'] == 1) {
                                        echo '<span class="fa fa-check"></span><br />';
                                    }else{
                                        echo '<span class="fa fa-remove"></span><br />';
                                    }
                                }
                            }else{
                                if($question['discuss'] == 1) {
                                    echo '<span class="fa fa-check"></span><br />';
                                }else{
                                    echo '<span class="fa fa-remove"></span><br />';
                                }
                            }
                            ?>
                        </td>
                        <? if($test['author']['id'] == AuthComponent::user('id') && (!AppHelper::isCitoTest($test))) { ?>
                            <td class="nopadding">

                                <a href="#" class="btn white pull-right dropblock-owner dropblock-left" id="question_<?=getUUID($question, 'get');?>" onclick="return false;">
                                    <span class="fa fa-list-ul"></span>
                                </a>
                                <? if($question['question']['type'] == 'GroupQuestion') { ?>
                                    <a href="#" class="btn white pull-right" onclick="Navigation.load('/questions/view_group/<?=$test_id?>/<?=getUUID($question, 'get');?>');">
                                        <span class="fa fa-folder-open-o"></span>
                                    </a>

                                    <div class="dropblock blur-close" for="question_<?=getUUID($question, 'get');?>">
                                        <a href="#" class="btn highlight white" onclick="Navigation.load('/questions/view_group/<?=$test_id?>/<?=getUUID($question, 'get');?>');">
                                            <span class="fa fa-edit mr5"></span>
                                            Wijzigen
                                        </a>
                                        <a href="#" class="btn highlight white" onclick="Questions.delete('test', '<?=$test_id?>',<?=getUUID($question, 'getQuoted');?>);">
                                            <span class="fa fa-trash mr5"></span>
                                            Verwijderen
                                        </a>
                                    </div>
                                <? }else{ ?>
                                    <a href="#" class="btn white pull-right" onclick="Popup.load('/questions/edit/test/<?=$test_id?>/<?=$question['question']['type']?>/<?=getUUID($question, 'get');?>', 800);">
                                        <span class="fa fa-folder-open-o"></span>
                                    </a>

                                    <div class="dropblock blur-close" for="question_<?=getUUID($question, 'get');?>">
                                        <a href="#" class="btn highlight white" onclick="Popup.load('/questions/edit/test/<?=$test_id?>/<?=$question['question']['type']?>/<?=getUUID($question, 'get');?>', 800);">
                                            <span class="fa fa-edit mr5"></span>
                                            Wijzigen
                                        </a>
                                        <a href="#" class="btn highlight white" onclick="Questions.delete('test', '<?=$test_id?>',<?=getUUID($question, 'getQuoted');?>);">
                                            <span class="fa fa-trash mr5"></span>
                                            Verwijderen
                                        </a>
                                    </div>
                                <? } ?>
                            </td>
                        <? } ?>
                    </tr>
                    <?
                }
                ?>
            </tbody>
        </table>
    </div>
    <? if($test['author']['id'] == AuthComponent::user('id') && !AppHelper::isCitoTest($test)) { ?>
        <div class="block-footer">
            <a href="#" class="btn highlight mt5 mr5 pull-right" onclick="Popup.load('/questions/add_existing/test/<?=$test_id?>', 1200); return false;">
                <i class="fa fa-clock-o mr5"></i> Bestaande vraag toevoegen
            </a>
            <a href="#" class="btn highlight mt5 mr5 pull-right" onclick="Popup.load('/questions/add_custom/test/<?=$test_id?>', 800); return false;">
                <i class="fa fa-plus mr5"></i> Nieuwe vraag toevoegen
            </a>
            <a href="#" class="btn highlight mt5 mr5 pull-right" onclick="Test.groupQuestionChooseTypePopup('<?=$test_id?>')">
                <i class="fa fa-plus mr5"></i> Nieuwe vraaggroep
            </a>
            <a href="#" class="btn highlight mt5 mr5 pull-right" style="display: none;">
                <i class="fa fa-check mr5"></i> Bestaande vraag toevoegen
            </a>
        </div>
    <? } ?>
</div>
<? if($test['author']['id'] == AuthComponent::user('id')) { ?>
    <script type="text/javascript">
        <?php if(!AppHelper::isCitoTest($test)){?>
        $("#tableQuestions tbody").sortable({
            delay: 150,
            stop: function( event, ui ) {
                Questions.updateIndex(ui.item[0].id, '<?=$test_id?>');
            }
        }).disableSelection();
        <?php } ?>

        var winW = $(window).width();
        $('.cell_autowidth').css({
            'display': 'block',
            'width': (winW - 600) + 'px',
            'text-overflow': 'ellipsis',
            'white-space': 'nowrap',
            'overflow': 'hidden'
        });

    </script>
<? } ?>
