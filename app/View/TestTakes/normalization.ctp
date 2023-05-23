<div id="buttons">
    <?php if ($totalScore === 0) { ?>
        <a href="#" class="btn grey mr2">
            <span class="fa fa-check mr5"></span>
            <?= __("Normering opslaan") ?>
        </a>
    <?php } else { ?>
        <a href="#" class="btn highlight mr2" onclick="TestTake.saveNormalization('<?= $take_id ?>');">
            <span class="fa fa-check mr5"></span>
            <?= __("Normering opslaan") ?>
        </a>
    <?php } ?>

    <?= $this->element('back-button'); ?>
</div>

<h1><?= __("Normeren") ?></h1>

<?= $this->Form->create('TestTake') ?>

<?php if ($totalScore === 0) { ?>
    <div class="block">
        <div class="block-head"><?= __("Normering") ?></div>
        <div class="block-content">
            <table class="table table-striped" id="tableQuestions">
                <thead>
                <tr>
                    <td class="danger">
                        <?= __("Er zijn geen punten om mee te rekenen. Daarom is het niet mogelijk een normering te kiezen.") ?>
                    </td>
                </tr>
                </thead>
            </table>
        </div>
    </div>
<?php } else { ?>

    <div class="block">
        <div class="block-head"><?= __("Normering") ?></div>
        <div style="display: none"><input type="hidden" id="hiddenIndex" name="hiddenIndex" value="0"></div>
        <div class="block-content">
            <?php
            $radios = [
                [
                    'inputName'    => 'data[TestTake][value_1]',
                    'inputValue'   => $normalizationStandards[1] === $userSettings['grade_default_standard'] ? $userSettings['grade_standard_value'] : 1,
                    'radioValue'   => 1,
                    'radioChecked' => $normalizationStandards[1] === $userSettings['grade_default_standard'],
                    'title'        => __("Goed per punt"),
                    'subtitle'     => __("Goed per punt"),
                ],
                [
                    'inputName'    => 'data[TestTake][value_4]',
                    'inputValue'   => $normalizationStandards[4] === $userSettings['grade_default_standard'] ? $userSettings['grade_standard_value'] : 1,
                    'radioValue'   => 4,
                    'radioChecked' => $normalizationStandards[4] === $userSettings['grade_default_standard'],
                    'title'        => __("Fouten per punt"),
                    'subtitle'     => __("Fouten per punt"),
                ],
                [
                    'inputName'    => 'data[TestTake][value_2]',
                    'inputValue'   => $normalizationStandards[2] === $userSettings['grade_default_standard'] ? $userSettings['grade_standard_value'] : 7.5,
                    'radioValue'   => 2,
                    'radioChecked' => $normalizationStandards[2] === $userSettings['grade_default_standard'],
                    'title'        => __("Normeren o.b.v. gemiddeld cijfer"),
                    'subtitle'     => __("Gemiddeld cijfer"),
                ],
                [
                    'inputName'    => 'data[TestTake][value_3]',
                    'inputValue'   => $normalizationStandards[3] === $userSettings['grade_default_standard'] ? $userSettings['grade_standard_value'] : 1,
                    'radioValue'   => 3,
                    'radioChecked' => $normalizationStandards[3] === $userSettings['grade_default_standard'],
                    'title'        => __("Normeren o.b.v. n-term"),
                    'subtitle'     => __("N-term:"),
                ],
                [
                    'inputName'    => 'data[TestTake][value_5]',
                    'inputValue'   => $normalizationStandards[5] === $userSettings['grade_default_standard'] ? $userSettings['grade_standard_value'] : 1,
                    'cesuur'       => $normalizationStandards[5] === $userSettings['grade_default_standard'] ? $userSettings['grade_cesuur_percentage'] : 50,
                    'radioValue'   => 5,
                    'radioChecked' => $normalizationStandards[5] === $userSettings['grade_default_standard'],
                    'title'        => __("Normeren o.b.v. n-term"),
                    'subtitle'     => __("N-term:"),
                ],
            ];
            ?>
            <table class="table table-striped">
                <?php foreach ($radios as $radio) { ?>
                    <tr>
                        <td width="20">
                            <input name="data[TestTake][type]"
                                   type="radio"
                                   value="<?= $radio['radioValue'] ?>"
                                <?= $radio['radioChecked'] ? 'checked' : '' ?>
                                   onclick="TestTake.normalizationPreview('<?= $take_id ?>');"
                            />
                        </td>
                        <th width="300">
                            <?= $radio['title'] ?>
                        </th>
                        <td width="150"><?= $radio['subtitle'] ?></td>
                        <td <?php if (!isset($radio['cesuur'])) { ?> colspan="4" <?php } ?>>
                            <input type="text" name="<?= $radio['inputName'] ?>" value="<?= $radio['inputValue'] ?>"
                                   style="width:50px;"
                                   onkeyup="setNormalizationType(<?= $radio['radioValue'] ?>)"/>
                        </td>

                        <?php if (isset($radio['cesuur'])) { ?>
                            <td width="60">
                                <?= __("Cesuur:") ?>
                            </td>
                            <td width="100">
                                <input type="text" name="data[TestTake][value_6]" value="<?= $radio['cesuur'] ?>"
                                       style="width:50px;"
                                       onkeyup="setNormalizationType(5)"/> %
                            </td>
                        <?php } ?>
                    </tr>
                <?php } ?>
            </table>
        </div>
    </div>

    <div class="block">
        <div class="block-head"><?= __("Voorbeeld") ?></div>
        <div class="block-content" id="divPreview"></div>
    </div>

<?php } ?>

<div class="block">
    <div class="block-head"><?= __("Vragen") ?></div>
    <div class="block-content">
        <table class="table table-striped">
            <tr>
                <th>#</th>
                <th><?= __("Vragen") ?></th>
                <th><?= __("Beoordelingen") ?></th>
                <th><?= __("Gem. score") ?></th>
                <th><?= __("Max score") ?></th>
                <th><?= __("Overslaan") ?></th>
            </tr>
            <?

            $groupQuestionUuid = '';
            $i = 0;
            foreach ($questions as $question_id => $question) {
                if ($question['type'] !== 'GroupQuestion') {
                    $i++;
                }
                ?>
                <?
                if ($question['type'] == 'GroupQuestion') {

                    ?>
                    <tr>
                        <td>

                        </td>
                        <td><?= substr(strip_tags($question['name']), 0, 100) ?> - <?
                            if ($question['groupquestion_type'] == 'carousel') {
                                echo('carrousel');
                            } else {
                                echo($question['groupquestion_type']);
                            }

                            ?>

                        </td>
                        <td>&nbsp;</td>
                        <td>
                            &nbsp;
                        </td>
                        <td><?= $question['score'] ?></td>
                        <td>
                            <? if ($question['groupquestion_type'] == 'carousel') { ?>
                                <input name="data[Question][<?= $question_id ?>]" value="1" type="checkbox"
                                       onchange="TestTake.handleGroupQuestionSkip(this,'<? echo($question['uuid']) ?>','<?= $take_id ?>');"/>
                            <? } ?>
                        </td>
                    </tr>
                <? } elseif ($question['is_subquestion'] == '1') { ?>
                    <tr>
                        <td><?= $i ?></td>
                        <td style="padding-left:15px"><?= substr(strip_tags($question['question']), 0, 100) ?></td>
                        <td><?= isset($question['ratings']) ? $question['ratings'] : 0 ?></td>
                        <td>
                            <?
                            if (isset($question['ratings']) && $question['ratings'] > 0) {
                                echo round($question['total_score'] / $question['ratings']);
                            } else {
                                echo '-';
                            }
                            ?>
                        </td>
                        <td><?= $question['score'] ?></td>
                        <td>
                            <input name="data[Question][<?= $question_id ?>]" value="1" type="checkbox"
                                <? if (in_array($question_id, $carouselQuestionsChildArray)) { ?>
                                    class="child_<? echo($question['groupQuestionUuid']) ?> groupquestion_child" disabled
                                <? } else { ?>
                                    onchange="TestTake.normalizationPreview('<?= $take_id ?>');"
                                <? } ?>
                            />
                        </td>
                    </tr>
                <? } else { ?>
                    <tr>
                        <td><?= $i ?></td>
                        <td><?= substr(strip_tags($question['question']), 0, 100) ?></td>
                        <td><?= isset($question['ratings']) ? $question['ratings'] : 0 ?></td>
                        <td>
                            <?
                            if (isset($question['ratings']) && $question['ratings'] > 0) {
                                echo round($question['total_score'] / $question['ratings']);
                            } else {
                                echo '-';
                            }
                            ?>
                        </td>
                        <td><?= $question['score'] ?></td>
                        <td>
                            <input name="data[Question][<?= $question_id ?>]" value="1" type="checkbox"
                                   onchange="TestTake.normalizationPreview('<?= $take_id ?>');"/>
                        </td>
                    </tr>
                <? } ?>
                <?

            }
            ?>
        </table>
    </div>
</div>
<script type="text/javascript">
    TestTake.normalizationPreview('<?=$take_id?>', true);

    $('input').keyup(function () {
        clearTimeout(window.normalizeTimeout);
        window.normalizeTimeout = setTimeout(function () {
            TestTake.normalizationPreview('<?=$take_id?>', true);
        }, 1000);
    });

    function setNormalizationType(type) {
        $('input:radio[name="data[TestTake][type]"][value="' + type + '"]').click();
    }
</script>

<?= $this->Form->end(); ?>
