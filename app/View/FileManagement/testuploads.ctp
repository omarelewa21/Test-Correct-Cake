<div id="buttons">
    <a href="#" class="btn white mr2" onclick="Popup.load('/file_management/upload_test',600);">
        <span class="fa fa-plus mr5"></span>
        Toets uploaden
    </a>
</div>

<h1>Aangeboden toets bestanden</h1>

<div class="block autoheight">
    <div class="block-head">Toetsen</div>
    <div class="block-content" id="filesContainter">
            Hier kunt u uw toetsmateriaal aanleveren.<br />
            Onze toetsenbakkers zorgen ervoor dat deze toetsen z.s.m. klaar staan in uw itembank.
        <table class="table table-striped mt8" id="filesTable">
            <thead>
            <tr>
                <th sortkey="created_at" width="250">Datum</th>
                <th width="250" sortkey="user_id">Docent</th>
                <th>Vak</th>
                <th>Naam</th>
                <th sortkey="status" width="150">Status</th>
            </tr>
            </thead>
            <tbody></tbody>
        </table>

        <script type="text/javascript">

            $('#filesTable').tablefy({
                'source' : '/file_management/load_testuploads',
                'container' : $('#filesContainter')
            });
        </script>
    </div>
    <div class="block-footer"></div>
</div>
