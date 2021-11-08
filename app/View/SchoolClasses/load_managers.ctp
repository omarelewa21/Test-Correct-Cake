<table class="table table-striped">
    <tr>
<th><?= __("Naam")?></th>
<th><?= __("Emailadres")?></th>
<th></th>
</tr>
<?
foreach($managers as $manager) {
    ?>
    <tr>
        <td>
            <?=$manager['name_first']?>
            <?=$manager['name_suffix']?>
            <?=$manager['name']?>
        </td>
        <td>
            <?=$manager['username']?>
        </td>
        <td class="nopadding">
            <?php if((bool) $class['demo'] !== true){?>
            <a href="#" class="btn white pull-right" onclick="Popup.load('/messages/send/<?=getUUID($manager, 'get');?>', 500);">
                <span class="fa fa-edit mr5"></span>
            </a>
            <a href="#" class="btn white pull-right" onclick="SchoolClass.removeManager('<?=$class_id?>', '<?=getUUID($manager, 'get');?>');">
                <span class="fa fa-remove"></span>
            </a>
            <a href="#" class="btn white pull-right" onclick="Popup.load('/users/edit/<?=getUUID($manager, 'get');?>', 400);">
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
    <a href="#" class="btn highlight inline-block" onclick="Popup.load('/users/add/management/<?=$class_id?>', 400);">
    <?= __("Nieuwe toevoegen")?>
    </a>

    <a href="#" class="btn highlight inline-block" onclick="Popup.load('/school_classes/add_management/<?=$class_id?>', 400);">
    <?= __("Bestaande koppelen")?>
    </a>
    <?php } ?>
</center>