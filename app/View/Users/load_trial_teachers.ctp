<?
foreach ($users as $user) {
    ?>
    <tr>
        <td><?= $user['name_first'] ?></td>
        <td><?= $user['name_suffix'] ?></td>
        <td><?= $user['name'] ?></td>
        <td><?= $user['username'] ?></td>
        <td><?= $user['school_location']['name'] ?></td>
        <td>
            <? if ($trialStatus[getUUID($user, 'get')] === 'not_started') { ?>
                <span class="tag" data-tag-warning><?= __('Niet begonnen') ?></span>
            <? } ?>
            <? if ($trialStatus[getUUID($user, 'get')] === 'expired') { ?>
                <span class="tag" data-tag-error><?= __('Verlopen') ?></span>
            <? } ?>
            <? if ($trialStatus[getUUID($user, 'get')] === 'active') { ?>
                <span class="tag" data-tag-success><?= __('Actief') ?></span>
            <? } ?>
        </td>
        <td>
            <?= $trialDaysLeft[getUUID($user, 'get')] ?? '-' ?>
        </td>
        <td class="nopadding">
            <? if ($trialStatus[getUUID($user, 'get')] !== 'not_started') { ?>
                <a href="#" class="btn white pull-right dropblock-owner dropblock-left"
                   id="trial_teacher_<?= getUUID($user, 'get'); ?>"
                   onclick="Popup.load('/users/change_trial_date/<?= getUUID($user, 'get'); ?>/<?= getUUID($user['trial_period'], 'get'); ?>', 600);">
                    <span class="fa fa-calendar"></span>
                </a>
            <? } ?>
        </td>
    </tr>
    <?
}
?>