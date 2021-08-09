<div id="buttons">
    <a href="#" class="btn white mr2" onclick="Navigation.back();">
        <span class="fa fa-backward mr5"></span>
        Terug
    </a>
</div>

<h1>Docenten import</h1>


<div class="block">
    <div class="block-head">Data
        <button id="setDefaultHeadingTeacher">Zet default kolom headers!!</button>
    </div>
    <div class="duplicate" id="duplicates-data-errors"></div>
    <div class="duplicates-in-database-data-errors" id="duplicates-in-database-data-errors"></div>
    <div class="error" id="column-errors"></div>
    <div class="error" id="missing-data-errors"></div>
    <div class="block-content">

        <div>
            <div>
                <textarea rows="1" id="excelPasteBox" placeholder="Plak je excel data hier..."></textarea>
            </div>
            <hr class="showAfterProcess"/>
            <div id="output">
            </div>
            <hr class="showAfterProcess"/>
            <a href="#" id="exportJsonData" style="display:none" class="btn highlight inline-block showAfterProcess">Docenten
                importeren</a>
            <textarea style="display:none !important" rows="20" id="jsonDataDump"
                      placeholder="JSON Data will appear here..."></textarea>
        </div>
    </div>
</div>



<script>
    var url = '/users/doImportTeachers';

    var dbFields = [
        {'column': 'name_first', 'name': 'Voornaam'},
        {'column': 'name_suffix', 'name': 'tussenvoegsel'},
        {'column': 'name', 'name': 'Achternaam'},
        {'column': 'abbreviation', 'name': 'Afkorting'},
        {'column': 'username', 'name': 'E-mailadres'},
        {'column': 'external_id', 'name': 'Externe code'},
        {'column': 'note', 'name': 'Notities'},
        {'column': 'school_class', 'name': 'Koppeling klasnaam'},
        {'column': 'subject', 'name': 'Koppeling welk vak'},
    ];
</script>
<?= $this->element('import_users_as_teachers_style_and_script',['type'=>'teachers']) ?>

