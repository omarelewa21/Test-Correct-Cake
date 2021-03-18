<? if($isAdministrator) : ?>
    <div id="buttons">
        <a href="#" class="btn white" onclick="Popup.load('/umbrella_organisations/add', 800);">
            <span class="fa fa-plus mr5"></span>
            <?= __("Nieuwe koepelorganisatie")?>
        </a>
    </div>
<? endif; ?>

<h1><?= __("Koepelorganisaties")?></h1>

<div class="block autoheight">
    <div class="block-head"><?= __("Koepelorganisaties")?></div>
    <div class="block-content" id="organisationsContainer">
        <table class="table table-striped" id="organisationsTable">
            <thead>
            <tr>
                <th><?= __("Klantnummer")?></th>
                <th><?= __("Organisatie")?></th>
                <th><?= __("Eindklant")?></th>
                <th><?= __("Aantal licenties")?></th>
                <th><?= __("Geactiveerd")?></th>
                <th><?= __("Vraagitems gecreëerd")?></th>
                <th></th>
            </tr>
            </thead>
            <tbody></tbody>
        </table>

        <script type="text/javascript">
            $('#organisationsTable').tablefy({
                'source' : '/umbrella_organisations/load',
                'filters' : null,
                'container' : $('#organisationsContainer')
            });
        </script>
    </div>
    <div class="block-footer"></div>
</div>