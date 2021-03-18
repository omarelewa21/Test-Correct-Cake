<div class="popup-head"><?= __("Docenten van andere locaties toevoegen")?></div>
<div class="popup-content" >

    <div id="buttons" class="pull-right">
        <a href="#" class="btn white dropblock-owner dropblock-left mr2" id="filters_existing_teacher">
            <span class="fa fa-filter mr5"></span>
            <?= __("Filteren")?>
        </a>

        <div class="dropblock" for="filters_existing_teacher">
            <?= $this->Form->create('ExistingTeacher') ?>
            <table id="testsFilter" class="mb5">
                <tr>
                    <th><?= __("Voornaam")?></th>
                    <td>
                        <?= $this->Form->input('name_first', array('label' => false)) ?>
                    </td>
                </tr>
                <tr>
                    <th><?= __("Achternaam")?></th>
                    <td>
                        <?= $this->Form->input('name', array('label' => false)) ?>
                    </td>
                </tr>

                    <tr>
                        <th><?= __("E-mailadres")?></th>
                        <td>
                            <?= $this->Form->input('username', array('label' => false, 'type' => 'text')) ?>
                        </td>
                    </tr>


            </table>
            <?= $this->Form->end(); ?>

            <a href="#" class="btn btn-close white small pull-right"><?= __("Sluiten")?></a>
            <a href="#" class="btn btn-reset white small pull-right mr5"><?= __("Reset")?></a>
            <br clear="all"/>
        </div>
    </div>



    <div class="block autoheight">

        <div style="padding:25px;" id="existingTeachersContainer">
            <table class="table table-striped" id="existingTeachersTable">
                <thead>
                <tr>
                    <th><?= __("Voornaam")?></th>
                    <th><?= __("Tussenvoegsel")?></th>
                    <th><?= __("Achternaam")?></th>
                    <th><?= __("E-mailadres")?></th>
                    <th><?= __("Stammnummer")?></th>
                    <th width="100">&nbsp;</th>
                </tr>
                </thead>
                <tbody></tbody>
            </table>

            <script type="text/javascript">
                $('#existingTeachersTable').tablefy({
                    'source': '/users/load_existing_teachers',
                    'filters': $('#ExistingTeacherAddExistingTeachersForm'),
                    'container': $('#existingTeachersContainer')
                });
            </script>
        </div>
        <div class="block-footer"></div>
    </div>

</div>
<div class="popup-footer">
    <a href="#" class="btn grey mt5 mr5 pull-right" onclick="Popup.closeLast();Navigation.load('users/index/teachers')">
    <?= __("Sluiten")?>
    </a>
</div>

