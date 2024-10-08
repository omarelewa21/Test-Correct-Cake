<div id="buttons">
    <? if (in_array($type, ['students', 'teachers', 'management']) && in_array($role, ['Manager', 'School manager'])) { ?>
        <a href="#" class="btn white" onclick="User.sendWelcomeMails('<?= $type ?>');">
            <?= __("Verstuur welkomstmail") ?>
        </a>
    <? } ?>

    <? if (in_array($role, ['Administrator', 'Manager', 'School manager'])) {
        if ($role === 'School manager' && $type === 'teachers') {
            ?>
            <a href="#" class="btn white" onclick="Popup.load('/users/add_existing_teachers', 900);">
                <span class="fa fa-plus mr5"></span>
                <?= __("Bestaande docent koppelen") ?>
            </a>
            <?
        }
        ?>

        <? if ($type !== 'trial_teachers') { ?>
        <a href="#" class="btn white" onclick="Popup.load('/users/add/<?= $type ?>', 400);">
            <span class="fa fa-plus mr5"></span
            >
            <?= $params['add_title'] ?>
        </a>
        <? } ?>
    <? } ?>

    <? if (in_array($role, ['School manager']) && $type === 'students') {
        ?>
        <a href="#" class="btn white" onclick="Navigation.load('/users/import/students');">
            <span class="fa fa-cloud-upload mr5"></span>
            <?= __("Studenten importeren") ?>
        </a>
        <?
    }
    ?>

    <? if (in_array($role, ['School manager']) && $type === 'teachers') {
        ?>
        <a href="#" class="btn white" onclick="User.importTeachersChooseTypePopup()">
            <span class="fa fa-cloud-upload mr5"></span>
            <?= __("Importeren") ?>
        </a>
        <?
    }
    ?>
    <a href="#" class="btn white dropblock-owner dropblock-left mr2" id="filters">
        <span class="fa fa-filter mr5"></span>
        <?= __("Filteren") ?>
    </a>

    <div class="dropblock" for="filters">
        <?= $this->Form->create('User') ?>
        <table id="testsFilter" class="mb5">
            <tr>
                <th><?= __("Voornaam") ?></th>
                <td>
                    <?= $this->Form->input('name_first', array('label' => false)) ?>
                </td>
            </tr>
            <tr>
                <th><?= __("Achternaam") ?></th>
                <td>
                    <?= $this->Form->input('name', array('label' => false)) ?>
                </td>
            </tr>
            <? if ($type == 'students') { ?>
                <tr style="<?= ($role == 'Support') ? 'display:none;' : '' ?>">
                    <th><?= __("Studentnummer") ?></th>
                    <td>
                        <?= $this->Form->input('external_id', array('label' => false, 'type' => 'text')) ?>
                    </td>
                </tr>
                <tr>
                    <th><?= __("Locatie") ?></th>
                    <td>
                        <?= $this->Form->input('school_location_id', ['label' => false, 'options' => $school_location]) ?>
                    </td>
                </tr>
            <? } elseif (in_array($type, ['teachers', 'trial_teachers']) && in_array($role, ['Administrator'])) { ?>
                <tr>
                    <th><?= __("E-mailadres") ?></th>
                    <td>
                        <?= $this->Form->input('username', array('label' => false, 'type' => 'text')) ?>
                    </td>
                </tr>
                <tr>
                    <th><?= __("Locatie") ?></th>
                    <td>
                        <?= $this->Form->input('school_location_id', ['label' => false, 'options' => $school_location]) ?>
                    </td>
                </tr>
                <tr>
                    <th><?= __("Status") ?></th>
                    <td>
                        <?= $this->Form->input('trial_status', ['label' => false, 'options' => array('0' => 'All', '1' => 'Not started', '2' => 'Active','3'=>'Expired')]) ?>
                    </td>
                </tr>
                <tr>
                    <th><?= __("Pakket") ?></th>
                    <td>
                        <?= $this->Form->input('has_package', ['label' => false, 'options' => array('0' => 'All', '1' => 'On', '2' => 'Off')]) ?>
                    </td>
                </tr>
                
            <? } elseif ($role == 'Support' && in_array($type, ['teachers', 'students'])) { ?>
                <tr>
                    <th><?= __("Locatie") ?></th>
                    <td>
                        <?= $this->Form->input('school_location_id', ['label' => false, 'options' => $school_location]) ?>
                    </td>
                </tr>
            <? } ?>

        </table>
        <?= $this->Form->end(); ?>

        <a href="#" class="btn btn-close white small pull-right"><?= __("Sluiten") ?></a>
        <a href="#" class="btn btn-reset white small pull-right mr5"><?= __("Reset") ?></a>
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
                <? if ($role == 'Support') { ?>
                    <th><?= __('Voornaam') ?></th>
                    <th><?= __('Tussenvoegsel') ?></th>
                    <th><?= __('Achternaam') ?></th>
                    <? if ($type == 'teachers') { ?>
                    <th><?= __("Status") ?></th>
                    <th><?= __("Dagen over") ?></th>
                    <? } ?>
                    <th><?= __('School') ?></th>
                    <th width="100"></th>
                <? } else { ?>
                    <? if ($type == 'students') { ?>
                        <th width="50"></th>
                        <th><?= __("Voornaam") ?></th>
                        <th><?= __("Tussenvoegsel") ?></th>
                        <th><?= __("Achternaam") ?></th>
                        <th><?= __("School") ?></th>
                        <th><?= __("Klassen") ?></th>
                        <th width="100">&nbsp;</th>
                    <? } elseif ($type == 'accountmanagers') { ?>
                        <th><?= __("In dienst van") ?></th>
                        <th><?= __("Voornaam") ?></th>
                        <th><?= __("Tussenvoegsel") ?></th>
                        <th><?= __("Achternaam") ?></th>
                        <th><?= __("Accounts") ?></th>
                        <th><?= __("Licenties totaal") ?></th>
                        <th><?= __("Licenties actief") ?></th>
                        <th width="100">&nbsp;</th>
                    <? } elseif ($type == 'trial_students' || $type == 'trial_teachers') { ?>
                        <th><?= __("Voornaam") ?></th>
                        <th><?= __("Tussenvoegsel") ?></th>
                        <th><?= __("Achternaam") ?></th>
                        <th><?= __("E-mailadres") ?></th>
                        <th><?= __("School") ?></th>
                        <th><?= __("Status") ?></th>
                        <th><?= __("Dagen over") ?></th>
                        <th width="100">&nbsp;</th>
                    <? } elseif ($type == 'managers' || $type == 'teachers' || $type == 'students' || $type == 'management' || 'support' || $type == 'test_team') { ?>
                        <th><?= __("Voornaam") ?></th>
                        <th><?= __("Tussenvoegsel") ?></th>
                        <th><?= __("Achternaam") ?></th>
                        <? if ($type == 'teachers' && in_array($role, ['Administrator'])) { ?>
                            <th><?= __("E-mailadres") ?></th>
                        <? } ?>
                        <th width="140">&nbsp;</th>
                    <? } ?>
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
