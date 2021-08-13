<div id="buttons">
    <a href="#" class="btn white dropblock-owner dropblock-left mr2" id="filters">
        <span class="fa fa-filter mr5"></span>
        <?= __("Filteren")?>
    </a>

    <div class="dropblock" for="filters">
        <?=$this->Form->create('Question')?>
        <table id="testsFilter" class="mb5">
            <tr>
                <th><?= __("Uniek ID")?></th>
                <td>
                    <?=$this->Form->input('id', array('label' => false, 'type' => 'text')) ?>
                </td>
            </tr>
            <tr>
                <th><?= __("Termen")?></th>
                <td>
                    <?=$this->Form->input('search', array('label' => false, 'type' => 'select', 'multiple' => true)) ?>
                </td>
            </tr>
            <tr>
                <th><?= __("Type")?></th>
                <td>
                    <?=$this->Form->input('type', array('options' => $filterTypes, 'label' => false)) ?>
                </td>
            </tr>
            <tr>
                <th><?= __("Vak")?></th>
                <td>
                    <?=$this->Form->input('subject', array('options' => $subjects, 'label' => false)) ?>
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
                    <?=$this->Form->input('education_levels', array('options' => $education_levels, 'label' => false)) ?>
                </td>
            </tr>
            <tr>
                <th><?= __("Leerjaar")?></th>
                <td>
                    <?=$this->Form->input('education_level_years', array('options' => $education_level_years, 'label' => false)) ?>
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

        <a href="#" class="btn btn-close white small pull-right mr5"><?= __("Sluiten")?></a>
        <a href="#" class="btn btn-reset white small pull-right"><?= __("Reset")?></a>
        <br clear="all" />
    </div>
</div>

<h1><?= __("Vragenbank")?></h1>

<div class="block autoheight">
    <div class="block-head"><?= __("Vragen")?></div>
    <div class="block-content" id="questionsContainter">
        <table class="table table-striped" id="questionsTable">
            <thead>
            <tr>
                <th></th>
                <th width="40"></th>
                <th><?= __("Vraag")?></th>
                <th><?= __("Type")?></th>
                <th><?= __("Auteur(s)")?></th>
                <th><?= __("Punten")?></th>
                <th><?= __("Tags")?></th>
                <th></th>
            </tr>
            </thead>
            <tbody></tbody>
        </table>

        <script type="text/javascript">
            $('#questionsTable').tablefy({
                'source' : '/questions/load',
                'filters' : $('#QuestionIndexForm'),
                'container' : $('#questionsContainter')
            });
        </script>
    </div>
    <div class="block-footer"></div>
</div>

<script type="text/javascript">
    $('#QuestionSearch').select2({
        tags : true
    });
</script>
