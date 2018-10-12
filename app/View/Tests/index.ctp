<div id="buttons">
    <a href="#" class="btn white mr2" onclick="Popup.load('/test_takes/add',1000);">
        <span class="fa fa-calendar-o mr5"></span>
        Toetsen inplannen
    </a>

    <a href="#" class="btn white dropblock-owner dropblock-left mr2" id="filters">
        <span class="fa fa-filter mr5"></span>
        Filteren
    </a>
    <a href="#" class="btn white" onclick="Popup.load('/tests/add', 1000);">
        <span class="fa fa-plus mr5"></span>
        Nieuwe toets
    </a>

    <div class="dropblock" for="filters">
        <?=$this->Form->create('Test')?>
            <table id="testsFilter" class="mb5">
                <tr>
                    <th>Toets</th>
                    <td>
                        <?=$this->Form->input('name', array('label' => false)) ?>
                    </td>
                </tr>
                <tr>
                    <th>Type</th>
                    <td>
                        <?=$this->Form->input('kind', array('options' => $kinds, 'label' => false)) ?>
                    </td>
                </tr>
                <tr>
                    <th>Vak</th>
                    <td>
                        <?=$this->Form->input('subject', array('options' => $subjects, 'label' => false)) ?>
                    </td>
                </tr>
                <tr>
                    <th>Periode</th>
                    <td>
                        <?=$this->Form->input('period', array('options' => $periods, 'label' => false)) ?>
                    </td>
                </tr>
                <tr>
                    <th>Niveau</th>
                    <td>
                        <?=$this->Form->input('education_levels', array('options' => $education_levels, 'label' => false)) ?>
                    </td>
                </tr>
                <tr>
                    <th>Niveau jaar</th>
                    <td>
                        <?=$this->Form->input('education_level_years', array('options' => $education_level_years, 'label' => false)) ?>
                    </td>
                </tr>

                <?php if(true): ?>
                  <th>Bron</th>
                  <td>
                    <?=$this->Form->input('is_open_sourced_content',array(
                      'options'=>[ 'Alles', 'Eigen content', 'Gratis content' ],'label'=>false
                    )) ?>
                  </td>
                <?php endif; ?>

                <tr>
                    <th>Aangemaakt van</th>
                    <td>
                        <?=$this->Form->input('created_at_start', array('label' => false)) ?>
                    </td>
                </tr>

                <tr>
                    <th>Aangemaakt tot</th>
                    <td>
                        <?=$this->Form->input('created_at_end', array('label' => false)) ?>
                    </td>
                </tr>
            </table>
        <?=$this->Form->end();?>

        <a href="#" class="btn btn-close white small pull-right">Sluiten</a>
        <a href="#" class="btn btn-reset white small pull-right mr5">Reset</a>
        <br clear="all" />
    </div>
</div>

<h1>Toetsen</h1>

<div class="block autoheight">
    <div class="block-head">Toetsen</div>
    <div class="block-content" id="testsContainter">
        <table class="table table-striped" id="testsTable">
            <thead>
            <tr>
              <th></th>
                <th sortkey="abbriviation" width="50">Afk.</th>
                <th sortkey="name">Titel</th>
                <th width="70" style="text-align: center" sortkey="subject">Vragen</th>
                <th width="170" sortkey="subject">Vak</th>
                <th width="170" sortkey="author">Auteur</th>
                <th width="170" sortkey="kind">Type</th>
                <th width="150" sortkey="level">Niveau</th>
                <th width="100">&nbsp;</th>
            </tr>
            </thead>
            <tbody></tbody>
        </table>

        <script type="text/javascript">

            $('#TestCreatedAtStart, #TestCreatedAtEnd').datepicker({
                dateFormat: 'dd-mm-yy'
            });

            $('#testsTable').tablefy({
                'source' : '/tests/load',
                'filters' : $('#TestIndexForm'),
                'container' : $('#testsContainter')
            });
        </script>
    </div>
    <div class="block-footer"></div>
</div>
