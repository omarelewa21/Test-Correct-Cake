<div class="popup-head">Docenten van andere locaties toevoegen</div>
<div class="popup-content" >

    <div style="display:none;" id="buttons" class="pull-right">
        <a href="#" class="btn white dropblock-owner dropblock-left mr2" id="filters">
            <span class="fa fa-filter mr5"></span>
            Filteren
        </a>

        <div class="dropblock" for="filters">
            <?= $this->Form->create('ExistingTeacher') ?>
            <table id="testsFilter" class="mb5">
                <tr>
                    <th>Voornaam</th>
                    <td>
                        <?= $this->Form->input('name_first', array('label' => false)) ?>
                    </td>
                </tr>
                <tr>
                    <th>Achternaam</th>
                    <td>
                        <?= $this->Form->input('name', array('label' => false)) ?>
                    </td>
                </tr>

                    <tr>
                        <th>E-mailadres</th>
                        <td>
                            <?= $this->Form->input('username', array('label' => false, 'type' => 'text')) ?>
                        </td>
                    </tr>


            </table>
            <?= $this->Form->end(); ?>

            <a href="#" class="btn btn-close white small pull-right">Sluiten</a>
            <a href="#" class="btn btn-reset white small pull-right mr5">Reset</a>
            <br clear="all"/>
        </div>
    </div>



    <div class="block autoheight">

        <div class="block-content" id="existingTeachersContainer">
            <table class="table table-striped" id="existingTeachersTable">
                <thead>
                <tr>
                    <th>Voornaam</th>
                    <th>Tussenvoegsel</th>
                    <th>Achternaam</th>
                    <th>E-mailadres</th>
                    <th>Stammnummer</th>
                    <th width="100">&nbsp;</th>
                </tr>
                </thead>
                <tbody></tbody>
            </table>

            <script type="text/javascript">
                $('#existingTeachersTable').tablefy({
                    'source': '/users/load_existing_teachers',
                    'filters': $('#ExistingTeacherIndexForm'),
                    'container': $('#existingTeachersContainer')
                });
            </script>
        </div>
        <div class="block-footer"></div>
    </div>

</div>
<div class="popup-footer">
    <a href="#" class="btn grey mt5 mr5 pull-right" onclick="Popup.closeLast();Navigation.load('users/index/teachers')">
        Annuleer
    </a>
</div>

