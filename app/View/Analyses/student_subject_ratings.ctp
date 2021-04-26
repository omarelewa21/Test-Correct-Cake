<?
$data = "";

foreach($subjects as $subject) {
    if($subject['uuid'] == $subject_id) {
        $data = $subject;
    }
}

if(!isset($data['school_years'][0])) {
    die(__("Deze grafiek kon niet worden gegegereer"));
}

$data = $data['school_years'][0];
?>

<script type="text/javascript">
    $(function () {
        $('#divAnalyseSubjectRatings').highcharts({
            title: {
                text: '',
            },
            credits : false,
            xAxis: {
                type: 'datetime',
                dateTimeLabelFormats: { // don't display the dummy year
                    month: '%e. %b',
                    year: '%b'
                },
                title: {
                    text: ''
                }
            },
            yAxis: {
                title: {
                    enabled : false
                },
                min: 1,
                max: <?=$type == 'percentages' ? 100 : 10?>,
                tickInterval: 0.5,
                plotLines: [{
                    color: 'black',
                    width: 2,
                    value: <?=$type == 'percentages' ? 55 : 5.5?>
                }]
            },
            legend: {
                layout: 'vertical',
                align: 'right',
                verticalAlign: 'middle',
                borderWidth: 0
            },
            plotOptions : {
            
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
                            pointFormat: '<br><?= __("Cijfer")?> {point.y}'
                        }
                    }
            },
            series: [{
                name: '<?= __("Studentgemiddelde")?>',
                data: [
                    <?php
                    foreach($data['studentAverages'] as $date => $rating) {

                        $dateFixed = date('Y', strtotime($date)) . ', ';
                        $dateFixed .= (date('m', strtotime($date)) - 1) . ', ';
                        $dateFixed .= date('d', strtotime($date));

                        if($type == 'percentages') {
                            $percentage = $rating * 100;
                            echo "[Date.UTC(" . $dateFixed . "), " . round($percentage, 1) . "],";
                        }else{
                            echo "[Date.UTC(" . $dateFixed . "), " . round($rating, 1) . "],";
                        }
                    }
                    ?>
                ]
            }, {
                name: '<?= __("Klassengemiddelde")?>',
                data: [
                    <?php
                    foreach($data['classAverages'] as $date => $rating) {

                        $dateFixed = date('Y', strtotime($date)) . ', ';
                        $dateFixed .= (date('m', strtotime($date)) - 1) . ', ';
                        $dateFixed .= date('d', strtotime($date));

                        if($type == 'percentages') {
                            $percentage = $rating * 100;
                            echo "[Date.UTC(" . $dateFixed . "), " . round($percentage, 1) . "],";
                        }else{
                            echo "[Date.UTC(" . $dateFixed . "), " . round($rating, 1) . "],";
                        }
                    }
                    ?>
                ]
            }, {
                name: '<?= __("Behaalde resultaten")?>',     
                type : 'scatter',
                data: [
                    <?php
                    foreach($student['ratings'] as $rating) {
                    
                        if($rating['user_id']==$student['id']) {

                            $dateFixed = date('Y', strtotime($rating['time_start'])) . ', ';
                            $dateFixed .= (date('m', strtotime($rating['time_start'])) - 1) . ', ';
                            $dateFixed .= date('d', strtotime($rating['time_start']));

                            if($type == 'percentages') {
                                $percentage = 0;
                                if($rating['max_score'] != '0.0' && $rating['score'] != '0.0') {
                                    $percentage = (100 / $rating['max_score']) * $rating['score'];
                                }
                                                 
                            }else{                           
         
                                echo "[Date.UTC(" . $dateFixed . "), " . round($rating['rating'], 1) . "],";
                                
                            }
                        }
                    }
                    ?>
                ]
            }]
        });
    });
</script>
