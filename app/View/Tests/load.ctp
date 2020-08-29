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
        <td><?=$subjects[$test['subject_id']]?></td>
        <td>
            <?=$test['author']['name_first']?>
            <?=$test['author']['name_suffix']?>
            <?=$test['author']['name']?>
        </td>
        <td><?=$kinds[$test['test_kind_id']]?></td>
        <td><?=$test['education_level_year']?> <?=$education_levels[$test['education_level_id']]?></td>
        <td class="nopadding">
            <a href="#" class="btn white pull-right dropblock-owner dropblock-left" id="test_<?=$test['id']?>">
                <span class="fa fa-list-ul"></span>
            </a>
            <a href="#" class="btn white pull-right" onclick="Navigation.load('/tests/view/<?=$test['id']?>');">
                <span class="fa fa-folder-open-o"></span>
            </a>

            <div class="dropblock blur-close" for="test_<?=$test['id']?>">
                <? if($test['author']['id'] == $user_id && !AppHelper::isCitoTest($test)) {?>
                    <a href="#" class="btn highlight white" onclick="Navigation.load('/tests/view/<?=$test['id']?>');">
                        <span class="fa fa-edit mr5"></span>
                        Wijzigen
                    </a>
                <? } ?>
                <a href="#" class="btn highlight white" onclick="Popup.load('/test_takes/add/<?=$test['id']?>',1000);">
                    <span class="fa fa-calendar mr5"></span>
                    Inplannen
                </a>
                <?php if(!AppHelper::isCitoTest($test)){?>
                <a href="#" class="btn highlight white" onclick="Test.duplicate(<?=$test['id']?>);">
                    <span class="fa fa-random mr5"></span>
                    Dupliceren
                </a>
                <?php } ?>
                <? if($test['author']['id'] == $user_id && !AppHelper::isCitoTest($test)) {?>
                    <a href="#" class="btn highlight white" onclick="Test.delete(<?=$test['id']?>, false);">
                        <span class="fa fa-remove mr5"></span>
                        Verwijderen
                    </a>
                <? } ?>
            </div>
        </td>
    </tr>
    <?
}
?>
