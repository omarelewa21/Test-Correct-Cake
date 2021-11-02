<div id="buttons">
    <a href="#" class="btn white mr2" onclick="Popup.load('/file_management/upload_test',800);">
        <span class="fa fa-plus mr5"></span>
        <?= __("Toets uploaden")?>
    </a>
</div>

<h1><?= __("Mijn geüploade toetsbestanden")?></h1>

<div class="block autoheight">
    <div class="block-head"><?= __("Toetsen")?></div>
    <div class="block-content" id="filesContainter">
    <?= __("Hier kunt u uw toetsmateriaal aanleveren.")?><br />
    <?= __("Onze toetsenbakkers zorgen ervoor dat deze toetsen z.s.m. klaar staan in uw itembank.")?>
        <table class="table table-striped mt8" id="filesTable">
            <thead>
            <tr>
                <th sortkey="created_at" width="250"><?= __("Datum")?></th>
                <th width="250" sortkey="user_id"><?= __("Docent")?></th>
                <th><?= __("Vak")?></th>
                <th><?= __("Naam")?></th>
                <th sortkey="status" width="150"><?= __("Status")?></th>
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
