<?
foreach ($users as $user) {
    if (!empty($user['trial_periods'])) {
        foreach ($user['trial_periods'] as $trialPeriod) {
            $lookupKey = sprintf('%s-%s', getUUID($user, 'get'), getUUID($trialPeriod, 'get'));
            ?>
            <tr>
                <td><?= $user['name_first'] ?></td>
                <td><?= $user['name_suffix'] ?></td>
                <td><?= $user['name'] ?></td>
                <td><?= $user['username'] ?></td>
                <td><?= $trialPeriod['school_location']['name'] ?></td>
                <td>
                    <? if ($trialStatus[$lookupKey] === 'not_started') { ?>
                        <span class="tag" data-tag-warning><?= __('Niet begonnen') ?></span>
                    <? } ?>
                    <? if ($trialStatus[$lookupKey] === 'expired') { ?>
                        <span class="tag" data-tag-error><?= __('Verlopen') ?></span>
                    <? } ?>
                    <? if ($trialStatus[$lookupKey] === 'active') { ?>
                        <span class="tag" data-tag-success><?= __('Actief') ?></span>
                    <? } ?>
                </td>
                <td>
                    <?= $trialDaysLeft[$lookupKey] ?? '-' ?>
                </td>
                <td class="nopadding">
                    <? if ($trialStatus[$lookupKey] !== 'not_started') { ?>
                        <a href="#" class="btn white pull-right dropblock-owner dropblock-left"
                           id="trial_teacher_<?= getUUID($user, 'get'); ?>"
                           onclick="Popup.load('/users/change_trial_date/<?= getUUID($user, 'get'); ?>/<?= getUUID($trialPeriod, 'get'); ?>', 600);">
                            <span class="fa fa-calendar"></span>
                        </a>
                    <? } ?>
                </td>
            </tr>
        <?php }
    } else {
        ?>
        <tr>
            <td><?= $user['name_first'] ?></td>
            <td><?= $user['name_suffix'] ?></td>
            <td><?= $user['name'] ?></td>
            <td><?= $user['username'] ?></td>
            <td><?= $user['school_location']['name'] ?></td>
            <td>
                <span class="tag" data-tag-warning><?= __('Niet begonnen') ?></span>
            </td>
            <td>
                <?= $trialDaysLeft[getUUID($user, 'get')] ?? '-' ?>
            </td>
            <td class="nopadding"></td>
        </tr>
    <?php }
} ?>