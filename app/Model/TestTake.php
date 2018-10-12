<?php
App::uses('AppModel', 'Model');

class TestTake extends AppModel
{
    public $displayField = 'name';


    public function check($test_take, $test) {
        $errors = [];

        if(empty($test_take['date']) && strlen($test_take['date']) != 10) {
            $errors[] = "Geen datum ingevuld";
        }

        if(empty($test_take['period_id'])) {
            $errors[] = "Geen periode geselecteerd";
        }

        if(empty($test_take['invigilators'])) {
            $errors[] = "Geen surveillanten geselecteerd";
        }

        if(empty($test_take['class_id'])) {
            $errors[] = "Geen klas geselecteerd";
        }

        if(isset($test_take['weight'])) {
            if ($test_take['weight'] == '' || !is_numeric($test_take['weight'])) {
                $errors[] = "Geen correcte weging ingevoerd";
            }

            if($test['test_kind_id'] == "3") {
                if((int) $test_take['weight'] < 1 ) {
                    $errors[] = "Voor een summatieve toets mag de weging niet 0 zijn.";
                }
            }

            if($test_take['is_rtti_test_take'] == "1" ) {

                if((int) $test_take['weight'] == 0 && $test['test_kind_id'] == "3") {
                    $errors[] = "Voor een RTTI toets mag de weging niet 0 zijn.";
                }

                if($test['test_kind_id'] == "3" || $test['test_kind_id'] == '2') {
                    if((int) $test_take['weight'] > 15 ) {
                        $errors[] = "Voor een summatieve RTTI toets mag de weging niet meer dan 15 zijn.";
                    }
                }
            }
        } 

        return [
            'status' => count($errors) == 0 ? true : false,
            'errors' => $errors
        ];
    }

    public function checkRetake($test_take, $test) {
        $errors = [];

        if(empty($test_take['time_start']) && strlen($test_take['time_start']) != 10) {
            $errors[] = "Geen datum ingevuld";
        }

        if(empty($test_take['period_id'])) {
            $errors[] = "Geen periode geselecteerd";
        }

        if(empty($test_take['invigilators'])) {
            $errors[] = "Geen surveillanten geselecteerd";
        }

        if(empty($test_take['retake_test_take_id '])) {
            $errors[] = "Geen klas geselecteerd";
        }

        if(isset($test_take['weight'])) {
            if ($test_take['weight'] == '' || !is_numeric($test_take['weight'])) {
                $errors[] = "Geen correcte weging ingevoerd";
            }

            if($test['test_kind_id'] == "3") {
                if((int) $test_take['weight'] < 1 ) {
                    $errors[] = "Voor een summatieve toets mag de weging niet 0 zijn.";
                }
            }

            if($test_take['is_rtti_test_take'] == "1" ) {

                if((int) $test_take['weight'] == 0 && $test['test_kind_id'] == "3") {
                    $errors[] = "Voor een RTTI toets mag de weging niet 0 zijn.";
                }

                if($test['test_kind_id'] == "3" || $test['test_kind_id'] == '2') {
                    if((int) $test_take['weight'] > 15 ) {
                        $errors[] = "Voor een summatieve RTTI toets mag de weging niet meer dan 15 zijn.";
                    }
                }
            }
        }

        return [
            'status' => count($errors) == 0 ? true : false,
            'errors' => $errors
        ];
    }

    public function checkEdit($test_take, $retake, $test) {

        $errors = [];

        if(empty($test_take['time_start']) && strlen($test_take['time_start']) != 10) {
            $errors[] = "Geen datum ingevuld";
        }

        if(empty($test_take['period_id']) && !$retake) {
            $errors[] = "Geen periode geselecteerd";
        }

        if(empty($test_take['invigilators'])) {
            $errors[] = "Geen surveillanten geselecteerd";
        }

        if(!$retake) {
            if(isset($test_take['weight'])) {
                if ($test_take['weight'] == '' || !is_numeric($test_take['weight'])) {
                    $errors[] = "Geen correcte weging ingevoerd";
                }

                if($test['test_kind_id'] == "3") {
                    if((int) $test_take['weight'] < 1 ) {
                        $errors[] = "Voor een summatieve toets mag de weging niet 0 zijn.";
                    }
                }

                if($test_take['is_rtti_test_take'] == "1" ) {

                    if((int) $test_take['weight'] == 0 && $test['test_kind_id'] == "3") {
                        $errors[] = "Voor een RTTI toets mag de weging niet 0 zijn.";
                    }

                    if($test['test_kind_id'] == "3" || $test['test_kind_id'] == '2') {
                        if((int) $test_take['weight'] > 15 ) {
                            $errors[] = "Voor een summatieve RTTI toets mag de weging niet meer dan 15 zijn.";
                        }
                    }                    
                }
            }
        }

        return [
            'status' => count($errors) == 0 ? true : false,
            'errors' => $errors
        ];
    }
}