<table class="table table-striped">
    <tr>
<th>Naam</th>
<th>Emailadres</th>
<th></th>
</tr>
<?
foreach($students as $student) {
    ?>
    <tr>
        <td>
            <?=$student['name_first']?>
            <?=$student['name_suffix']?>
            <?=$student['name']?>
        </td>
        <td>
            <?=$student['username']?>
        </td>
        <td class="nopadding">
            <a href="#" class="btn white pull-right" onclick="SchoolClass.removeStudent(<?=$class_id?>, <?=$student['id']?>);">
                <span class="fa fa-remove"></span>
            </a>
            <a href="#" class="btn white pull-right dropblock-left" onclick="Popup.load('/users/edit/<?=$student['id']?>', 400);">
                <span class="fa fa-folder-open-o"></span>
            </a>
        </td>
    </tr>
<?
}
?>
</table>

<br />
<center>
    <a href="#" class="btn highlight inline-block" onclick="Navigation.load('/school_classes/import/<?=$location_id?>/<?=$class_id?>', 400);">
        Studenten importeren
    </a>
    <a href="#" class="btn highlight inline-block" onclick="Popup.load('/users/add/students/<?=$class_id?>', 400);">
        Student toevoegen
    </a>
    <a href="#" class="btn highlight inline-block" onclick="Popup.load('/school_classes/add_student/<?=$class_id?>', 400);">
        Bestaande koppelen
    </a>
</center>