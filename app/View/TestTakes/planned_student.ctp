<h1>Geplande toetsen</h1>

<div class="block autoheight">
    <div class="block-head">Geplande toetsen</div>
    <div class="block-content" id="testsContainter">
        <table class="table table-striped" id="testsTable">
            <thead>
            <tr>
                <th>Toets</th>
                <th width="50">Vragen</th>
                <th>Surveillanten</th>
                <th width="200">Ingepland door</th>
                <th width="120">Vak</th>
                <th width="140">Afname-datum</th>
                <th width="80">Status</th>
                <th width="80">Type</th>
                <th width="60">Weging</th>
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