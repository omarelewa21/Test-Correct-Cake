<div id="buttons">
    <a href="#" class="btn white mr2" onclick="Popup.load('/file_management/upload_class', 800);">
        <span class="fa fa-plus mr5"></span>
        <?= __("Nieuwe Klas")?>
    </a>
    <a href="#" class="btn white mr2" onclick="Navigation.load('/file_management/classuploads');">
        <span class="fa fa-list-ul mr5"></span>
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
