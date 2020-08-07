<div id="buttons">
    <?php if((bool) $section['demo'] !== true){?>
    <a href="#" class="btn white" onclick="Popup.load('/sections/edit/<?=$section['id']?>', 400);">
        <span class="fa fa-edit mr5"></span>
        Wijzigen
    </a>
    <a href="#" class="btn white" onclick="Section.delete(<?=$section['id']?>, true);">
        <span class="fa fa-remove mr5"></span>
        Verwijderen
    </a>
    <?php } ?>
    <a href="#" class="btn white mr2" onclick="Navigation.back();">
        <span class="fa fa-backward mr5"></span>
        Terug
    </a>
</div>

<h1><?=$section['name']?></h1>


<div class="block">
    <div class="block-head">Vakken</div>
    <div class="block-content">
        <table class="table table-striped">
            <table class="table table-striped">
                <tr>
                    <th>Vak</th>
                    <th>Categorie</th>
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
                            <a href="#" class="btn white pull-right dropblock-owner dropblock-left" id="SchoolYear_<?=$subject['id']?>">
                                <span class="fa fa-list-ul"></span>
                            </a>

                            <div class="dropblock blur-close" for="SchoolYear_<?=$subject['id']?>">
                                <a href="#" class="btn highlight white" onclick="Popup.load('/sections/edit_subject/<?=$subject['id']?>', 400);">
                                    <span class="fa fa-edit mr5"></span>
                                    Wijzigen
                                </a>
                                <a href="#" class="btn highlight white" onclick="Subject.delete(<?=$subject['id']?>);">
                                    <span class="fa fa-remove mr5"></span>
                                    Verwijderen
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
                <a href="#" class="btn highlight inline-block" onclick="Popup.load('/sections/add_subject/<?=$section['id']?>', 400);">
                    <span class="icon icon-plus"></span>
                    Nieuw vak
                </a>
                <?php } ?>
            </center>
        </table>
    </div>

</div>

<?php foreach($section['subjects'] as $subject) { ?>
<div class="block autoheight">
    <div class="block-head">Docenten gekoppeld <?=$subject['name']?> </div>
    <div class="block-content">
        <table class="table table-striped" id="usersTable">
            <thead>
            <tr>
                <th>Voornaam</th>
                <th>Tussenvoegsel</th>
                <th>Achternaam</th>
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