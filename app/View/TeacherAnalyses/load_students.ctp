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
            <a href="#" class="btn white pull-right dropblock-left" onclick="User.goToLaravel('/teacher/analyses/<?=getUUID($student, 'get');?>/<?=$class_id?>');">
                <img class="tat-check" src="img/ico/analyses.svg" width="16px" height="16px" alt="">
            </a>
        </td>
    </tr>
<?
}
?>
</table>
