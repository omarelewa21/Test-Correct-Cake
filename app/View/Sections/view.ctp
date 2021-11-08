<div id="buttons">
    <?php if((bool) $section['demo'] !== true){?>
    <a href="#" class="btn white" onclick="Popup.load('/sections/edit/<?=getUUID($section, 'get');?>', 400);">
        <span class="fa fa-edit mr5"></span>
        <?= __("Wijzigen")?>
    </a>
    <a href="#" class="btn white" onclick="Section.delete(<?=getUUID($section, 'getQuoted');?>, true);">
        <span class="fa fa-remove mr5"></span>
        <?= __("Verwijderen")?>
    </a>
    <?php } ?>
    <a href="#" class="btn white mr2" onclick="Navigation.back();">
        <span class="fa fa-backward mr5"></span>
        <?= __("Terug")?>
    </a>
</div>

<h1><?=$section['name']?></h1>


<div class="block">
    <div class="block-head"><?= __("Vakken")?></div>
    <div class="block-content">
        <table class="table table-striped">
            <table class="table table-striped">
                <tr>
                    <th><?= __("Vak")?></th>
                    <th><?= __("Categorie")?></th>
                    <th></th>
                </tr>
                <?
                foreach($section['subjects'] as $subject) {
                    ?>
                    <tr>
                        <td><?=$subject['name']?></td>
                        <td><?=$subject['base_subject']['name']?></td>
                        <td class="nopadding">
                            <?php if((bool) $section['demo'] !== true){?>
                            <a href="#" class="btn white pull-right dropblock-owner dropblock-left" id="SchoolYear_<?=getUUID($subject, 'get');?>">
                                <span class="fa fa-list-ul"></span>
                            </a>

                            <div class="dropblock blur-close" for="SchoolYear_<?=getUUID($subject, 'get');?>">
                                <a href="#" class="btn highlight white" onclick="Popup.load('/sections/edit_subject/<?=getUUID($subject, 'get');?>', 400);">
                                    <span class="fa fa-edit mr5"></span>
                                    <?= __("Wijzigen")?>
                                </a>
                                <a href="#" class="btn highlight white" onclick="Subject.delete(<?=getUUID($subject, 'getQuoted');?>);">
                                    <span class="fa fa-remove mr5"></span>
                                    <?= __("Verwijderen")?>
                                </a>
                            </div>
                            <?php } ?>
                        </td>
                    </tr>
                    <?
                }
                ?>
            </table>
            <br />
            <center>
                <?php if((bool) $section['demo'] !== true){?>
                <a href="#" class="btn highlight inline-block" onclick="Popup.load('/sections/add_subject/<?=getUUID($section, 'get');?>', 400);">
                    <span class="icon icon-plus"></span>
                    <?= __("Nieuw vak")?>
                </a>
                <?php } ?>
            </center>
        </table>
    </div>

</div>

<?php foreach($section['subjects'] as $subject) { ?>
<div class="block ">
    <div class="block-head"><?= __("Docenten gekoppeld")?> <?=$subject['name']?> </div>
    <div class="block-content">
        <table class="table table-striped" id="usersTable">
            <thead>
            <tr>
                <th><?= __("Voornaam")?></th>
                <th><?= __("Tussenvoegsel")?></th>
                <th><?= __("Achternaam")?></th>
            </tr>
            </thead>
            <tbody>
            <?php foreach($subject['teachers'] as $teacher) { ?>

            <tr>
                <td> <?= $teacher['user']['name_first'] ?></td>
                <td> <?= $teacher['user']['name_suffix'] ?></td>
                <td> <?= $teacher['user']['name'] ?></td>
            </tr>
        <?php } ?>


            </tbody>
        </table>


    </div>
    <div class="block-footer"></div>
</div>
<?php } ?>

<div class="block ">
    <div class="block-head"><?= __("Sectie gedeeld met")?></div>
    <div class="block-content">
        <table class="table table-striped" id="schoolLocationTable">
            <thead>
            <tr>
                <th><?= __("School locatie naam")?></th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            <?php foreach($sharedSchoolLocations as $schoolLocation) { ?>

            <tr>
                <td> <?= $schoolLocation['name'] ?></td>
                <td style="text-align:right;">
                    <a href="#" class="danger" onclick="SharedSectionLocation.delete(<?=getUUID($section, 'getQuoted');?>,<?=getUUID($schoolLocation, 'getQuoted');?>);">
                        <span class="fa fa-remove mr5"></span>

                    </a>
                </td>
            </tr>
            <?php } ?>


            </tbody>
        </table>
        <br/>
        <center>
        <a href="#" class="btn highlight inline-block" onclick="Popup.load('/sections/add_school_location/<?=getUUID($section, 'get');?>', 600);">
            <span class="icon icon-plus"></span>
            <?= __("Nieuwe schoollocatie toevoegen")?>
        </a>
        </center>

    </div>
    <div class="block-footer">
        <br />
    </div>
</div>

<script>
    var SharedSectionLocation = {
        delete : function(sectionId,schoolLocationId) {

            Popup.message({
                btnOk: '<?= __("Ja")?>',
                btnCancel: '<?= __("Annuleer")?>',
                title: '<?= __("Weet u het zeker?")?>',
                message: '<?= __("Weet u zeker dat u deze school locatie wilt ontkoppelen?")?>'
            }, function() {
                $.ajax({
                    url: '/sections/delete_shared_section_school_location/' + sectionId+'/'+schoolLocationId,
                    type: 'DELETE',
                    success: function(response) {
                        Notify.notify('<?= __("De school locatie is ontkoppeld")?>', 'info');
                        Navigation.refresh();
                    }
                });
            });
        }
    };
</script>