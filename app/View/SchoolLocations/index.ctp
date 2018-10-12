<? if($isAdministrator): ?>
    <div id="buttons">
        <a href="#" class="btn white" onclick="Popup.load('/school_locations/add', 1100);">
            <span class="fa fa-plus mr5"></span>
            Nieuwe schoollocatie
        </a>
    </div>
<? endif; ?>

<h1>Schoollocaties</h1>

<div class="block autoheight">
    <div class="block-head">Schoollocaties</div>
    <div class="block-content" id="schoolsContainer">
        <table class="table table-striped" id="schoolsTable">
            <thead>
            <tr>
                <th>Klantcode</th>
                <th>School</th>
                <th>Gemeenschap</th>
                <th>Stad</th>
                <th>Licenties</th>
                <th>Geactiveerd</th>
                <th>Vraagitems</th>
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