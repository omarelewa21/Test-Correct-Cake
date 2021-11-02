<? if($isAdministrator): ?>
    <div id="buttons">
        <a href="#" class="btn white" onclick="Popup.load('/school_locations/add', 1100);">
            <span class="fa fa-plus mr5"></span>
            <?= __("Nieuwe schoollocatie")?>
        </a>
    </div>
<? endif; ?>

<h1><?= __("Schoollocaties")?></h1>

<div class="block autoheight">
    <div class="block-head"><?= __("Schoollocaties")?></div>
    <div class="block-content" id="schoolsContainer">
        <table class="table table-striped" id="schoolsTable">
            <thead>
            <tr>
                <th><?= __("Klantcode")?></th>
                <th><?= __("School")?></th>
                <th><?= __("Gemeenschap")?></th>
                <th><?= __("Stad")?></th>
                <th><?= __("Licenties")?></th>
                <th><?= __("Geactiveerd")?></th>
                <th><?= __("Vraagitems")?></th>
                <th></th>
            </tr>
            </thead>
            <tbody></tbody>
        </table>

        <script type="text/javascript">
            $('#schoolsTable').tablefy({
                'source' : '/school_locations/load',
                'filters' : null,
                'container' : $('#schoolsContainer')
            });
        </script>
    </div>
    <div class="block-footer"></div>
</div>