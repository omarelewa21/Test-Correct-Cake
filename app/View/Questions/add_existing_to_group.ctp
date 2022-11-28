<div class="popup-head"><?= __("Toetsvraag importeren")?></div>
<div class="popup-content">

    <div id="buttons" style="float:right;">
        <a href="#" class="btn white highlight dropblock-owner dropblock-left mr2" id="existingQuestionsFilter">
            <span class="fa fa-filter mr5"></span>
            <?= __("Filteren")?>
        </a>

        <div class="dropblock" for="existingQuestionsFilter">
            <?=$this->Form->create('Question')?>
            <table id="questionsFilter" class="mb5">
                <tr>
                    <th><?= __("Id")?></th>
                    <td>
                        <?=$this->Form->input('id', array('label' => false, 'type' => 'text', 'class' => 'disable_protect')) ?>
                    </td>
                </tr>
                <tr>
                    <th><?= __("Termen")?></th>
                    <td>
                        <?=$this->Form->input('search', array('label' => false, 'type' => 'select', 'multiple' => true, 'class' => 'disable_protect')) ?>
                    </td>
                </tr>
                <tr>
                    <th><?= __("Type")?> </th>
                    <td>
                        <?=$this->Form->input('type', array('options' => $filterTypes, 'label' => false, 'class' => 'disable_protect')) ?>
                    </td>
                </tr>
                <tr>
                    <th><?= __("Vak")?></th>
                    <td>
                        <?=$this->Form->input('subject', array('options' => $subjects, 'label' => false, 'value' => $subject_id, 'class' => 'disable_protect')) ?>
                    </td>
                </tr>
                <tr>
                    <th><?= __("Examenvak")?></th>
                    <td>
                        <?=
                  $this->Form->input('base_subject_id',array('options' => $baseSubjects,'label' => false))
                        ?>
                    </td>
                </tr>
                <tr>
                    <th><?= __("Niveau")?></th>
                    <td>
                        <?=$this->Form->input('education_levels', array('options' => $education_levels, 'label' => false, 'value' => $education_level_id, 'class' => 'disable_protect')) ?>
                    </td>
                </tr>
                <tr>
                    <th><?= __("Leerjaar")?></th>
                    <td>
                        <?=$this->Form->input('education_level_years', array('options' => $education_level_years, 'label' => false, 'value' => $year_id, 'class' => 'disable_protect')) ?>
                    </td>
                </tr>

                <tr>
                    <th><?= __("Bron")?></th>
                    <td>
                        <?=
                  $this->Form->input('source',array('options' => $filterSource,'label' => false))
                        ?>
                    </td>
                </tr>
            </table>
            <?=$this->Form->end();?>

            <a href="#" class="btn btn-close white small pull-right"><?= __("Sluiten")?></a>
            <br clear="all" />
        </div>
    </div>

    <br clear="all" />

    <div id="questionsContainter" style="height:500px; overflow: auto;">
        <table class="table table-striped" id="questionsTable">
            <thead>
            <tr>
                <th></th>
                <th></th>
                <th><?= __("Vraag")?></th>
                <th><?= __("Leerjaar")?></th>
                <th><?= __("Niveau")?></th>
                <th><?= __("Type")?></th>
                <th><?= __("Punten")?></th>
                <th><?= __("Tags")?></th>
                <th></th>
            </tr>
            </thead>
            <tbody></tbody>
        </table>

        <script type="text/javascript">
            $('#questionsTable').tablefy({
                'source' : '/questions/add_existing_question_to_group_list',
                'filters' : $('#QuestionAddExistingToGroupForm'),
                'container' : $('#questionsContainter')
            });
        </script>
    </div>
</div>
<div class="popup-footer">
    <a href="#" class="btn grey mt5 mr5 pull-right" onclick="Popup.closeLast();" selid="cancel-btn">
    <?= __("Annuleer")?>
    </a>
</div>

<script type="text/javascript">
    $('#QuestionSearch').select2({
        tags : true
    });
</script>
