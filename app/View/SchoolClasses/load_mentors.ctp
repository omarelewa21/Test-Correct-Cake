<table class="table table-striped">
    <tr>
<th><?= __("Naam")?></th>
<th><?= __("Emailadres")?></th>
<th></th>
</tr>
<?
foreach($mentors as $mentor) {
    ?>
    <tr>
        <td>
            <?=$mentor['name_first']?>
            <?=$mentor['name_suffix']?>
            <?=$mentor['name']?>
        </td>
        <td>
            <?=$mentor['username']?>
        </td>
        <td class="nopadding">
            <?php if((bool) $class['demo'] !== true){?>
            <a href="#" class="btn white pull-right" onclick="Popup.load('/messages/send/<?=getUUID($mentor, 'get');?>', 500);">
                <span class="fa fa-edit mr5"></span>
            </a>
            <a href="#" class="btn white pull-right" onclick="SchoolClass.removeMentor('<?=$class_id?>', <?=getUUID($mentor, 'getQuoted');?>);">
                <span class="fa fa-remove"></span>
            </a>
            <a href="#" class="btn white pull-right" onclick="Popup.load('/users/edit/<?=getUUID($mentor, 'get');?>', 400);">
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
    <!-- <a href="#" class="btn highlight inline-block" onclick="Popup.load('/users/add/mentors', 400);">
        Nieuwe gebruiker
    </a> -->
    <?php if((bool) $class['demo'] !== true){?>
    <a href="#" class="btn highlight inline-block" onclick="Popup.load('/school_classes/add_mentor/<?=$class_id?>', 400);">
    <?= __("Bestaande koppelen")?>
    </a>
    <?php } ?>
</center>
