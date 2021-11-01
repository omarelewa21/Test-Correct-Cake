<h1><?= __("Bestanden")?></h1>

<div class="block autoheight">
    <div class="block-head"><?= __("Toetsen")?></div>
    <div class="block-content" id="filesContainter">
        <table class="table table-striped" id="filesTable">
            <thead>
            <tr>
                <th width="250"><?= __("Datum")?></th>
                <th width="250"><?= __("Docent")?></th>
                <th><?= __("Vak")?></th>
                <th><?= __("Naam")?></th>
                <th width="150"><?= __("Status")?></th>
                <th width="100">&nbsp;</th>
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
