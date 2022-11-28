<div id="buttons"></div>


<h1><?= __("Mijn klassen")?></h1>

<div class="block autoheight">
    <div class="block-head"><?= __("Klassen")?></div>
    <div class="block-content" id="classesContainer">
        <table class="table table-striped" id="classesTable">
            <thead>
            <tr>
                <th sortkey="name"><?= __("Naam")?></th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            <script type="text/javascript">
                $('#classesTable').tablefy({
                    'source' : '/teacher_analyses/load',
                    'container' : $('#classesContainer')
                });
            </script>

            </tbody>
        </table>

    </div>
    <div class="block-footer"></div>
</div>
