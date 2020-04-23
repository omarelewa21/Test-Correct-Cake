<div id="buttons">
    <a href="#" class="btn white" onclick="Navigation.load('/school_classes/import/<?=$class['school_location_id']?>/<?=$class['id']?>');">
        <span class="fa fa-cloud-upload mr5"></span>
        Studenten importeren
    </a>
    <a href="#" class="btn white" onclick="Navigation.load('/analyses/school_class/<?=$class['id']?>');">
        <span class="fa fa-bar-chart mr5"></span>
        Analyse
    </a>
    <a href="#" class="btn white" onclick="Popup.load('/school_classes/edit/<?=$class['id']?>', 400);">
        <span class="fa fa-edit mr5"></span>
        Wijzigen
    </a>
    <a href="#" class="btn white" onclick="SchoolClass.delete(<?=$class['id']?>, 1);">
        <span class="fa fa-remove mr5"></span>
        Verwijderen
    </a>
    <a href="#" class="btn white mr2" onclick="Navigation.back();">
        <span class="fa fa-backward mr5"></span>
        Terug
    </a>
</div>

<h1><?=$class['name']?></h1>

<div class="block">
    <div class="block-head">Informatie</div>
    <div class="block-content">
        <table class="table table-striped">
            <tr>
                <th width="15%">Naam</th>
                <td width="35%"><?=$class['name']?></td>
                <th width="15%">Stamklas</th>
                <td width="35%"><?=$class['is_main_school_class'] == 1 ? 'Ja' : 'Nee'?></td>
            </tr>
            <tr>
                <th width="15%">Locatie</th>
                <td width="35%"><?=$class['school_location']['name']?></td>
                <th width="15%">Niveau</th>
                <td width="35%"><?=$class['education_level_year']?> <?=$class['education_level']['name']?></td>
            </tr>
        </table>
    </div>
</div>

<div class="block">
    <div class="block-head">Gerelateerd</div>
    <div class="block-content">
        <div class="tabs">
            <a href="#" class="btn grey highlight" page="students" tabs="class_tabs">
                Studenten
            </a>
            <a href="#" class="btn grey" page="mentors" tabs="class_tabs">
                Mentor(en)
            </a>
            <a href="#" class="btn grey" page="managers" tabs="class_tabs">
                Teamleiders / co&ouml;rdinators
            </a>
            <a href="#" class="btn grey" page="teachers" tabs="class_tabs">
                Docenten
            </a>

            <br clear="all" />
        </div>

        <div page="students" class="page active" tabs="class_tabs">

        </div>

        <div page="mentors" class="page" tabs="class_tabs">

        </div>

        <div page="managers" class="page" tabs="class_tabs">

        </div>

        <div page="teachers" class="page" tabs="class_tabs">

        </div>
    </div>
</div>


<script type="text/javascript">
    $(function() {
        $('.page[page="students"]').load('/school_classes/load_students/<?=$class['id']?>/<?=$class["school_location"]["id"]?>?' + new Date().getTime());
        $('.page[page="mentors"]').load('/school_classes/load_mentors/<?=$class['id']?>?' + new Date().getTime());
        $('.page[page="managers"]').load('/school_classes/load_managers/<?=$class['id']?>?' + new Date().getTime());
        $('.page[page="teachers"]').load('/school_classes/load_teachers/<?=$class['id']?>?' + new Date().getTime());
    });
</script>