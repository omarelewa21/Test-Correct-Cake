<table class="table table-striped">
    <tr>
<th>Naam</th>
<th>Emailadres</th>
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
            <a href="#" class="btn white pull-right" onclick="Popup.load('/messages/send/<?=$mentor['id']?>', 500);">
                <span class="fa fa-edit mr5"></span>
            </a>
            <a href="#" class="btn white pull-right" onclick="SchoolClass.removeMentor(<?=$class_id?>, <?=$mentor['id']?>);">
                <span class="fa fa-remove"></span>
            </a>
            <a href="#" class="btn white pull-right" onclick="Popup.load('/users/edit/<?=$mentor['id']?>', 400);">
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
    <!-- <a href="#" class="btn highlight inline-block" onclick="Popup.load('/users/add/mentors', 400);">
        Nieuwe gebruiker
    </a> -->
    <a href="#" class="btn highlight inline-block" onclick="Popup.load('/school_classes/add_mentor/<?=$class_id?>', 400);">
        Bestaande koppelen
    </a>
</center>
