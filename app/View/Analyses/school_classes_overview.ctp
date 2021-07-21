<h1>Mijn klassen analyses</h1>
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

<?
if ($is_temp_teacher) {
?>
    <script>Notify.notify("Je kunt nog geen analyses bekijken omdat je in een tijdelijke school zit. Zodra we je verplaatst hebben naar je school kun je analyses wel bekijken. We sturen je een bericht zodra we je gekoppeld hebben aan je school.", "info", 15000);</script>
<?
} else {
?>
<script type="text/javascript">
    $('#classesTable').tablefy({
        'source' : '/analyses/load_school_classes_overview',
        'container' : $('#classesContainer'),
        'hideEmpty' : true
    });
</script>
<? } ?>