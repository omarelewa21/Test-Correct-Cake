<div class="popup-head"><?= __("Combineervraag")?></div>
<div class="popup-content">
    <?=$this->Form->create('Question', array('id' => $is_clone_request ? 'QuestionAddForm' : 'QuestionEditForm'))?>

        <table class="table mb15">
            <tr>
                <th width="10%">
                <?= __("Punten")?>
                </th>
                <td width="110">
                    <?=$this->Form->input('score', array('style' => 'width:50px;', 'value' => $question['question']['score'], 'label' => false, 'verify' => 'notempty'))?>
                </td>
                <td>
                    <?=$this->Form->input('closeable', array('type' => 'checkbox', 'value' => 1, 'label' => false, 'div' => false, 'checked' => $question['question']['closeable'] == 1 ? 'checked' : ''))?> <?= __("Deze vraag afsluiten")?> <span class="fa fa-info-circle" onclick="Popup.load('/questions/closeable_info', 500);" style="cursor:pointer"></span><br />
                    <?=$this->Form->input('discuss', array('type' => 'checkbox', 'value' => 1, 'label' => false, 'div' => false, 'checked' => $question['discuss'] == 1 ? 'checked' : ''))?> <?= __("Bespreken in de klas")?><br />
                    <? if($owner != 'group') { ?><?=$this->Form->input('maintain_position', array('type' => 'checkbox', 'value' => 1, 'label' => false, 'div' => false, 'checked' => $question['maintain_position'] == 1 ? 'checked' : ''))?> <?= __("Deze vraag vastzetten")?> <br /><? }?>
                    <?=$this->Form->input('decimal_score', array('type' => 'checkbox', 'value' => 1, 'label' => false, 'div' => false, 'checked' => $question['question']['decimal_score'] == 1 ? 'checked' : ''))?> <?= __("Halve punten mogelijk")?><br />
                    <?php if(!$is_open_source_content_creator): ?>
                        <?=$this->Form->input('add_to_database', array('type' => 'checkbox', 'value' => 1, 'label' => false, 'div' => false, 'checked' => $question['question']['add_to_database'] == 1 ? 'checked' : ''))?> <?= __("Openbaar maken")?> <span class="fa fa-info-circle" onclick="Popup.load('/questions/public_info', 500);" style="cursor:pointer"></span>
                    <?php endif; ?>
                </td>
                <td width="230">
                    <?=$this->Form->input('note_type', array('type' => 'select', 'value' => $question['question']['note_type'], 'label' => false, 'div' => false, 'options' => [
                        'NONE' => __("Geen kladblok"),
                        'TEXT' => __("Tekstvlak"),
                        'DRAWING' => __("Tekenvlak")
                    ]))?>
                </td>
            </tr>
        </table>

        <div class="tabs">
            <a href="#" class="btn grey highlight" page="question" tabs="edit_question">
            <?= __("Vraag")?>
            </a>

            <a href="#" class="btn grey" page="options" tabs="edit_question">
            <?= __("Antwoorden")?>
            </a>

            <? if($owner != 'group') { ?>
                <a href="#" class="btn grey" page="sources" tabs="edit_question">
                <?= __("Bronnen")?>
                </a>
            <? } ?>

            <a href="#" class="btn grey" page="attainments" tabs="edit_question">
            <?= __("Eindtermen")?>
            </a>

            <a href="#" class="btn grey" page="tags" tabs="edit_question">
            <?= __("Tags")?>
            </a>

            <a href="#" class="btn grey" page="rtti" tabs="edit_question">
            <?= __("Taxonomie")?>
            </a>

            <a href="#" class="btn grey" page="owners" tabs="edit_question">
            <?= __("Info")?>
            </a>
            <br clear="all" />
        </div>


        <div page="question" class="page active" tabs="edit_question">
            <?=$this->Form->input('question', array('style' => 'width:737px; height: 100px;', 'type' => 'textarea', 'div' => false, 'label' => false, 'value' => $question['question']['question'])); ?>
        </div>
        <div page="options" class="page" tabs="edit_question">
            <table class="table" id="tableMatchingOptions">
                <thead>
                    <tr>
                        <th>&nbsp;</th>
                        <th><?= __("Links")?></th>
                        <th><?= __("Rechts")?></th>
                        <th>&nbsp;</th>
                    </tr>
                </thead>
                <tbody>
                    <?
                    function findRightAnswer($ar, $id){
                        $rightAnswer = false;
                        foreach($ar as $answer){
                            if($answer['correct_answer_id'] == $id){
                                $rightAnswer = $answer;
                                break;
                            }
                        }
                        return $rightAnswer;
                    }
                    $counter = 0;
                    $answers = $question['question']['matching_question_answers'];
                    $leftAr = $rightAr = [];
                    foreach($answers as $answer){
                        if($answer['type'] == 'LEFT' && $answer['answer']){
                            $leftAr[] = $answer;
                        }
                        elseif($answer['type'] == 'RIGHT' && $answer['answer']){
                            $rightAr[] = $answer;
                        }
                        else{
                            // should not happen
                        }
                    }

                    foreach($leftAr as $i => $answer){
                        $left = $answer;
                        $right = findRightAnswer($rightAr,$answer['id']);
                        $display = true;
                        ?>
                        <tr style="<?=$display ? '' : 'display:none;' ?>">
                            <td>
                                <span class="fa fa-arrows" style="cursor:move"></span>
                            </td>
                            <td>
                                <?=$this->Form->input('', array('type' => 'hidden','label' => false, 'name' => 'data[Question][answers]['.$i.'][order]', 'value' => $i, 'class' => 'order'))?>
                                <?=$this->Form->input('', array('style' => 'width: 300px;', 'label' => false, 'name' => 'data[Question][answers]['.$i.'][left]', 'value' => isset($left['answer']) ? $left['answer'] : ''))?>
                            </td>
                            <td>
                                <?=$this->Form->input('', array('style' => 'width: 300px;', 'label' => false, 'name' => 'data[Question][answers]['.$i.'][right]', 'value' => isset($right['answer']) ? $right['answer'] : ''))?>
                            </td>
                            <td>
                                <? if($editable) { ?>
                                    <a href="#" class="btn red small" onclick="Questions.removeMatchingOption(this);">
                                        <span class="fa fa-remove"></span>
                                    </a>
                                <? } ?>
                            </td>
                        </tr>
                        <?
                    }
                    $i++;
                    for($i;$i<50;$i++){
                        $display = false;
                        $left = [];
                        $right = [];
                        ?>
                        <tr style="<?=$display ? '' : 'display:none;' ?>">
                            <td>
                                <span class="fa fa-arrows"></span>
                            </td>
                            <td>
                                <?=$this->Form->input('', array('type' => 'hidden','label' => false, 'name' => 'data[Question][answers]['.$i.'][order]', 'value' => $i, 'class' => 'order'))?>
                                <?=$this->Form->input('', array('style' => 'width: 300px;', 'label' => false, 'name' => 'data[Question][answers]['.$i.'][left]', 'value' => isset($left['answer']) ? $left['answer'] : ''))?>
                            </td>
                            <td>
                                <?=$this->Form->input('', array('style' => 'width: 300px;', 'label' => false, 'name' => 'data[Question][answers]['.$i.'][right]', 'value' => isset($right['answer']) ? $right['answer'] : ''))?>
                            </td>
                            <td>
                                <? if($editable) { ?>
                                <a href="#" class="btn red small" onclick="Questions.removeMatchingOption(this);">
                                    <span class="fa fa-remove"></span>
                                </a>
                                <? } ?>
                            </td>
                        </tr>
                        <?
                    }

                    ?>
                </tbody>
            </table>

            <? if($editable) { ?>
                <center>
                    <a href="#" class="btn highlight small inline-block" onclick="Questions.addMatchingOption();">
                        <span class="fa fa-plus"></span>
                        <?= __("Optie toevoegen")?>
                    </a>
                </center>
            <? } ?>
        </div>

        <div page="attainments" class="page" tabs="edit_question">
            <?=$this->element('attainments', ['attainments' => $attainments, 'selectedAttainments' => $selectedAttainments]) ?>
        </div>


    <?=$this->element('question_tab_rtti',['question' => $question]); ?>

        <div page="tags" class="page" tabs="edit_question">

            <?=$this->Form->input('tags', array('label' => false, 'type' => 'select', 'multiple' => true, 'style' => 'width:750px;', 'options' => $question['question']['tags'], 'value' => $question['question']['tags']))?>
        </div>

    <div page="owners" class="page" tabs="edit_question">
        <?=$this->element('question_info', ['question' => $question])?>
    </div>

        <?=$this->Form->end();?>
        <? if($owner != 'group') { ?>
            <div page="sources" class="page" tabs="edit_question"></div>
        <? } ?>
</div>
<div class="popup-footer">
    <a href="#" class="btn grey mt5 mr5 pull-right" onclick="Popup.closeLast();">
    <?= __("Annuleer")?>
    </a>

    <? if($is_clone_request){ ?>
        <a href="#" class="btn highlight mt5 mr5 pull-right" onclick="Questions.add('MatchingQuestion', '<?=$owner?>', '<?=$owner_id?>');">
            <?= __("Vraag opslaan")?>
        </a>
    <? }else{ ?>
        <? if($editable) { ?>
            <a href="#" class="btn highlight mt5 mr5 pull-right" onclick="Questions.edit('<?=$owner?>', '<?=$owner_id?>', 'MatchingQuestion', '<?=getUUID($question, 'get');?>');">
                <?= __("Vraag opslaan")?>
            </a>
        <? } ?>
    <? } ?>
</div>

<script type="text/javascript">

    <? if(!$editable) { ?>
        $('.popup-content input, .popup-content select, .popup-content textarea').not('.disable_protect').attr({'disabled' : true});
    <? } ?>

    <? if($is_clone_request){ ?>
        Questions.loadAddAttachments(true);
    <? }else{ ?>
        <? if($owner != 'group') { ?>
            Questions.loadEditAttachments('<?=$owner?>', '<?=$owner_id?>', '<?=getUUID($question, 'get');?>');
        <? } ?>
    <? } ?>

    $('#QuestionAttainments').select2();


    $('#QuestionTags').select2({
        tags : true,
        ajax: {
            url: "/questions/tags",
            dataType: 'json',
            delay: 250,
            data: function (params) {
                return {
                    q: params.term,
                };
            },
            processResults: function (data, page) {
                return {
                    results: data.items
                };
            },
            cache: true
        }
    });

    $('#tableMatchingOptions tbody').sortable({
        delay: 150,
        handle: "span",
        stop: function( event, ui ) {
            Questions.updateMatchingOrder();
        }
    });
    $('#QuestionQuestion').ckeditor({});
</script>
