<div id="buttons">
    <a href="#" class="btn white" onclick="Popup.load('/sections/add', 400);">
        <span class="fa fa-plus mr5"></span>
        Nieuwe Sectie
    </a>
</div>

<h1>Secties</h1>

<div class="block autoheight">
    <div class="block-head">Secties</div>
    <div class="block-content" id="sectionsContainer">
        <table class="table table-striped" id="sectionsTable">
            <thead>
            <tr>
                <th>Sectie</th>
                <th>Schoollocaties</th>
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