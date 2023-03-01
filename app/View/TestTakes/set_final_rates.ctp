<div id="buttons">
    <a href="#" class="btn highlight mr2" onclick="TestTake.markRated('<?=$take_id?>');">
        <span class="fa fa-check mr5"></span>
        <?= __("Becijferd markeren")?>
    </a>

    <?php if($take['show_grades']) { ?>
        <a href="#" class="btn white mr2" onclick="TestTake.hideGrades('<?=$take_id?>');">
            <span class="fa fa-eye-slash mr5"></span>
            <?= __("Cijfers verbergen")?>
        </a>
    <? } else { ?>
        <a href="#" class="btn white mr2" onclick="TestTake.showGrades('<?=$take_id?>');">
            <span class="fa fa-eye mr5"></span>
            <?= __("Toon cijfers")?>
        </a>
    <? } ?>

    <?= $this->element('back-button'); ?>
</div>

<h1><?= __("Becijferen")?></h1>
<div class="block">
    <div class="block-head"><?= __("Toetsinformatie")?></div>
    <div class="block-content">
        <table class="table table-striped">
            <tr>
                <th width="12%"><?= __("Toets")?></th>
                <td width="21%"><?=$take['test']['name']?></td>
                <th width="12%"><?= __("Gepland")?></th>
                <td width="21%"><?=date('d-m-Y', strtotime($take['time_start']))?></td>
                <th width="12%"><?= __("Normering")?></th>
                <td width="21%">
                    <?
                    if(!empty($take['ppp'])) {
                        echo __("Goed per punt: ") . $take['ppp'];
                    }else if(!empty($take['epp'])) {
                        echo __("Fouten per punt: ") . $take['epp'];
                    }else if(!empty($take['wanted_average'])) {
                        echo __("Gemiddeld cijfer: ") . $take['wanted_average'];
                    }else if(!empty($take['pass_mark'])) {
                        echo __("Cesuur: ") . $take['pass_mark'] . ' - N-term: ' . $take['n_term'];
                    }else{
                        echo __("N-term: ") . $take['n_term'];
                    }
                    ?>
                </td>
            </tr>
            <tr>
                <th><?= __("Type")?></th>
                <td><?=$take['retake'] == 0 ? __("Geplande toets") : __("Inhaal toets")?></td>
                <th><?= __("Weging")?></th>
                <td><?=$take['weight']?></td>
                <th><?= __("Vakdocent")?></th>
                <td>
                    <?=$take['user']['name_first']?>
                    <?=$take['user']['name_suffix']?>
                    <?=$take['user']['name']?>
                </td>
            </tr>
            <tr>
                <th><?= __("Vak")?></th>
                <td>
                    <?=$take['test']['subject']['name']?>
                </td>
                <th><?= __("Klas(sen)")?></th>
                <td colspan="3">
                    <?
                    foreach($take['school_classes'] as $class) {
                        echo $class['name'] . '<Br />';
                    }
                    ?>
                </td>
            </tr>
        </table>
    </div>
</div>


<div class="block">
    <div class="block-head"><?= __("Becijferen")?></div>
    <div class="block-content">
        <table class="table table-striped">
            <tr>
                <th width="200"><?= __("Student")?></th>
                <th><?= __("Notities")?></th>
                <? if(isset($retake_participants)) { ?>
                    <th width="150"><?= __("Vorige beoordeling")?></th>
                <? } ?>
                <th width="150"><?= __("Beoordeling")?></th>
                <th width="200"><?= __("Aangepaste beoordeling")?></th>
                <th width="50"></th>
            </tr>
            <?
            foreach($participants as $participant) {
                ?>
                <tr>
                    <td>
                        <?=$participant['user']['name_first']?>
                        <?=$participant['user']['name_suffix']?>
                        <?=$participant['user']['name']?>
                    </td>
                    <td>
                        <?=empty($participant['invigilator_note']) ? 'n.v.t.' : nl2br($participant['invigilator_note'])?>
                    </td>
                    <? if(isset($retake_participants)) { ?>
                        <td>
                            <?
                            foreach($retake_participants as $retake_participant) {
                                if($retake_participant['user_id'] == $participant['user_id']) {
                                    echo $retake_participant['rating'];
                                }
                            }
                            ?>
                        </td>
                    <? } ?>
                    <td>
                        <?=empty($participant['rating']) ? '-' : $participant['rating']?>
                    </td>
                    <td>
                        <div id="final_rating_<?=$participant['id']?>"></div>
                        <script>
                            $(function() {
                                $("#final_rating_<?=$participant['id']?>").slider({
                                    value:<?=empty($participant['rating']) ? 0 : $participant['rating']?>,
                                    min: 1,
                                    max: 10.1,
                                    step: 0.1,
                                    slide: function (event, ui) {
                                        $('#final_rating_label_<?=$participant['id']?>').html(ui.value);
                                    },
                                    stop: function (event, ui) {
                                        TestTake.setFinalRate('<?=$take_id?>', '<?=getUUID($participant, 'get');?>', ui.value);
                                    }
                                });
                            });
                        </script>
                    </td>
                    <td>
                        <div id="final_rating_label_<?=$participant['id']?>"><?=empty($participant['rating']) ? 0 : $participant['rating']?></div>
                    </td>
                </tr>
                <?
            }
            ?>
        </table>
    </div>
</div>

