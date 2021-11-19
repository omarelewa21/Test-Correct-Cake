<h1><?= __("Bespreken met CO-Learning")?></h1>

<div class="block autoheight">
    <div class="block-head"><?= __("Bespreken met CO-Learning")?></div>
    <div class="block-content" id="testsContainter">
        <table class="table table-striped" id="testsTable">
            <thead>
            <tr>
                <th><?= __("Toets")?></th>
                <th width="120"><?= __("Vak")?></th>
                <th width="140"><?= __("Afname-datum")?></th>
                <th width="80"><?= __("Status")?></th>
                <th width="80"><?= __("Type")?></th>
                <th width="60"><?= __("Weging")?></th>
                <th width="30">&nbsp;</th>
            </tr>
            </thead>
            <tbody></tbody>
        </table>

        <script type="text/javascript">
            $('#testsTable').tablefy({
                'source' : '/test_takes/load_taken_student',
                'filters' : null,
                'container' : $('#testsContainter')
            });
        </script>
    </div>
    <div class="block-footer"></div>
</div>