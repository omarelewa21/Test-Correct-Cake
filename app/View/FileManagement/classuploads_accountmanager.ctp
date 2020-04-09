<h1>Bestanden</h1>

<div class="block autoheight">
    <div class="block-head">Toetsen</div>
    <div class="block-content" id="filesContainter">
        <table class="table table-striped" id="filesTable">
            <thead>
            <tr>
                <th width="250">Datum</th>
                <th width="200">School</th>
                <th width="74">Code</th>
                <th width="250">Docent</th>
                <th>Klas</th>
                <th width="150">Status</th>
                <th width="250">Behandelaar</th>
                <th width="100">&nbsp;</th>
            </tr>
            </thead>
            <tbody></tbody>
        </table>

        <script type="text/javascript">

            $('#filesTable').tablefy({
                'source' : '/file_management/load_classuploads',
                'container' : $('#filesContainter')
            });
        </script>
    </div>
    <div class="block-footer"></div>
</div>
