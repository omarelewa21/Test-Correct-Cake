<?php

function getDiv($analyse){
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

$pValue = $analyse['p_value']*100;

$bgColor = '#ff3333';
$borderColor = '#ff0000';

if($pValue >= 55){
    $bgColor = '#ffff00';
    $borderColor = '#e6e600';
}
if($pValue >= 65){
    $bgColor = '#5cd65c';
    $borderColor = '#33cc33';
}


$factor = 0;
foreach($ratioAr as $ar){
    if($ar['start'] < $analyse['questions_per_attainment'] && $ar['end'] >= $analyse['questions_per_attainment']){
        $factor = $ar['multiplierBase'] + (($analyse['questions_per_attainment']-$ar['start'])/($ar['end']-$ar['start']));
        break;
    }
}

$width = round((300/5) * $factor); // total width 300 with 5 blocks

echo sprintf('<div title="gebasseerd op %d vragen voor dit leerdoel" style="overflow:hidden;border:1px solid %s;width:%dpx;height:20px;background-color:%s;text-align:center;font-size:10px;font-weight:bold;line-height:20px">P%d</div>',$analyse['questions_per_attainment'],$borderColor,$width,$bgColor,$pValue);
}

?>
<div class="block">
    <div class="block-head">Leerdoel analyse</div>
    <div class="block-content">
        <table class="table table-striped">
            <tr>
                <th colspan="9">Leerdoel</th>
            </tr>
            <tr>
                <td style="width:15px"></td>
                <td style="width:15px"></td>
                <td></td>
                <td style="width:60px;padding-left:0;padding-right:0"><span style="margin-left:-2px">0</span></td>
                <td style="width:60px;padding-left:0;padding-right:0"><span style="margin-left:-3px">5</span></td>
                <td style="width:60px;padding-left:0;padding-right:0"><span style="margin-left:-6px">10</span></td>
                <td style="width:60px;padding-left:0;padding-right:0"><span style="margin-left:-5px">20</span></td>
                <td style="width:60px;padding-left:0;padding-right:0"><span style="margin-left:-5px">40</span></td>
                <td style="width:60px;padding-left:0;padding-right:0"><span style="margin-left:-5px">80</span></td>
            </tr>
            <?
            foreach($analysis as $analyse) {
            ?>
            <tr style="cursor:pointer" title="Klik om de details te zien/ te verbergen" onClick="showHideTestTakeAttainmentParticipants(this,'<?=$test_take_id?>','<?=$analyse['uuid']?>');">
                <td><i class="fa fa-caret-right"></i></td>
                <td colspan="2">
                    <?= $analyse['code'] ?><?= $analyse['subcode']?>
                    <?= $analyse['description'] ?>
                </td>
                <td colspan="6">
                    <?php echo getDiv($analyse); ?>
                </td>
            </tr>
            <?
            }
            ?>
            <tr>
                <td style="width:15px"></td>
                <td style="width:15px"></td>
                <td></td>
                <td style="width:60px;padding-left:0;padding-right:0"><span style="margin-left:-2px">0</span></td>
                <td style="width:60px;padding-left:0;padding-right:0"><span style="margin-left:-3px">5</span></td>
                <td style="width:60px;padding-left:0;padding-right:0"><span style="margin-left:-6px">10</span></td>
                <td style="width:60px;padding-left:0;padding-right:0"><span style="margin-left:-5px">20</span></td>
                <td style="width:60px;padding-left:0;padding-right:0"><span style="margin-left:-5px">40</span></td>
                <td style="width:60px;padding-left:0;padding-right:0"><span style="margin-left:-5px">80</span></td>
            </tr>

        </table>
    </div>
</div>

<script>
    function showHideTestTakeAttainmentParticipants(row,testTakeId,attainmentId){
        var tr = jQuery("."+testTakeId+"-"+attainmentId);
        if(tr.length == 0){
            tr = jQuery('<tr><td colspan="9">Een moment de relevante data wordt opgezocht...</td></tr>');
            tr.insertAfter((row));
            TestTake.getTestTakeAttainmentAnalysisDetails(testTakeId,attainmentId,function(data){
                tr.replaceWith(data);
            });
        }
        else if(tr.first().is(':visible')){
            tr.hide();
            jQuery(row).find('i:first').removeClass('fa-caret-down').addClass('fa-caret-right');
        } else {
            tr.show();
            jQuery(row).find('i:first').removeClass('fa-caret-right').addClass('fa-caret-down');
        }
    }
</script>