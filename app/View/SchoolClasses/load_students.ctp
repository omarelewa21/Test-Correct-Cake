<table class="table table-striped">
    <tr>
<th><?= __("Naam")?></th>
<th><?= __("Emailadres")?></th>
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
            <?php if((bool) $class['demo'] !== true){?>
            <a href="#" class="btn white pull-right" onclick="SchoolClass.removeStudent('<?=$class_id?>', '<?=getUUID($student, 'get');?>');">
                <span class="fa fa-remove"></span>
            </a>
            <a href="#" class="btn white pull-right dropblock-left" onclick="Popup.load('/users/edit/<?=getUUID($student, 'get');?>', 400);">
                <span class="fa fa-folder-open-o"></span>
            </a>
            <?php } ?>
        </td>
    </tr>
<?
}
?>
</table>

<br />
<center>
    <?php if((bool) $class['demo'] !== true){?>
    <a href="#" class="btn highlight inline-block" onclick="Navigation.load('/school_classes/import/<?=$location_id?>/<?=$class_id?>', 400);">
    <?= __("Studenten importeren")?>
    </a>
    <a href="#" class="btn highlight inline-block" onclick="Popup.load('/users/add/students/<?=$class_id?>', 400);">
    <?= __("Student toevoegen")?>
    </a>
    <a href="#" class="btn highlight inline-block" onclick="Popup.load('/school_classes/add_student/<?=$class_id?>', 400);">
    <?= __("Bestaande koppelen")?>
    </a>
    <?php } ?>
</center>