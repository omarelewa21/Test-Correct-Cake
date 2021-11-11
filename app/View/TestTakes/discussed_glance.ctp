<h1><?= __("Toetsen inzien")?></h1>

<div class="block autoheight">
    <div class="block-head"><?= __("Toetsen inzien")?></div>
    <div class="block-content" id="testsContainter">
        <table class="table table-striped" id="testsTable">
            <thead>
            <tr>
                <th><?= __("Toets")?></th>
                <th><?= __("Notities surveillant")?></th>
                <th width="140"><?= __("Afname-datum")?></th>
                <th width="140"><?= __("Inzien tot")?></th>
                <th width="100">&nbsp;</th>
            </tr>
            </thead>
            <tbody></tbody>
        </table>

        <script type="text/javascript">
            $('#testsTable').tablefy({
                'source' : '/test_takes/load_discussed_glance',
                'filters' : null,
                'container' : $('#testsContainter')
            });
        </script>
    </div>
    <div class="block-footer"></div>
</div>