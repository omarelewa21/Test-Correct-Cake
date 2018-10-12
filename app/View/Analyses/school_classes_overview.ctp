<h1>Klassen analyse</h1>
<div class="block autoheight">
    <div class="block-head">Klassen overzicht</div>
    <div class="block-content" id="classesContainer">
        <table class="table" id="classesTable" cellpadding="1">
            <tr>
                <th>Klas</th>
                <th>Mentor</th>
                <th>Studenten</th>
                <?
                foreach($subjects as $subject) {
                    echo '<th>' . $subject['abbreviation'] . '</th>';
                }
                ?>
                <th width="30"></th>
            </tr>
        </table>
    </div>
</div>

<script type="text/javascript">
    $('#classesTable').tablefy({
        'source' : '/analyses/load_school_classes_overview',
        'container' : $('#classesContainer'),
        'hideEmpty' : true
    });
</script>