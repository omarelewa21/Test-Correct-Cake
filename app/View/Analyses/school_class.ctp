<div id="buttons">
    <a href="#" class="btn white mr2" onclick="Navigation.back();">
        <span class="fa fa-backward mr5"></span>
        Terug
    </a>
</div>

<h1>Analyse van <?=$class['name']?></h1>

<?
$teacher = false;
foreach(AuthComponent::user()['roles'] as $role) {
    if($role['name'] == 'Teacher') {
        $teacher = true;
    }
}
?>

<div class="block" style="float: left; width: 49.5%">
    <div class="block-head">Afgenomen toetsen</div>
    <div class="block-content" style="height: 400px; overflow: auto;">
        <table class="table">
            <tr>
                <th>Toets</th>
                <th width="40">Vak</th>
                <th width="90">Afname</th>
                <th width="60">Weging</th>
                <? if($teacher){ ?>
                    <th width="30">&nbsp;</th>
                <? } ?>
            </tr>
            <?
            foreach($test_takes as $test_take) {
                ?>
                <tr>
                    <td><?=$test_take['test']['name']?> [<?=$test_take['test']['abbreviation']?>]</td>
                    <td><?=$test_take['test']['subject']['abbreviation']?></td>
                    <td><?=date('d-m-Y', strtotime($test_take['time_start']))?></td>
                    <td><?=$test_take['weight']?></td>
                    <? if($teacher){ ?>
                        <td class="nopadding" width="30">
                            <a href="#" class="btn white pull-right" onclick="Navigation.load('/test_takes/view/<?=getUUID($test_take, 'get');?>');">
                                <span class="fa fa-folder-open-o"></span>
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

<div class="block" style="float: right; width: 49.5%">
    <div class="block-head">Cijferlijst</div>
    <div class="block-content" style="height: 430px; overflow: auto; padding:10px;">
        <table class="table">
            <tr>
                <th>Student</th>
                <?
                foreach($subjects_stats as $subject) {
                    echo '<th>' . $subject['abbreviation'] . '</th>';
                }
                ?>
                <th width="30"></th>
            </tr>
            <?
            foreach($class['student_users'] as $student) {
                ?>
                <tr>
                    <td>
                        <?=$student['name_first'] ?>
                        <?=$student['name_suffix'] ?>
                        <?=$student['name'] ?>
                    </td>
                    <?
                    foreach($subjects_stats as $subject) {

                        $content = '-';

                        foreach ($subject['average'] as $rating) {
                            if($rating['user_id'] == $student['id']) {
                                $content = round($rating['rating'], 1);
                                break;
                            }
                        }

                        echo '<td>' . $content . '</td>';

                    }
                    ?>
                    <td>
                        <a href="#" class="btn white" onclick="Navigation.load('/analyses/student/<?=getUUID($student, 'get');?>');">
                            <span class="fa fa-folder-open-o"></span>
                        </a>
                    </td>
                </tr>
                <?
            }
            ?>
        </table>
    </div>
</div>

<br clear="all" />

<?
$i = 0;
foreach($subjects_stats as $subject) {

    $i ++;

    if(empty($subject['average'])) {
        continue;
    }

    $ratings = [];
    foreach($subject['average'] as $rating) {
        $ratings[] = $rating['rating'];
    }

    sort($ratings);

    $lower = array_slice($ratings, 0, round(count($ratings) / 2));
    $upper = array_slice($ratings, count($lower));

    ?>
    <div class="block">
        <div class="block-head"><?=$subject['name']?></div>
        <div class="block-content">
            <div style="float: left; width:200px; height: 250px; margin: 10px;" id="boxplot_<?=$i?>"></div>

            <script type="text/javascript">
                $(function () {
                    $('#boxplot_<?=$i?>').highcharts({

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
                                text: '<?=$class['name']?>'
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

            <?
            foreach($parallel_classes as $parallel_class) {
                foreach($parallel_class['subjects_stats'] as $subjects_stats) {

                    if($subjects_stats['id'] == $subject['id'] && !empty($subjects_stats['average'])) {

                        $teachers = [];
                        foreach($subjects_stats['teacher'] as $teacher) {

                            $teacher_name = substr(strtoupper($teacher['name_first']), 0, 1) . '. ';
                            $teacher_name .= !empty($teacher['name_suffix']) . ' ' ? $teacher['name_suffix'] : '';
                            $teacher_name .= $teacher['name'];

                            $teachers[] = $teacher_name;
                        }

                        $i++;

                        $ratings = [];
                        foreach($subjects_stats['average'] as $rating) {
                            $ratings[] = $rating['rating'];
                        }

                        sort($ratings);

                        $lower = array_slice($ratings, 0, round(count($ratings) / 2));
                        $upper = array_slice($ratings, count($lower));

                        ?>
                        <div style="float: left; width:200px; height: 250px; margin: 10px;" id="boxplot_<?=$i?>"></div>

                        <script type="text/javascript">
                            $(function () {
                                $('#boxplot_<?=$i?>').highcharts({

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
                                            text: '<?=$parallel_class['name']?> - <?=implode(' - ', $teachers)?>'
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
                        <?
                    }
                }
            }
            ?>

        </div>
    </div>
    <?
}
?>