<table class="table table-striped">
    <tr>
<th>Naam</th>
<th>Emailadres</th>
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
            <a href="#" class="btn white pull-right" onclick="Popup.load('/messages/send/<?=$manager['id']?>', 500);">
                <span class="fa fa-edit mr5"></span>
            </a>
            <a href="#" class="btn white pull-right" onclick="SchoolClass.removeManager(<?=$class_id?>, <?=$manager['id']?>);">
                <span class="fa fa-remove"></span>
            </a>
            <a href="#" class="btn white pull-right" onclick="Popup.load('/users/edit/<?=$manager['id']?>', 400);">
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
    <a href="#" class="btn highlight inline-block" onclick="Popup.load('/users/add/management/<?=$class_id?>', 400);">
        Nieuwe toevoegen
    </a>

    <a href="#" class="btn highlight inline-block" onclick="Popup.load('/school_classes/add_management/<?=$class_id?>', 400);">
        Bestaande koppelen
    </a>
</center>