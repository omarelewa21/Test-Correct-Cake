<div id="buttons">
    <a href="#" class="btn white" onclick="Popup.load('/school_classes/add', 400);">
        <span class="fa fa-plus mr5"></span>
        Nieuwe Klas
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