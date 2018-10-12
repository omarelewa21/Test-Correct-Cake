<?
$data = "";

foreach($subjects as $subject) {
    if($subject['id'] == $subject_id) {
        $data = $subject;
    }
}

if(!isset($data['school_years'][0])) {
    die('Deze grafiek kon niet worden gegegereerd');
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
            series: [{
                name: 'Studentgemiddelde',
                data: [
                    <?
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
                name: 'Klassengemiddelde',
                data: [
                    <?
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
                name: 'Behaalde resultaten',
                data: [
                    <?
                    foreach($student['ratings'] as $rating) {

                        $dateFixed = date('Y', strtotime($rating['time_start'])) . ', ';
                        $dateFixed .= (date('m', strtotime($rating['time_start'])) - 1) . ', ';
                        $dateFixed .= date('d', strtotime($rating['time_start']));

                        if($type == 'percentages') {
                            $percentage = 0;
                            if($rating['max_score'] != '0.0' && $rating['score'] != '0.0') {
                                $percentage = (100 / $rating['max_score']) * $rating['score'];
                            }

                            echo "[Date.UTC(" . $dateFixed . "), " . round($percentage, 1) . "],";
                        }else{
                            echo "[Date.UTC(" . $dateFixed . "), " . round($rating['rating'], 1) . "],";
                        }
                    }
                    ?>
                ]
            }]
        });
    });
</script>
