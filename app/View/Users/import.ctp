<div id="buttons">
    <a href="#" class="btn white mr2" onclick="Navigation.back();">
        <span class="fa fa-backward mr5"></span>
        <?= __("Terug")?>
    </a>
</div>

<h1><?= __("Docenten import")?></h1>


<div class="block">
    <div class="block-head"><?= __("Data")?>
        <button id="setDefaultHeadingTeacher"><?= __("Zet default kolom headers!!")?></button>
    </div>
    <div class="duplicate" id="duplicates-data-errors"></div>
    <div class="duplicates-in-database-data-errors" id="duplicates-in-database-data-errors"></div>
    <div class="error" id="column-errors"></div>
    <div class="error" id="missing-data-errors"></div>
    <div class="block-content">

        <div>
            <div>
                <textarea rows="1" id="excelPasteBox" placeholder='<?= __("Plak je excel data hier...")?>'></textarea>
            </div>
            <hr class="showAfterProcess"/>
            <div id="output">
            </div>
            <hr class="showAfterProcess"/>
            <a href="#" id="exportJsonData" style="display:none" class="btn highlight inline-block showAfterProcess"><?= __("Docenten")?>
            <?= __("importeren")?></a>
            <textarea style="display:none !important" rows="20" id="jsonDataDump"
                      placeholder='<?= __("JSON Data will appear here...")?>'></textarea>
        </div>
    </div>
</div>



<script>
    var url = '/users/doImportTeachers';

    var dbFields = [
        {'column': 'name_first', 'name': '<?= __("Voornaam")?>'},
        {'column': 'name_suffix', 'name': '<?= __("tussenvoegsel")?>'},
        {'column': 'name', 'name': '<?= __("Achternaam")?>'},
        {'column': 'abbreviation', 'name': '<?= __("Afkorting")?>'},
        {'column': 'username', 'name': '<?= __("E-mailadres")?>'},
        {'column': 'external_id', 'name': '<?= __("Externe code")?>'},
        {'column': 'note', 'name': '<?= __("Notities")?>'},
        {'column': 'school_class', 'name': '<?= __("Koppeling klasnaam")?>'},
        {'column': 'subject', 'name': '<?= __("Koppeling welk vak")?>'},
    ];
</script>
<?= $this->element('import_users_as_teachers_style_and_script',['type'=>'teachers']) ?>

