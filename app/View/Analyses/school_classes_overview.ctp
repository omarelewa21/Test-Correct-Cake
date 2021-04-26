<h1><?= __("Klassen analyse")?></h1>
<div class="block autoheight">
    <div class="block-head"><?= __("Klassen overzicht")?></div>
    <div class="block-content" id="classesContainer">
        <table class="table" id="classesTable" cellpadding="1">
            <tr>
                <th><?= __("Klas")?></th>
                <th><?= __("Mentor")?></th>
                <th><?= __("Studenten")?></th>
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
    <script>Notify.notify('<?= __("Je kunt nog geen analyses bekijken omdat je in een tijdelijke school zit. Zodra we je verplaatst hebben naar je school kun je analyses wel bekijken. We sturen je een bericht zodra we je gekoppeld hebben aan je school")?>', "info", 15000);</script>
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