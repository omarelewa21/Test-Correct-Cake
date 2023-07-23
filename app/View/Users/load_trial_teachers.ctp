<?
foreach ($users as $user) {
    foreach ($user['trialSchoolLocations'] as $trialSchoolLocation) {
        if (empty($user['trial_periods'])) { ?>
            <tr>
                <td><?= $user['name_first'] ?></td>
                <td><?= $user['name_suffix'] ?></td>
                <td><?= $user['name'] ?></td>
                <td><?= $user['username'] ?></td>
                <td><?= $trialSchoolLocation['name'] ?></td>
                <td>
                    <span class="tag" data-tag-warning><?= __('Niet begonnen') ?></span>
                </td>
                <td>
                    <?= $trialDaysLeft[getUUID($user, 'get')] ?? '-' ?>
                </td>
                <td class="nopadding"></td>
            </tr>
        <? } else {
            $trialPeriodIndex = array_search($trialSchoolLocation['id'], $user['trial_periods']);
            $trialPeriodUuid = $user['trial_periods'][$trialPeriodIndex]['uuid'];
            $lookupKey = sprintf('%s-%s', getUUID($user, 'get'), getUUID($trialPeriodUuid, 'get'));
            ?>
            <tr>
                <td><?= $user['name_first'] ?></td>
                <td><?= $user['name_suffix'] ?></td>
                <td><?= $user['name'] ?></td>
                <td><?= $user['username'] ?></td>
                <td><?= $trialSchoolLocation['name'] ?></td>
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
                           onclick="Popup.load('/users/change_trial_date/<?= getUUID($user, 'get'); ?>/<?= $trialPeriodUuid ?>', 600);">
                            <span class="fa fa-folder-open-o"></span>
                        </a>
                    <? } ?>
                </td>
            </tr>
            <?php
        }
    }
} ?>