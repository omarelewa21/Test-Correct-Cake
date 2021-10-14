<h1><?=__('Support logs')?></h1>

<div class="block autoheight">
    <div class="block-content" id="supportLogsContainer">
        <table class="table table-striped" id="supportLogsTable">
            <thead>
            <tr>
                <th width="250"><?= __('Support gebruiker') ?></th>
                <th width="250"><?=__('Gebruiker')?></th>
                <th><?=__('Schoollocatie')?></th>
                <th width="180" sortkey="created_at"><?=__('Datum')?></th>
                <th><?=__('Ip')?></th>
                <th width="30">&nbsp;</th>
            </tr>
            </thead>
            <tbody></tbody>
        </table>

        <script type="text/javascript">
            $('#supportLogsTable').tablefy({
                'source': '/support/load',
                'filters': null,
                'container': $('#supportLogsContainer')
            });
        </script>
    </div>
    <div class="block-footer"></div>
</div>
