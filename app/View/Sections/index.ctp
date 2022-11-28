<div id="buttons">
    <a href="#" class="btn white" onclick="Popup.load('/sections/add', 400);">
        <span class="fa fa-plus mr5"></span>
        <?= __("Nieuwe Sectie")?>
    </a>
</div>

<h1><?= __("Secties")?></h1>

<div class="block autoheight">
    <div class="block-head"><?= __("Secties")?></div>
    <div class="block-content" id="sectionsContainer">
        <table class="table table-striped" id="sectionsTable">
            <thead>
            <tr>
                <th><?= __("Sectie")?></th>
                <th><?= __("Schoollocaties")?></th>
                <th><?= __("Subject") ?></th>
                <th><?= __("Afk.") ?></th>
                <th><?= __("Categorie") ?></th>
                <th></th>
            </tr>
            </thead>
            <tbody></tbody>
        </table>

        <script type="text/javascript">
            $('#sectionsTable').tablefy({
                'source' : '/sections/load',
                'filters' : null,
                'container' : $('#sectionsContainer')
            });
        </script>
    </div>
    <div class="block-footer"></div>
</div>
