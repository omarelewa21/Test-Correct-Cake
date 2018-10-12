<?
foreach($tests as $test) {
    if($test['id'] != $test_id) {
        ?>
        <tr>
            <td><?= $test['abbreviation'] ?></td>
            <td><?= $test['name'] ?></td>
            <td><?= $subjects[$test['subject_id']] ?></td>
            <td class="nopadding">
                <a href="#" class="btn white pull-right"
                   onclick="Questions.loadExistionQuestionsList(<?= $test['id'] ?>);">
                    <span class="fa fa-folder-open-o"></span>
                </a>
            </td>
        </tr>
    <?
    }
}
?>