<h1><?= __("Geplande toetsen")?></h1>

<div class="block autoheight">
    <div class="block-head"><?= __("Geplande toetsen")?></div>
    <div class="block-content" id="testsContainter">
        <table class="table table-striped" id="testsTable">
            <thead>
            <tr>
                <th><?= __("Toets")?></th>
                <th width="50"><?= __("Vragen")?></th>
                <th><?= __("Surveillanten")?></th>
                <th width="200"><?= __("Ingepland door")?></th>
                <th width="120"><?= __("Vak")?></th>
                <th width="140"><?= __("Afname-datum")?></th>
                <th width="80"><?= __("Status")?></th>
                <th width="80"><?= __("Type")?></th>
                <th width="60"><?= __("Weging")?></th>
                <th width="100">&nbsp;</th>
            </tr>
            </thead>
            <tbody></tbody>
        </table>

        <script type="text/javascript">
            $('#testsTable').tablefy({
                'source' : '/test_takes/load_planned_student',
                'filters' : $('#TestTakeIndexForm'),
                'container' : $('#testsContainter')
});
</script>
</div>
<div class="block-footer"></div>
</div>