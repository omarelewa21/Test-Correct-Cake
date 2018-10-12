<table class="table table-striped">
    <tr>
        <th>Docent</th>
        <th>Vak</th>
        <th></th>
    </tr>
    <?

    foreach($class['teacher'] as $teacher) {
        ?>
        <tr>
            <td>
                <?=$teacher['user']['name_first']?>
                <?=$teacher['user']['name_suffix']?>
                <?=$teacher['user']['name']?>
            </td>
            <td>
                <?=$teacher['subject']['name']?>
            </td>
            <td class="nopadding">
                <a href="#" class="btn white pull-right dropblock-left" onclick="SchoolClass.removeTeacher(<?=$teacher['id']?>);">
                    <span class="fa fa-remove"></span>
                </a>
            </td>
        </tr>
    <?
    }
    ?>
</table>

<br />
<center>
    <a href="#" class="btn highlight inline-block" onclick="Popup.load('/school_classes/add_teacher/<?=$class['id']?>', 400);">
        Docent toevoegen
    </a>
</center>