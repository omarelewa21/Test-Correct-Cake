<?php

function getDiv($participant){
    $ratioAr = [
        [
            'start' => 0,
            'end' => 5,
            'multiplierBase' => 0,
        ],
        [
            'start' => 5,
            'end' => 10,
            'multiplierBase' => 1,
        ],
        [
            'start' => 10,
            'end' => 20,
            'multiplierBase' => 2,
        ],
        [
            'start' => 20,
            'end' => 40,
            'multiplierBase' => 3,
        ],
        [
            'start' => 40,
            'end' => 80,
            'multiplierBase' => 4,
        ],
        [
            'start' => 80,
            'end' => 160,
            'multiplierBase' => 5,
        ],
    ];

    $pValue = $participant['p_value']*100;

    $bgColor = '#ff6666';
    $borderColor = '#ff0000';

    if($pValue >= 55){
        $bgColor = '#ffff33';
        $borderColor = '#e6e600';
    }
    if($pValue >= 65){
        $bgColor = '#85e085';
        $borderColor = '#33cc33';
    }


    $factor = 0;
    foreach($ratioAr as $ar){
        if($ar['start'] < $participant['questions_per_attainment'] && $ar['end'] >= $participant['questions_per_attainment']){
            $factor = $ar['multiplierBase'] + (($participant['questions_per_attainment']-$ar['start'])/($ar['end']-$ar['start']));
            break;
        }
    }

$width = round((300/5) * $factor); // total width 300 with 5 blocks

echo sprintf('<div title="gebasseerd op %d vragen voor dit leerdoel" style="overflow:hidden;border:1px solid %s;width:%dpx;height:20px;background-color:%s;text-align:center;font-size:10px;font-weight:bold;line-height:20px">P%d</div>',$participant['questions_per_attainment'],$borderColor,$width,$bgColor,$pValue);
}

foreach($participants as $participant) {
            ?>
    <tr class="<?=$test_take_id?>-<?=$attainment_id?>">
        <td></td>
        <td></td>
        <td>
            <?= $participant['name_first'] ?>
            <?= $participant['name_suffix'] ?>
            <?= $participant['name'] ?>
        </td>
        <td colspan="6">
            <?php echo getDiv($participant); ?>
        </td>
    </tr>
            <?php
}
