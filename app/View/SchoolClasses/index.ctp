<div id="buttons">
    <a href="#" class="btn white" onclick="Popup.load('/school_classes/add', 400);">
        <span class="fa fa-plus mr5"></span>
        <?= __("Nieuwe Klas")?>
    </a>
    
    <a href="#" class="btn white" onclick="Navigation.load('/users/import/students');">
        <span class="fa fa-cloud-upload mr5"></span>
        Studenten importeren
    </a>
        
    <a href="#" class="btn white dropblock-owner dropblock-left mr2 <?php if($currentYearId !== ''){?>highlight <?php } ?>" id="filters">
        <span class="fa fa-filter mr5"></span>
        <?= __("Filteren")?>
    </a>

    <div class="dropblock" for="filters">
        <?= $this->Form->create('SchoolClass') ?>
        <table id="testsFilter" class="mb5">
            <tr>
                <th><?= __("Naam")?></th>
                <td>
                    <?= $this->Form->input('name', array('label' => false)) ?>
                </td>
            </tr>
            <tr>
                <th><?= __("Schooljaar")?></th>
                <td>
                    <?=$this->Form->input('school_year_id', ['selected'=>$currentYearId, 'options' => $school_years, 'label' => false]) ?>
                </td>
            </tr>
        </table>
        <?= $this->Form->end(); ?>
        <a href="#" class="btn btn-close white small pull-right"><?= __("Sluiten")?></a>
        <a href="#" class="btn btn-reset white small pull-right mr5"><?= __("Reset")?></a>
        <br clear="all" />
    </div>

</div>


<h1><?= __("Klassen")?></h1>

<div class="block autoheight">
    <div class="block-head"><?= __("Klassen")?></div>
    <div class="block-content" id="classesContainer">
        <table class="table table-striped" id="classesTable">
            <thead>
            <tr>
                <th><?= __("Naam")?></th>
                <th></th>
            </tr>
            </thead>
            <tbody></tbody>
        </table>

        <script type="text/javascript">
            $('#classesTable').tablefy({
                'source': '/school_classes/load',
                'filters': $('#SchoolClassIndexForm'),
                'container': $('#classesContainer')
            });
        </script>
    </div>
    <div class="block-footer"></div>
</div>