<div id="buttons">
    <?php if((bool) $class['demo'] !== true){?>
    <a href="#" class="btn white" onclick="Navigation.load('/school_classes/import/<?=getUUID($class['school_location'], 'get')?>/<?=getUUID($class, 'get');?>');">
        <span class="fa fa-cloud-upload mr5"></span>
        <?= __("Studenten importeren")?>
    </a>
    <a href="#" class="btn white" onclick="Navigation.load('/analyses/school_class/<?=getUUID($class, 'get');?>');">
        <span class="fa fa-bar-chart mr5"></span>
        <?= __("Analyse")?>
    </a>
    <a href="#" class="btn white" onclick="Popup.load('/school_classes/edit/<?=getUUID($class, 'get');?>', 400);">
        <span class="fa fa-edit mr5"></span>
        <?= __("Wijzigen")?>
    </a>
    <a href="#" class="btn white" onclick="SchoolClass.delete('<?=getUUID($class, 'get');?>', 1);">
        <span class="fa fa-remove mr5"></span>
        <?= __("Verwijderen")?>
    </a>
    <?php } ?>
    <a href="#" class="btn white mr2" onclick="Navigation.back();">
        <span class="fa fa-backward mr5"></span>
        <?= __("Terug")?>
    </a>
</div>

<h1><?=$class['name']?></h1>

<div class="block">
    <div class="block-head"><?= __("Informatie")?></div>
    <div class="block-content">
        <table class="table table-striped">
            <tr>
                <th width="15%"><?= __("Naam")?></th>
                <td width="35%"><?=$class['name']?></td>
                <th width="15%"><?= __("Stamklas")?></th>
                <td width="35%"><?=$class['is_main_school_class'] == 1 ? __("Ja") : __("Nee")?></td>
            </tr>
            <tr>
                <th width="15%"><?= __("Locatie")?></th>
                <td width="35%"><?=$class['school_location']['name']?></td>
                <th width="15%"><?= __("Niveau")?></th>
                <td width="35%"><?=$class['education_level_year']?> <?=$class['education_level']['name']?></td>
            </tr>
        </table>
    </div>
</div>

<div class="block">
    <div class="block-head"><?= __("Gerelateerd")?></div>
    <div class="block-content">
        <div class="tabs">
            <a href="#" class="btn grey highlight" page="students" tabs="class_tabs">
            <?= __("Studenten")?>
            </a>
            <a href="#" class="btn grey" page="mentors" tabs="class_tabs">
            <?= __("Mentor(en)")?>
            </a>
            <a href="#" class="btn grey" page="managers" tabs="class_tabs">
            <?= __("Teamleiders / coördinators")?>
            </a>
            <a href="#" class="btn grey" page="teachers" tabs="class_tabs">
            <?= __("Docenten")?>
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
        $('.page[page="students"]').load('/school_classes/load_students/<?=getUUID($class, 'get');?>/<?=getUUID($class["school_location"], 'get');?>?' + new Date().getTime());
        $('.page[page="mentors"]').load('/school_classes/load_mentors/<?=getUUID($class, 'get');?>?' + new Date().getTime());
        $('.page[page="managers"]').load('/school_classes/load_managers/<?=getUUID($class, 'get');?>?' + new Date().getTime());
        $('.page[page="teachers"]').load('/school_classes/load_teachers/<?=getUUID($class, 'get');?>?' + new Date().getTime());
    });
</script>