<div id="buttons">
    <a href="#" class="btn white mr2" onclick="Navigation.back();">
        <span class="fa fa-backward mr5"></span>
        Terug
    </a>
</div>

<h1>Studenten import</h1>
<div class="block">
    <div class="block-head">Informatie</div>
    <div class="block-content">
        <table class="table table-striped">
            <tr>
                <th width="15%">Locatie</th>
                <td width="35%"><?= $school_location['name'] ?></td>
                <th width="15%">Klas</th>
                <td width="35%">
                    </td>
            </tr> 
        </table>
    </div>
</div>

<div class="block">
    <div class="block-head">Data
        <button id="setDefaultHeading">Zet default kolom headers!!</button>
    </div>
    <div class="duplicate" id="duplicates-data-errors"></div>
    <div class="duplicate-in-database" id="duplicates-in-database-data-errors"></div>

    <div class="error" id="column-errors"></div>
    <div class="error" id="missing-data-errors"></div>
    <div class="block-content">


        <div >
            <div>
                <textarea rows="1" id="excelPasteBox" placeholder="Plak je excel data hier..."></textarea>
            </div>
            <hr class="showAfterProcess"/>
            <div id="output">
            </div>
            <hr class="showAfterProcess"/>
            <a href="#" id="exportJsonData" style="display:none" class="btn highlight inline-block showAfterProcess" >Studenten importeren</a>
            <textarea style="display:none !important" rows="20" id="jsonDataDump" placeholder="JSON Data will appear here..."></textarea>
        </div>
    </div>
</div>

<?= $this->element('import_users_style_and_script',['type'=>'students']) ?>
