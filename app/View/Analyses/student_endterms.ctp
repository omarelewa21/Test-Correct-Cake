<?

//debug($attainments);

/*$attainments = [
    [
        'title' => 'Eindterm 1',
        'current_p_value' => .72,
        'current_p_value_count' => 8,
        'total_p_value' => .23,
        'total_p_value_count' => 10
    ],
    [
        'title' => 'Eindterm 2',
        'current_p_value' => .5,
        'current_p_value_count' => 15,
        'total_p_value' => .22,
        'total_p_value_count' => 7
    ],
    [
        'title' => 'Eindterm 3',
        'current_p_value' => .40,
        'current_p_value_count' => 1,
        'total_p_value' => .26,
        'total_p_value_count' => 2
    ],
    [
        'title' => 'Eindterm 4',
        'current_p_value' => .80,
        'current_p_value_count' => 4,
        'total_p_value' => .70,
        'total_p_value_count' => 6
    ],
    [
        'title' => 'Eindterm 5',
        'current_p_value' => .60,
        'current_p_value_count' => 1,
        'total_p_value' => .15,
        'total_p_value_count' => 5
    ]
];*/
?>

<script type="text/javascript">
    $(function () {
        $('#divAnalyseTerms').highcharts({

            chart: {
                type: 'bubble',
                plotBorderWidth: 1,
                zoomType: 'xy'
            },

            legend: {
                enabled: false
            },

            title: {
                text: ''
            },

            credits : false,

            xAxis: {
                gridLineWidth: 1,
                title: {
                    text: ''
                },
                min: 0,
                max: 100,
                plotLines: [{
                    color: 'black',
                    dashStyle: 'dot',
                    width: 2,
                    value: 80,
                    label: {
                        align: 'middle',
                        rotation: 0,
                        y: 15,
                        style: {
                            fontStyle: 'italic'
                        },
                        text: '<?= __("'Minimaal gewenste p-waarde'")?>'
                    },
                    zIndex: 3
                }]
            },

            yAxis: {
                startOnTick: false,
                endOnTick: false,
                title: {
                    text: ''
                },
                maxPadding: 0.2,
                min: 0,
                plotLines: [{
                    color: 'black',
                    dashStyle: 'dot',
                    width: 2,
                    value: 8,
                    label: {
                        align: 'middle',
                        rotation: 0,
                        y: 15,
                        style: {
                            fontStyle: 'italic'
                        },
                        text: '<?= __("'Minimaal gewenste hoeveelheid vraagitems'")?>'
                    },
                    zIndex: 3
                }]
            },

            tooltip: {
                useHTML: true,
                headerFormat: '<table>',
                pointFormat: '<tr><th colspan="2"><h3>{point.country}</h3></th></tr>' +
                '<tr><th><?= __("P-waarde")?>:</th><td>{point.x}</td></tr>' +
                '<tr><th><?= __("Getoetst dit jaar")?>:</th><td>{point.y}</td></tr>' +
                '<tr><th><?= __("Getoetst totaal")?>:</th><td>{point.z}</td></tr>',
                footerFormat: '</table>',
                followPointer: true
            },

            plotOptions: {
                series: {
                    dataLabels: {
                        enabled: true,
                        format: '{point.name}'
                    }
                }
            },

            series: [{
                data: [
                    <?
                    foreach($attainments as $attainment) {
                        if(isset($attainment['current_p_value'])) {
                            echo json_encode([
                                'x' => round($attainment['current_p_value'] * 100),
                                'y' => round($attainment['current_p_value_count']),
                                'z' => round($attainment['total_p_value_count']),
                                'name' => $attainment['description'],
                                'country' => $attainment['description'],
                                'color' => 'rgba(83, 83, 223, .1)',
                            ]) . ',';
                        }
                    }
                    foreach($attainments as $attainment) {
                        echo json_encode([
                            'x' => round($attainment['total_p_value'] * 100),
                            'y' => isset($attainment['current_p_value_count']) ? $attainment['current_p_value_count'] : 0,
                            'z' => round($attainment['total_p_value_count']),
                            'name' => $attainment['description'],
                            'country' => $attainment['description'],
                            'color' => 'rgba(20, 20, 20, .1)',
                        ]) . ',';
                    }
                    ?>
                ]
            }]

        });

        $('#divAnalyseTerms1').highcharts({
            chart: {
                type: 'scatter',
                zoomType: 'xy'
            },
            title: {
                text: ''
            },
            credits : false,
            xAxis: {
                title: {
                    enabled: false
                },
                startOnTick: true,
                endOnTick: true,
                showLastLabel: true,
                min : 0,
                max : 100
            },
            yAxis: {
                title: {
                    text: ''
                },
                min : 0,
                max : 10
            },
            legend: {
                layout: 'vertical',
                align: 'right',
                verticalAlign: 'middle',
                borderWidth: 0,
                enabled : false
            },
            plotOptions: {
                scatter: {
                    marker: {
                        radius: 5,
                        states: {
                            hover: {
                                enabled: true,
                                lineColor: 'rgb(100,100,100)'
                            }
                        }
                    },
                    states: {
                        hover: {
                            marker: {
                                enabled: false
                            }
                        }
                    },
                    tooltip: {
                        headerFormat: '<b>{series.name}</b><br>',
                        pointFormat: '{point.x}, {point.y}'
                    }
                }
            },
            series: [{
                name: '<?= __("B1 - Eiwitsynthese (Dit schooljaar)")?>',
                color: 'rgba(83, 83, 223, .5)',
                data: [
                    <?
                    foreach($attainments as $attainment) {
                        if(isset($attainment['current_p_value'])) {
                            echo "[" . round($attainment['current_p_value'] * 100) . "," . $attainment['current_p_value_count'] . "], ";
                        }
                    }
                    ?>
                ]
            },{
                name: '<?= __("'B1 - Eiwitsynthese (Totaal)'")?>',
                color: 'rgba(20, 20, 20, .5)',
                data: [
                    <?
                    foreach($attainments as $attainment) {
                        if(isset($attainment['total_p_value'])) {
                            echo "[" . round($attainment['total_p_value'] * 100) . "," . $attainment['total_p_value_count'] . "], ";
                        }
                    }
                    ?>
                ]
            }]
        });
    });
</script>