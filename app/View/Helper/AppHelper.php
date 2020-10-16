<?php
/**
 * Application level View Helper
 *
 * This file is application-wide helper file. You can put all
 * application-wide helper-related methods here.
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.View.Helper
 * @since         CakePHP(tm) v 0.2.9
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */

App::uses('Helper', 'View');

/**
 * Application helper
 *
 * Add your application-wide methods in the class below, your helpers
 * will inherit them.
 *
 * @package       app.View.Helper
 */
class AppHelper extends Helper {

    public static function isCitoTest($test)
    {
        return (new AppController())->isCitoTest($test);
//        return substr_count($test['metadata'],'cito') > 0;
    }

    public static function isCitoQuestion($question)
    {
        return (new AppController())->isCitoQuestion($question);
//        return substr_count($question['metadata'],'cito') > 0;
    }

    public static function showExternalId($question)
    {
        if(static::isTestPortal()){
            return sprintf(' %s',$question['external_id']);
        }
        return '';
    }

    public static function isTestPortal()
    {
        $host = SobitLogger::getInstance()->getHost();
        return !!(substr_count($host,'testportal.test-correct') > 0);
    }

    public static function getDivForAttainmentAnalysis($data, $isPerAttainment = false){
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

        $pValue = $data['p_value']*100;

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
            if($ar['start'] < $data['questions_per_attainment'] && $ar['end'] >= $data['questions_per_attainment']){
                $factor = $ar['multiplierBase'] + (($data['questions_per_attainment']-$ar['start'])/($ar['end']-$ar['start']));
                break;
            }
        }

        $width = round((300/5) * $factor); // total width 300 with 5 blocks

        $fontStyle = 'normal';
        if($isPerAttainment){
            $fontStyle='italic';
        }

        return sprintf('<div title="p-waarde van %d, gebaseerd op %d vragen voor dit leerdoel" style="overflow:hidden;border:1px solid %s;border-radius:3px;width:%dpx;height:15px;background:%s;text-align:center;font-size:10px;font-weight:bold;line-height:15px;font-style:%s">P%d</div>',$pValue, $data['questions_per_attainment'],$borderColor,$width,$bgColor,$fontStyle,$pValue);

    }

}
