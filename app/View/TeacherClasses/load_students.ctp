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
            <a href="#" class="btn white pull-right dropblock-left" onclick="Popup.load('/users/change_password_for_user/<?=getUUID($student, 'get');?>/<?=$class_id?>', 400);">
                <span class="fa fa-key"></span>
            </a>
        </td>
    </tr>
<?
}
?>
</table>
