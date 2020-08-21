<div id="buttons">
    <a href="#" class="btn white mr2" onclick="Popup.load('/file_management/upload_class', 800);">
        <span class="fa fa-plus mr5"></span>
        Nieuwe Klas
    </a>
    <a href="#" class="btn white mr2" onclick="Navigation.load('/file_management/classuploads');">
        <span class="fa fa-list-ul mr5"></span>
        Aangeboden bestanden
    </a>
</div>


<h1>Mijn klassen</h1>

<div class="block autoheight">
    <div class="block-head">Klassen</div>
    <div class="block-content" id="classesContainer">
        <table class="table table-striped" id="classesTable">
            <thead>
            <tr>
                <th>Naam</th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            <?
        foreach($classes as $class) {
            ?>
                    <tr>
                        <td><?=$class['name']?></td>
                        <td class="nopadding">
                            <a href="#" class="btn white pull-right dropblock-left" onclick="Navigation.load('/teacher_classes/view/<?=$class['id']?>');">
                                <span class="fa fa-folder-open-o"></span>
                            </a>

                        </td>
                    </tr>
                    <?
        }
        ?>


            </tbody>
        </table>

    </div>
    <div class="block-footer"></div>
</div>