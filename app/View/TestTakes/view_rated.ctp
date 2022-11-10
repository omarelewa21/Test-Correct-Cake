<?
$isStudent = false;
foreach(AuthComponent::user()['roles'] as $role) {
    if($role['name'] == 'Student') {
        $isStudent = true;
    }
}
?>

<div id="buttons">
        <a href="#" class="btn white mr2" onclick="Navigation.load('/test_takes/add_retake/<?=$take_id?>');">
            <span class="fa fa-refresh mr5"></span>
            <?= __("Inhaal-toets plannen")?>
        </a>
        <?php if($take['is_rtti_test_take'] == 0): ?>
            <a href="#" class="btn white mr2" onclick="Navigation.load('/test_takes/normalization/<?=$take_id?>');">
                <span class="fa fa-hourglass-2 mr5"></span>
                <?= __("Normeren")?>
            </a>
        <?php endif;?>
        <a href="#" class="btn white mr2" onclick="Navigation.load('/test_takes/set_final_rates/<?=$take_id?>');">
            <span class="fa fa-hourglass-3 mr5"></span>
            <?= __("Becijferen")?>
        </a>
        <a href="#" class="btn white mr2" onclick="Popup.load('/test_takes/update_show_results/<?= $take_id ?>', 420);">
            <span class="fa fa-eye mr5"></span>
            <?= __("Openzetten")?>
        </a>
        <a href='#' onclick="Popup.load('/test_takes/rates_preview/<?=$take_id?>', 1000)" class="btn white mr2">
            <span class="fa fa-file mr5"></span>
            <?= __("Cijferlijst")?>
        </a>
    <? if(!$isStudent && $take['is_rtti_test_take'] == 0): ?>
        <a href='/test_takes/csv_export/<?=$take_id?>' target="_blank" class="btn white mr2">
            <span class="fa fa-download mr5"></span>
            <?= __("RTTI-Export")?>
        </a>
    <? endif; ?>

    <?php if($take['is_rtti_test_take'] == 1): ?>
        <a href="#" onclick="Popup.load('/test_takes/export_to_rtti/<?=$take_id?>', 1000)" title="Exporteren naar RTTI-Online" class="btn white mr2">Exporteren naar RTTI-Online</a>
    <?php endif; ?>

    <a href="#" class="btn white mr2"
        <? if (is_null($return_route)) { ?>
            onclick="Navigation.back();"
        <? } else { ?>
            onclick="User.goToLaravel('<?= $return_route ?>')"
        <? } ?>
    >
        <span class="fa fa-backward mr5"></span>
        <?= __("Terug")?>
    </a>
</div>

<h1><?= __("Resultaten toets")?></h1>
<?php if(isset($take['test_take_code']) && !empty($take['test_take_code'])) {?>
    <div class="test-take-code-show-wrapper">
        <div class="test-take-code-text-container">
            <h5><?= __('Student inlogtoetscode') ?></h5>
            <h1><?= $take['test_take_code']['prefix'] ?> <?= chunk_split($take['test_take_code']['code'], 3, ' ') ?></h1>
            <h2 title="<?= __('Kopieer toetslink') ?>" onclick="TestTake.copyDirectlink('<?=$take['directLink']?>');" style="margin-left:1.5rem; color:#041f74; cursor:pointer;">
                <span class="fa fa-clipboard mr5"></span>
            </h2>
        </div>
    </div>
<?php } ?>
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
                        echo __("Cesuur: ") . $take['pass_mark'] . __(" - N-term: ") . $take['n_term'];
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

<div class="block" style="width:calc(100% - 270px); float:left">
    <div class="block-head"><?= __("Studenten")?></div>
    <div class="block-content">
        <table class="table table-striped">
            <tr>
                <th><?= __("Student")?></th>
                <? if(!$isStudent) { ?>
                    <th width="120"><?= __("Score / Max")?></th>
                    <th width="120"><?= __("Veroorzaakte discrepanties")?></th>
                <? } ?>
                <th width="90"><?= __("Cijfer")?></th>
                <th width="60"><?= __("Notities")?></th>
                <? if($take['user_id'] == AuthComponent::user('id')) { ?>
                    <th width="130"></th>
                <? } ?>
            </tr>
            <?

            $scores = [];

            foreach($participants as $participant) {

                if(!empty($participant['rating'])) {
                    $scores[] = round($participant['rating'], 1);
                }
                ?>
                <tr>
                    <td>
                        <?=$participant['user']['name']?>,
                        <?=$participant['user']['name_first']?>
                        <?=$participant['user']['name_suffix']?>
                    </td>
                    <? if(!$isStudent) { ?>
                        <td>
                            <?=$participant['score']?> / <?=$participant['max_score']?>
                        </td>
                        <td><?=$participant['abnormalities']?></td>
                    <? } ?>
                    <td><?= !empty($participant['rating']) ? str_replace('.', ',', round($participant['rating'], 1)) : '-'?></td>
                    <td>
                        <?=empty($participant['invigilator_note']) ? __("Nee") : __("Ja")?>
                    </td>
                    <? if($take['user_id'] == AuthComponent::user('id')) { ?>
                        <td class="nopadding" width="100">
                            <a href="#" class="btn white pull-right" onclick="Navigation.load('/test_takes/view_results/<?=getUUID($take, 'get');?>/<?=getUUID($participant, 'get');?>');">
                                <span class="fa fa-folder-open-o"></span>
                            </a>
                            <a href="#" class="btn white pull-right" onclick="Popup.load('/test_takes/rated_info/<?=getUUID($take, 'get');?>/<?=getUUID($participant, 'get');?>', 500);">
                                <span class="fa fa-info-circle"></span>
                            </a>
                            <a href="#" class="btn white pull-right" onclick="Popup.load('/messages/send/<?=getUUID($participant['user'], 'get');?>', 500);">
                                <span class="fa fa-envelope-o"></span>
                            </a>
                        </td>
                    <? } ?>
                </tr>
                <?
            }
            ?>
        </table>
    </div>
</div>

<div style="width:250px; float:right">
    <div class="block">
        <div class="block-head"><?= __("Gemiddeld")?></div>
        <div class="block-content" style="text-align:center">
            <div style="font-size: 42px;">
                <?

                sort($scores);

                $som = 0;
                foreach($scores as $score) {
                    $som += $score;
                }

                echo str_replace('.', ',', round($som / count($scores), 1));
                ?>
            </div>
            <div style=" margin-top: 15px;">
            <?= __("Hoogste:")?> <?=str_replace('.', ',', $scores[count($scores) - 1])?> - <?= __("Laagste:")?> <?=str_replace('.', ',', $scores[0])?>
            </div>
        </div>
    </div>
    <? if(count($scores) > 5) { ?>
        <div class="block">
            <div class="block-head"><?= __("Boxplot")?></div>
            <div class="block-content">
                <?
                $lower = array_slice($scores, 0, round(count($scores) / 2));
                $upper = array_slice($scores, count($lower));

                ?>
                <div id="boxplot" style="height:300px;"></div>
            </div>
        </div>
    <? }else{
        ?>
        <div class="block">
            <div class="block-head"><?= __("Boxplot")?></div>
            <div class="block-content">
            <?= __("Niet voldoende gegevens")?>
            </div>
        </div>
        <?
    } ?>
</div>

<?php
if($isTeacher && $analysis && count($analysis)){
 echo $this->element("test_take_attainment_analysis",['analysis' => $analysis, 'test_take_id' => getUUID($take,'get'),'extra_style' => 'width:calc(100% - 270px); float:left']);
}
?>

<br clear="all" />
<? if(count($scores) > 5) { ?>
    <script type="text/javascript">
        clearTimeout(window.loadParticipants);
        TestTake.enterWaitingRoomPresenceChannel('<?=Configure::read('pusher-key')?>', '<?= $take_id ?>');
        TestTake.loadParticipants('<?=$take_id?>');
        $(function () {
            $('#boxplot').highcharts({

                chart: {
                    type: 'boxplot'
                },

                title: {
                    text: ''
                },

                legend: {
                    enabled: false
                },

                xAxis: {
                    title: {
                        text: ''
                    }
                },

                yAxis: {
                    title: {
                        text: ''
                    },
                    plotLines: [{
                        value: 5.5,
                        color: 'green',
                        width: 1
                    }],
                    min : 1,
                    max : 10
                },

                credits : false,

                series: [{
                    data: [
                        [
                            <?=$lower[0]?>,
                            <?=$lower[count($lower) / 2]?>,
                            <?=$lower[count($lower) - 1]?>,
                            <?=$upper[count($upper) / 2]?>,
                            <?=$upper[count($upper) - 1]?>
                        ]
                    ]
                }]

            });
        });


    </script>
<?php } ?>
