<? if($isAdministrator): ?>
    <div id="buttons">
        <a href="#" class="btn white" onclick="Popup.load('/schools/add', 800);">
            <span class="fa fa-plus mr5"></span>
            <?= __("Nieuwe school")?>
        </a>
    </div>
<? endif; ?>

<h1><?= __("Scholengemeenschappen")?></h1>

<div class="block autoheight">
    <div class="block-head"><?= __("Scholengemeenschappen")?></div>
    <div class="block-content" id="schoolsContainer">
        <table class="table table-striped" id="schoolsTable">
            <thead>
            <tr>
                <th><?= __("Klantnummer")?></th>
                <th><?= __("School")?></th>
                <th><?= __("Organisatie")?></th>
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
                'source' : '/schools/load',
                'filters' : null,
                'container' : $('#schoolsContainer')
            });
        </script>
    </div>
    <div class="block-footer"></div>
</div>