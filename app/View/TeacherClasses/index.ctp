<div id="buttons">
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
    <?php } else { ?>
        <a href="#" class="btn white mr2" onclick="Popup.load('/file_management/upload_class', 800);">
            <span class="fa fa-plus mr5"></span>
            <?= __("Nieuwe Klas")?>
        </a>
    <?php } ?>
    <a href="#" class="btn white mr2" onclick="Navigation.load('/file_management/classuploads');">
        <span class="fa fa-ellipsis-v mr5"></span>
        <?= __("Aangeboden bestanden")?>
    </a>
</div>


<h1><?= __("Mijn klassen")?></h1>

<div class="block autoheight">
    <div class="block-head"><?= __("Klassen")?></div>
    <div class="block-content" id="classesContainer">
        <table class="table table-striped" id="classesTable">
            <thead>
            <tr>
                <th sortkey="name"><?= __("Naam")?></th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            <script type="text/javascript">
                $('#classesTable').tablefy({
                    'source' : '/teacher_classes/load',
                    'container' : $('#classesContainer')
                });
            </script>

            </tbody>
        </table>

    </div>
    <div class="block-footer"></div>
</div>
