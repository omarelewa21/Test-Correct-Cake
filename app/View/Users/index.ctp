<div id="buttons">
    <? if (in_array($type, ['students', 'teachers', 'management']) && in_array($role, ['Manager', 'School manager'])) { ?>
        <a href="#" class="btn white" onclick="User.sendWelcomeMails('<?= $type ?>');">
            Verstuur welkomstmail
        </a>
    <? } ?>

    <? if (in_array($role, ['Administrator', 'Manager', 'School manager'])) {
        if ($role === 'Administrator' && $type === 'teachers' ) {
            ?>
    <a href="#" class="btn grey mr5" onclick="Popup.load('/users/add_existing_teachers', 400);">
        <span class="fa fa-plus mr5"></span>
        Bestaande docent koppelen
    </a>
        <?}
        ?>


        <a href="#" class="btn white mr5" onclick="Popup.load('/users/add/<?= $type ?>', 400);">
            <span class="fa fa-plus mr5"></span
            >
            <?= $params['add_title'] ?>
        </a>
    <? } ?>
    <? if (in_array($role, ['School manager']) && $type === 'teachers') {
        ?>
        <a href="#" class="btn white" onclick="Navigation.load('/users/import/teachers');">
            <span class="fa fa-cloud-upload mr5"></span>
            Importeren
        </a>
        <?
    }
    ?>
    <a href="#" class="btn white dropblock-owner dropblock-left mr2" id="filters">
        <span class="fa fa-filter mr5"></span>
        Filteren
    </a>

    <div class="dropblock" for="filters">
        <?= $this->Form->create('User') ?>
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
            <? if ($type == 'students') { ?>
                <tr>
                    <th>Studentnummer</th>
                    <td>
                        <?= $this->Form->input('external_id', array('label' => false, 'type' => 'text')) ?>
                    </td>
                </tr>
                <tr>
                    <th>Locatie</th>
                    <td>
                        <?= $this->Form->input('school_location_id', ['label' => false, 'options' => $school_location]) ?>
                    </td>
                </tr>
            <? } elseif ($type == 'teachers' && in_array($role, ['Administrator']) ) { ?>
            <tr>
                <th>E-mailadres</th>
                <td>
                    <?= $this->Form->input('username', array('label' => false, 'type' => 'text')) ?>
                </td>
            </tr>
            <tr>
                <th>Locatie</th>
                <td>
                    <?= $this->Form->input('school_location_id', ['label' => false, 'options' => $school_location]) ?>
                </td>
            </tr>
            <? } ?>

        </table>
        <?= $this->Form->end(); ?>

        <a href="#" class="btn btn-close white small pull-right">Sluiten</a>
        <a href="#" class="btn btn-reset white small pull-right mr5">Reset</a>
        <br clear="all"/>
    </div>
</div>

<h1><?= $params['title'] ?></h1>

<div class="block autoheight">
    <div class="block-head"><?= $params['title'] ?></div>
    <div class="block-content" id="usersContainer">
        <table class="table table-striped" id="usersTable">
            <thead>
            <tr>
                <? if ($type == 'students') { ?>
                    <th width="50"></th>
                    <th>Voornaam</th>
                    <th>Tussenvoegsel</th>
                    <th>Achternaam</th>
                    <th>School</th>
                    <th>Klassen</th>
                    <th width="100">&nbsp;</th>
                <? } elseif ($type == 'accountmanagers') { ?>
                    <th>In dienst van</th>
                    <th>Voornaam</th>
                    <th>Tussenvoegsel</th>
                    <th>Achternaam</th>
                    <th>Accounts</th>
                    <th>Licenties totaal</th>
                    <th>Licenties actief</th>
                    <th width="100">&nbsp;</th>
                <? } elseif ($type == 'managers' || $type == 'teachers' || $type == 'students' || $type == 'management') { ?>
                    <th>Voornaam</th>
                    <th>Tussenvoegsel</th>
                    <th>Achternaam</th>
                    <? if ($type == 'teachers' && in_array($role, ['Administrator']) ) { ?>
                    <th>E-mailadres</th>
                    <? } ?>
                    <th width="100">&nbsp;</th>
                <? } ?>
            </tr>
            </thead>
            <tbody></tbody>
        </table>

        <script type="text/javascript">
            $('#usersTable').tablefy({
                'source': '/users/load/<?=$type?>',
                'filters': $('#UserIndexForm'),
                'container': $('#usersContainer')
            });
        </script>
    </div>
    <div class="block-footer"></div>
</div>
