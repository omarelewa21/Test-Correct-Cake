<center>
    <?php if((bool) $class['demo'] !== true){?>
    <a href="#" class="btn highlight inline-block" onclick="Popup.load('/school_classes/add_teacher/<?=getUUID($class, 'get');?>', 400);">
    <?= __("Docent toevoegen")?>
    </a>
    <?php } ?>
</center>
<br />
<table class="table table-striped">
    <tr>
        <th><?= __("Docent")?></th>
        <th><?= __("Vak")?></th>
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
                <?php if((bool) $class['demo'] !== true){?>
                <a href="#" class="btn white pull-right dropblock-left" onclick="SchoolClass.removeTeacher('<?=getUUID($teacher, 'get');?>');">
                    <span class="fa fa-remove"></span>
                </a>
                <?php } ?>
            </td>
        </tr>
    <?
    }
    ?>
</table>
