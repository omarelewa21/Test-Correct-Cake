
<div class="block">
    <div class="block-head">Informatie</div>
    <div class="block-content">
        <table class="table table-striped">
            <tr>
                <th width="160">Vraag-items</th>
                <td><?=$teacher['count_questions']?></td>
                <th width="160">Toets-items</th>
                <td><?=$teacher['count_tests']?></td>
            </tr>
            <tr>
                <th width="160">Afgenomen toetsen</th>
                <td><?=$teacher['count_tests_taken']?></td>
                <th width="160">Besproken toetsen</th>
                <td><?=$teacher['count_tests_discussed']?></td>
            </tr>
        </table>
    </div>
</div>

<?
if ($is_temp_teacher) {
?>
    <script>Notify.notify("Je kunt nog geen analyses bekijken omdat je in een tijdelijke school zit. Zodra we je verplaatst hebben naar je school kun je analyses wel bekijken. We sturen je een bericht zodra we je gekoppeld hebben aan je school.", "info", 15000);</script>
<?}?>

<div class="block">
    <div class="block-head">Vergelijking met collega's</div>
    <div class="block-content" id="teacherGraph">
        <?
        if(!isset($teacher['compared_teachers']) || empty($teacher['compared_teachers']) || $is_temp_teacher) {
            ?>
            <center>Geen rapportage</center>
            <?
        }else{
            ?>
            <script type="text/javascript">
                $(function () {
                    $('#teacherGraph').highcharts({
                        chart: {
                            type: 'column'
                        },
                        title: {
                            text: ''
                        },
                        xAxis: {
                            categories: [
                                <?
                                foreach($teacher['compared_teachers'] as $compared_teacher) {
                                    $name = substr($compared_teacher['name_first'], 0, 1) . '. ';
                                    $name .= empty($compared_teacher['name_suffix']) ? '' : $compared_teacher['name_suffix'] . ' ';
                                    $name .= $compared_teacher['name'];

                                    echo "'" . $name . "',";
                                }
                                ?>
                            ],
                            crosshair: true
                        },
                        yAxis: {
                            min: 0,
                            max : 1
                        },
                        tooltip: {
                            headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
                            pointFormat: '<tr><td style="padding:0">{series.name}: </td>' +
                            '<td style="padding:0"><b>{point.y:.1f}</b></td></tr>',
                            footerFormat: '</table>',
                            shared: true,
                            useHTML: true
                        },
                        plotOptions: {
                            column: {
                                pointPadding: 0.2,
                                borderWidth: 0
                            }
                        },
                        series: [{
                            name: 'Gemiddelde',
                            data: [
                                <?
                                foreach($teacher['compared_teachers'] as $compared_teacher) {
                                    echo $compared_teacher['p_value_global'] . ',';
                                }
                                ?>
                            ]

                        }, {
                            name: 'Vergelijkende docent',
                            data: [
                                <?
                                foreach($teacher['compared_teachers'] as $compared_teacher) {
                                    echo $compared_teacher['p_value_this'] . ',';
                                }
                                ?>
                            ]

                        }, {
                            name: '<?

                        $name = substr($teacher['name_first'], 0, 1) . '. ';
                        $name .= empty($teacher['name_suffix']) ? '' : $teacher['name_suffix'] . ' ';
                        $name .= $teacher['name'];

                        echo $name;

                         ?>',
                            data: [
                                <?
                                foreach($teacher['compared_teachers'] as $compared_teacher) {
                                    echo $compared_teacher['p_value_own'] . ',';
                                }
                                ?>
                            ]

                        }]
                    });
                });
            </script>
            <?
        }

        ?>
    </div>
</div>



<?

$i = 0;

if(isset($teacher['school_class_stats']) && !empty($teacher['school_class_stats'])) {
    foreach($teacher['school_class_stats'] as $class_stats) {
        foreach($class_stats['subject'] as $subject) {

            $found = false;

            foreach($subject['teacher'] as $subject_teacher) {
                if($subject_teacher['id'] == $user_id) {
                    $found = true;
                }
            }

            if(!$found) {
                continue;
            }

        ?>
            <div class="block">
                <div class="block-head"><?=$class_stats['name']?> - <?=$subject['name']?></div>
                <div class="block-content">
                    <?

                    $ratings = [];

                    foreach($subject['average'] as $rating) {
                        $ratings[] = round($rating['rating'], 1);
                    }

                    if(count($ratings) == 0 || $is_temp_teacher) {
                        echo 'Geen gegevens</div></div>';
                        continue;
                    }

                    sort($ratings);

                    $lower = array_slice($ratings, 0, round(count($ratings) / 2));
                    $upper = array_slice($ratings, count($lower));

                    foreach($subject['teacher'] as $subject_teacher) {

                        if($subject_teacher['id'] != $user_id) {
                            continue;
                        }

                        $i++
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
                                            text: '<?=$subject_teacher['name_first']?>'
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

                    foreach($teacher['school_class_stats'] as $school_class_stats) {

                        if($school_class_stats['education_level_year'] != $class_stats['education_level_year'] || $school_class_stats['education_level_id'] != $class_stats['education_level_id']) {
                            continue;
                        }

                        foreach($school_class_stats['subject'] as $class_subject) {
                            $ratings = [];

                            foreach($class_subject['average'] as $rating) {
                                $ratings[] = round($rating['rating'], 1);
                            }

                            if(count($ratings) == 0) {
                                continue;
                            }

                            if($class_subject['id'] != $subject['id']) {
                                continue;
                            }

                            if($class_subject['id'] != $subject['id']) {
                                continue;
                            }

                            if($class_subject['id'] != $subject['id']) {
                                continue;
                            }

                            sort($ratings);

                            $lower = array_slice($ratings, 0, round(count($ratings) / 2));
                            $upper = array_slice($ratings, count($lower));

                            foreach($class_subject['teacher'] as $class_subject_teacher) {

                                if($class_subject_teacher['id'] == $user_id) {
                                    continue;
                                }

                                $i++
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
                                                    text: '<?=$class_subject_teacher['name_first']?> - <?=$school_class_stats['name']?>'
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
                    <br clear="all" />
                </div>
            </div>
            <?

        }
    }
}

?>