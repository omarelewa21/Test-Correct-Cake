<div id="buttons">
    <a href="#" class="btn white mr2" onclick="Navigation.load('/teacher_classes');">
        <span class="fa fa-backward mr5"></span>
        <?= __("Terug")?>
    </a>
    <?php if($has_lvs){ ?>
        <?php if($has_incomplete_import) { ?>
            <a href="#" class="btn white mr2" onclick="Navigation.callback=function(){displayCompleteUserImport();};Navigation.load('users/welcome');">
                <span class="fa fa-plus mr5"></span>
                <?= __("Importgegevens voor klassen compleet maken")?>
            </a>
        <?php } else { ?>
            <a href="#" class="btn white mr2" onclick="Popup.confirm(
                    {
                    'title' : '<?= __('Klassen zijn reeds gekoppeld via de automatische import')?>',
                    'text': '<?= __('Neem contact op met onze support afdeling indien je gekoppeld bent aan de verkeerde klassen.')?>',
                    'okBtn': $.i18n('Ok')
                    },function() {
                    return true;
                    });">
                    <span class="fa fa-plus mr5"></span>
            <?= __("Nieuwe Klas")?>
        </a>
        <?php } ?>
    <?php } else { ?>}
    <a href="#" class="btn white mr2" onclick="Popup.load('/file_management/upload_class', 800);">
        <span class="fa fa-plus mr5"></span>
        <?= __("Nieuwe Klas")?>
    </a>
    <?php } ?>
</div>
<h1><?= __("Aangeboden klassen bestanden")?></h1>

<div class="block autoheight">
    <div class="block-head"><?= __("Bestanden")?></div>
    <div class="block-content" id="filesContainter">
        <table class="table table-striped" id="filesTable">
            <thead>
            <tr>
                <th sortkey="created_at" width="250"><?= __("Datum")?></th>
                <th width="250" sortkey="user_id"><?= __("Docent")?></th>
                <th sortkey="class"><?= __("Klas")?></th>
                <th sortkey="status" width="150"><?= __("Status")?></th>
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
