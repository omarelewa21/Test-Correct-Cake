<h1>Te bespreken toetsen</h1>

<div class="block autoheight">
    <div class="block-head">Te bespreken toetsen</div>
    <div class="block-content" id="testsContainter">
        <table class="table table-striped" id="testsTable">
            <thead>
            <tr>
                <th>Toets</th>
                <th width="120">Vak</th>
                <th width="140">Afname-datum</th>
                <th width="80">Status</th>
                <th width="80">Type</th>
                <th width="60">Weging</th>
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