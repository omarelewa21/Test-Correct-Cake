<div id="buttons">
    <a href="#" class="btn white" onclick="Popup.load('/school_classes/add', 400);">
        <span class="fa fa-plus mr5"></span>
        Nieuwe Klas
    </a>

    <a href="#" class="btn white dropblock-owner dropblock-left mr2 <?php if($currentYearId !== ''){?>highlight <?php } ?>" id="filters">
        <span class="fa fa-filter mr5"></span>
        Filteren
    </a>

    <div class="dropblock" for="filters">
        <?= $this->Form->create('SchoolClass') ?>
        <table id="testsFilter" class="mb5">
            <tr>
                <th>Naam</th>
                <td>
                    <?= $this->Form->input('name', array('label' => false)) ?>
                </td>
            </tr>
            <tr>
                <th>Schooljaar</th>
                <td>
                    <?=$this->Form->input('school_year_id', ['selected'=>$currentYearId, 'options' => $school_years, 'label' => false]) ?>
                </td>
            </tr>
        </table>
        <?= $this->Form->end(); ?>
        <a href="#" class="btn btn-close white small pull-right">Sluiten</a>
        <a href="#" class="btn btn-reset white small pull-right mr5">Reset</a>
        <br clear="all" />
    </div>

</div>


<h1>Klassen</h1>

<div class="block autoheight">
    <div class="block-head">Klassen</div>
    <div class="block-content" id="classesContainer">
        <table class="table table-striped" id="classesTable">
            <thead>
            <tr>
                <th>Naam</th>
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