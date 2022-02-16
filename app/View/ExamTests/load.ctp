<?
$user_id = AuthComponent::user()['id'];

foreach($tests as $test) {
    ?>
    <tr>
      <?php if($test['is_open_source_content']): ?>
        <td>
            <i class="fa fa-free" style="background-image:url('/img/ico/free.png'); display:block; width:32px; height:32px">
            </i>
        </td>
      <?php else: ?>
        <td></td>
      <?php endif; ?>

        <td><?=$test['abbreviation']?></td>
        <td><?=$test['name']?></td>
        <td style="text-align: center"><?=$test['question_count']?></td>
        <td><?=$test['subject']['name']?></td>
        <td>
            <?=$test['author']['name_first']?>
            <?=$test['author']['name_suffix']?>
            <?=$test['author']['name']?>
        </td>
        <td><?=$kinds[$test['test_kind_id']]?></td>
        <td><?=$test['education_level_year']?> <?=$education_levels[$test['education_level_id']]?></td>
        <td class="nopadding">
            <a href="#" class="btn white pull-right dropblock-owner dropblock-left" id="test_<?=getUUID($test, 'get')?>">
                <span class="fa fa-list-ul"></span>
            </a>
            <a href="#" class="btn white pull-right" onclick="Navigation.load('/exam_tests/view/<?=getUUID($test, 'get')?>');">
                <span class="fa fa-folder-open-o"></span>
            </a>

            <div class="dropblock blur-close" for="test_<?=getUUID($test, 'get')?>">
                <a href="#" class="btn highlight white" onclick="Popup.load('/test_takes/add/<?=getUUID($test, 'get')?>',1000);">
                    <span class="fa fa-calendar mr5"></span>
                    <?= __("Inplannen")?>
                </a>
            </div>
        </td>
    </tr>
    <?
}
?>
