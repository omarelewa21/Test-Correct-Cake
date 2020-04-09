<h1>Aangeboden klassen bestanden</h1>

<div class="block autoheight">
    <div class="block-head">Toetsen</div>
    <div class="block-content" id="filesContainter">
        <table class="table table-striped" id="filesTable">
            <thead>
            <tr>
                <th sortkey="created_at" width="250">Datum</th>
                <th width="250" sortkey="user_id">Docent</th>
                <th sortkey="class">Klas</th>
                <th sortkey="status" width="150">Status</th>
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
