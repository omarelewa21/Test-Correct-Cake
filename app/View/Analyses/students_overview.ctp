<div id="buttons">
    <a href="#" class="btn white dropblock-owner dropblock-left mr2" id="filters">
        <span class="fa fa-filter mr5"></span>
        Filteren
    </a>

    <div class="dropblock" for="filters">
        <?=$this->Form->create('User')?>
        <table id="testsFilter" class="mb5">
            <tr>
                <th>Voornaam</th>
                <td>
                    <?=$this->Form->input('name_first', array('label' => false)) ?>
                </td>
            </tr>
            <tr>
                <th>Achternaam</th>
                <td>
                    <?=$this->Form->input('name', array('label' => false)) ?>
                </td>
            </tr>
            <tr>
                <th>Studentnummer</th>
                <td>
                    <?=$this->Form->input('external_id', array('label' => false, 'type' => 'text')) ?>
                </td>
            </tr>
            <tr>
                <th>Locatie</th>
                <td>
                    <?=$this->Form->input('school_location_id', ['label' => false, 'options' => $school_location])?>
                </td>
            </tr>
            <tr>
                <th>Klas</th>
                <td>
                    <?=$this->Form->input('school_class_id', ['label' => false, 'options' => $school_classes])?>
                </td>
            </tr>
        </table>
        <?=$this->Form->end();?>

        <a href="#" class="btn btn-close white small pull-right">Sluiten</a>
        <br clear="all" />
    </div>
</div>

<h1>Studenten analyse</h1>
<div class="block autoheight">
    <div class="block-head">Studenten overzicht</div>
    <div class="block-content" id="studentsContainer">
        <table class="table" id="studentsTable" cellpadding="1">
            <thead>
                <th>Nummer</th>
                <th>Voornaam</th>
                <th>Achternaam</th>
                <th>Klas</th>
                <?
                foreach($subjects as $subject) {
                    echo '<th>' . $subject['abbreviation'] . '</th>';
                }
                ?>
                <th width="30"></th>
            </thead>
            <tbody>

            </tbody>
        </table>
    </div>
</div>

<?
if ($is_temp_teacher) {
?>
    <script>Notify.notify("Je kunt nog geen analyses bekijken omdat je in een tijdelijke school zit. Zodra we je verplaatst hebben naar je school kun je analyses wel bekijken. We sturen je een bericht zodra we je gekoppeld hebben aan je school.", "info", 15000);</script>
<?
} else 
{
?>
<script type="text/javascript">
    $('#studentsTable').tablefy({
        'source' : '/analyses/load_students_overview',
        'container' : $('#studentsContainer'),
        'filters' : $('#UserStudentsOverviewForm'),
        hideEmpty : true
    });
</script>
<? } ?>