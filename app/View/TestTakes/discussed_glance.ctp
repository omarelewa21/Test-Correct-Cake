<h1>Toetsen inzien</h1>

<div class="block autoheight">
    <div class="block-head">Toetsen inzien</div>
    <div class="block-content" id="testsContainter">
        <table class="table table-striped" id="testsTable">
            <thead>
            <tr>
                <th>Toets</th>
                <th>Notities surveillant</th>
                <th width="140">Afname-datum</th>
                <th width="140">Inzien tot</th>
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