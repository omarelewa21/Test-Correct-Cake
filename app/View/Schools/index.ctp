<? if($isAdministrator): ?>
    <div id="buttons">
        <a href="#" class="btn white" onclick="Popup.load('/schools/add', 800);">
            <span class="fa fa-plus mr5"></span>
            Nieuwe school
        </a>
    </div>
<? endif; ?>

<h1>Scholengemeenschappen</h1>

<div class="block autoheight">
    <div class="block-head">Scholengemeenschappen</div>
    <div class="block-content" id="schoolsContainer">
        <table class="table table-striped" id="schoolsTable">
            <thead>
            <tr>
                <th>Klantnummer</th>
                <th>School</th>
                <th>Organisatie</th>
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
                'source' : '/schools/load',
                'filters' : null,
                'container' : $('#schoolsContainer')
            });
        </script>
    </div>
    <div class="block-footer"></div>
</div>