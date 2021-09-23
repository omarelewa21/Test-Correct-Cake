<div id="buttons">
    <a href="#" class="btn white dropblock-owner dropblock-left mr2" id="filters">
        <span class="fa fa-filter mr5"></span>
        Filteren
    </a>

    <div class="dropblock" for="filters">
        <?= $this->Form->create('Support') ?>
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

        </table>
        <?= $this->Form->end(); ?>

        <a href="#" class="btn btn-close white small pull-right">Sluiten</a>
        <a href="#" class="btn btn-reset white small pull-right mr5">Reset</a>
        <br clear="all"/>
    </div>
</div>

<h1>Support logs</h1>

<div class="block autoheight">
    <div class="block-content" id="usersContainer">
        <table class="table table-striped" id="usersTable">
            <thead>
            <tr>
                <th width="250">Support gebruiker</th>
                <th width="250">Gebruiker</th>
                <th width="180">Datum</th>
                <th>IP</th>
                <th width="30">&nbsp;</th>
            </tr>
            </thead>
            <tbody></tbody>
        </table>

        <script type="text/javascript">
            $('#usersTable').tablefy({
                'source': '/support/load',
                'filters': $('#SupportIndexForm'),
                'container': $('#usersContainer')
            });
        </script>
    </div>
    <div class="block-footer"></div>
</div>
