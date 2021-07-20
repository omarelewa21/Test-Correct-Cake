<h1>Klassen binnen schoollocatie</h1>

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
            <tbody>
            <script type="text/javascript">
                $('#classesTable').tablefy({
                    'source' : '/teacher_classes/load_school_location_classes',
                    'container' : $('#classesContainer')
                });
            </script>

            </tbody>
        </table>

    </div>
    <div class="block-footer"></div>
</div>