<div id="buttons">
    <a href="#" class="btn white" onclick="Popup.load('/school_classes/add', 400);">
        <span class="fa fa-plus mr5"></span>
        Nieuwe Klas
    </a>
</div>


<h1>Klassen</h1>

<div class="block autoheight">
    <div class="block-head">Klassen</div>
    <div class="block-content" id="classesContainer">
        <table class="table table-striped" id="classesTable">
            <thead>
            <tr>
                <th>Naam</th>
                <th></th>
            </tr>
            </thead>
            <tbody></tbody>
        </table>

        <script type="text/javascript">
            $('#classesTable').tablefy({
                'source' : '/school_classes/load',
                'filters' : null,
                'container' : $('#classesContainer')
            });
        </script>
    </div>
    <div class="block-footer"></div>
</div>