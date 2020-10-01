<?
foreach($tests as $test) {
    ?>
    <tr>
        <td><?=$test['abbreviation']?></td>
        <td><?=$test['name']?></td>
        <td><?=$subjects[$test['subject_id']]?></td>
        <td><?=$test['education_level_year']?> <?=$education_levels[$test['education_level_id']]?></td>
        <td>
            <?=$test['author']['name_first']?>
            <?=$test['author']['name_suffix']?>
            <?=$test['author']['name']?>
        </td>
        <td class="nopadding">
            <a href="#" class="btn white pull-right" onclick="Popup.load('/tests/preview_popup/<?=getUUID($test, 'get');?>', 1000);">
                <span class="fa fa-search"></span>
            </a>
    <? if ($test['has_duplicates']) { ?>
        &nbsp;
    <? } else { ?>
            <a href="#" class="btn white pull-right" onclick="TestTake.setSelectedTest('<?=getUUID($test, 'get');?>', '<?=$test['name']?>', <?=$test['test_kind_id']?>);">
                <span class="fa fa-plus"></span>
            </a>
        <? } ?>
        </td>
    </tr>
<?
}
?>