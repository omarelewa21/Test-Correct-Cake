<div id="buttons">
    <?php if($totalScore === 0) { ?>
    <a href="#" class="btn grey mr2">
        <span class="fa fa-check mr5"></span>
        <?= __("Normering opslaan")?>
    </a>
    <?php } else { ?>
    <a href="#" class="btn highlight mr2" onclick="TestTake.saveNormalization('<?=$take_id?>');">
        <span class="fa fa-check mr5"></span>
        <?= __("Normering opslaan")?>
    </a>
    <?php } ?>
    <a href="#" class="btn white mr2" onclick="Navigation.back();">
        <span class="fa fa-backward mr5"></span>
        <?= __("Terug")?>
    </a>
</div>

<h1><?= __("Normeren")?></h1>

<?=$this->Form->create('TestTake')?>

<?php

if($totalScore === 0){

?>
    <div class="block">
        <div class="block-head"><?= __("Normering")?></div>
        <div class="block-content">
            <table class="table table-striped" id="tableQuestions">
                <thead>
                    <tr>
                        <td class="danger">
                        <?= __("Er zijn geen punten om mee te rekenen. Daarom is het niet mogelijk een normering te kiezen.")?>
                        </td>
                    </tr>
                </thead>
            </table>
        </div>
    </div>

<?php } else { ?>

    <div class="block">
        <div class="block-head"><?= __("Normering")?></div>
        <div style="display: none"><input type="hidden" id="hiddenIndex" name="hiddenIndex" value="0"></div>
        <div class="block-content">
            <table class="table table-striped">
                <tr>
                    <td width="20"><input name="data[TestTake][type]" type="radio" value="1" checked onclick="TestTake.normalizationPreview('<?=$take_id?>');" /></td>
                    <th width="300">
                    <?= __("Goed per punt")?>
                    </th>
                    <td width="150"><?= __("Goed per punt")?></td>
                    <td colspan="4">
                        <input type="text" name="data[TestTake][value_1]" value="1" style="width:50px;" onkeyup="setNotmalizationType(1)" />
                    </td>
                </tr>
                <tr>
                    <td width="20"><input name="data[TestTake][type]" type="radio" value="4" onclick="TestTake.normalizationPreview('<?=$take_id?>');" /></td>
                    <th width="300">
                    <?= __("Fouten per punt")?>
                    </th>
                    <td width="150"><?= __("Fouten per punt")?></td>
                    <td colspan="4">
                        <input type="text" name="data[TestTake][value_4]" value="1" style="width:50px;" onkeyup="setNotmalizationType(4)"/>
                    </td>
                </tr>
                <tr>
                    <td><input name="data[TestTake][type]" type="radio" value="2" onclick="TestTake.normalizationPreview('<?=$take_id?>');" /></td>
                    <th>
                    <?= __("Normeren o.b.v. gemiddeld cijfer")?>
                    </th>
                    <td width="150"><?= __("Gemiddeld cijfer")?></td>
                    <td colspan="4">
                        <input type="text" name="data[TestTake][value_2]" value="7.5" style="width:50px;" onkeyup="setNotmalizationType(2)" />
                    </td>
                </tr>
                <tr>
                    <td><input name="data[TestTake][type]" type="radio" value="3" onclick="TestTake.normalizationPreview('<?=$take_id?>');" /></td>
                    <th>
                    <?= __("Normeren o.b.v. n-term")?>
                    </th>
                    <td width="60">
                    <?= __("N-term:")?>
                    </td>
                    <td colspan="4" width="100">
                        <input type="text" name="data[TestTake][value_3]" value="1" style="width:50px;" onkeyup="setNotmalizationType(3)" />
                    </td>
                </tr>
                <tr>
                    <td><input name="data[TestTake][type]" type="radio" value="5" onclick="TestTake.normalizationPreview('<?=$take_id?>');" /></td>
                    <th>
                    <?= __("Normeren o.b.v. cesuur")?>
                    </th>
                    <td width="60">
                    <?= __("N-term:")?>
                    </td>
                    <td width="100">
                        <input type="text" name="data[TestTake][value_5]" value="1" style="width:50px;" onkeyup="setNotmalizationType(5)" />
                    </td>
                    <td width="60">
                    <?= __("Cesuur:")?>
                    </td>
                    <td width="100">
                        <input type="text" name="data[TestTake][value_6]" value="50" style="width:50px;" onkeyup="setNotmalizationType(5)" />
                    </td>
                </tr>
            </table>
        </div>
    </div>

    <div class="block">
        <div class="block-head"><?= __("Voorbeeld")?></div>
        <div class="block-content" id="divPreview"></div>
    </div>

<?php } ?>

    <div class="block">
        <div class="block-head"><?= __("Vragen")?></div>
        <div class="block-content">
            <table class="table table-striped">
                <tr>
                    <th>#</th>
                    <th><?= __("Vragen")?></th>
                    <th><?= __("Beoordelingen")?></th>
                    <th><?= __("Gem. score")?></th>
                    <th><?= __("Max score")?></th>
                    <th><?= __("Overslaan")?></th>
                </tr>
                <?

                $groupQuestionUuid = '';
                $i = 0;
                foreach($questions as $question_id => $question) {
                if($question['type']!=='GroupQuestion') {
                    $i++;
                }
                    ?>
                        <?
                            if($question['type']=='GroupQuestion'){

                        ?>
                            <tr>
                                <td>

                                </td>
                                <td><?=substr(strip_tags($question['name']), 0, 100)?> - <?
                                        if($question['groupquestion_type']=='carousel'){
                                             echo('carrousel');
                                        }else{
                                            echo($question['groupquestion_type']);
                                        }

                                    ?>

                                </td>
                                <td>&nbsp;</td>
                                <td>
                                    &nbsp;
                                </td>
                                <td><?=$question['score']?></td>
                                <td>
                                    <? if($question['groupquestion_type']=='carousel'){ ?>
                                        <input name="data[Question][<?=$question_id?>]" value="1" type="checkbox" onchange="TestTake.handleGroupQuestionSkip(this,'<? echo($question['uuid'])?>','<?=$take_id?>');" />
                                    <? } ?>
                                </td>
                            </tr>
                        <? }elseif($question['is_subquestion']=='1'){ ?>
                            <tr>
                                <td><?=$i?></td>
                                <td style="padding-left:15px"><?=substr(strip_tags($question['question']), 0, 100)?></td>
                                <td><?=isset($question['ratings']) ? $question['ratings'] : 0?></td>
                                <td>
                                    <?
                                    if(isset($question['ratings']) && $question['ratings'] > 0) {
                                        echo round($question['total_score'] / $question['ratings']);
                                    }else{
                                        echo '-';
                                    }
                                    ?>
                                </td>
                                <td><?=$question['score']?></td>
                                <td>
                                    <input name="data[Question][<?=$groupQuestionChildArray[$question_id]?>]" value="1" type="checkbox"
                                    <? if(in_array($question_id,$carouselQuestionsChildArray)){ ?>
                                            class="child_<?echo($question['groupQuestionUuid'])?> groupquestion_child" disabled
                                    <? }else{ ?>
                                            onchange="TestTake.normalizationPreview('<?=$take_id?>');"
                                    <? } ?>
                                    />
                                </td>
                            </tr>
                        <? }else{ ?>
                            <tr>
                                <td><?=$i?></td>
                                <td><?=substr(strip_tags($question['question']), 0, 100)?></td>
                                <td><?=isset($question['ratings']) ? $question['ratings'] : 0?></td>
                                <td>
                                    <?
                                    if(isset($question['ratings']) && $question['ratings'] > 0) {
                                        echo round($question['total_score'] / $question['ratings']);
                                    }else{
                                        echo '-';
                                    }
                                    ?>
                                </td>
                                <td><?=$question['score']?></td>
                                <td>
                                    <input name="data[Question][<?=$question_id?>]" value="1" type="checkbox" onchange="TestTake.normalizationPreview('<?=$take_id?>');" />
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
    $('input').keyup(function() {
       clearTimeout(window.normalizeTimeout);
        window.normalizeTimeout = setTimeout(function() {
            TestTake.normalizationPreview('<?=$take_id?>');
        }, 1000);
    });

    function setNotmalizationType(type) {
        $('input:radio[name="data[TestTake][type]"][value="' + type + '"]').click();
    }
</script>

<?=$this->Form->end();?>
