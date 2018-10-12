<? if($isAdministrator) : ?>
    <div id="buttons">
        <a href="#" class="btn white" onclick="Popup.load('/umbrella_organisations/add', 800);">
            <span class="fa fa-plus mr5"></span>
            Nieuwe koepelorganisatie
        </a>
    </div>
<? endif; ?>

<h1>Koepelorganisaties</h1>

<div class="block autoheight">
    <div class="block-head">Koepelorganisaties</div>
    <div class="block-content" id="organisationsContainer">
        <table class="table table-striped" id="organisationsTable">
            <thead>
            <tr>
                <th>Klantnummer</th>
                <th>Organisatie</th>
                <th>Eindklant</th>
                <th>Aantal licenties</th>
                <th>Geactiveerd</th>
                <th>Vraagitems gecre&euml;rd</th>
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