<div id="buttons">
    <a href="#" class="btn white" onclick="Popup.load('/sections/edit/<?=$section['id']?>', 400);">
        <span class="fa fa-edit mr5"></span>
        Wijzigen
    </a>
    <a href="#" class="btn white" onclick="Organisation.delete(<?=$section['id']?>);">
        <span class="fa fa-remove mr5"></span>
        Delete
    </a>
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
                                    Delete
                                </a>
                            </div>
                        </td>
                    </tr>
                    <?
                }
                ?>
            </table>
            <br />
            <center>
                <a href="#" class="btn highlight inline-block" onclick="Popup.load('/sections/add_subject/<?=$section['id']?>', 400);">
                    <span class="icon icon-plus"></span>
                    Nieuw vak
                </a>
            </center>
        </table>
    </div>
</div>