<?php

App::uses('BaseService', 'Lib/Services');

/**
 * Class SchoolYearsService
 *
 *
 */
class SchoolYearsService extends BaseService
{
    public function getSchoolYears($params)
    {
        $response = $this->Connector->getRequest('/school_year', $params);
        if ($response === false) {
            return $this->Connector->getLastResponse();
        }


        if (isset($response['data']) && !empty($response['data'])) {
            return $response['data'];
        } else {
            return [];
        }
    }

    public function getSchoolYearList()
    {
        $response = $this->Connector->getRequest('/school_year', ['mode' => 'list']);
        if ($response === false) {
            return $this->Connector->getLastResponse();
        }

        return $response;
    }

    public function getActiveSchoolYearId()
    {
        $response = $this->Connector->getRequest('/school_year_active',[]);
        if ($response === false) {
            return $this->Connector->getLastResponse();
        }
        if(!array_key_exists(0, $response)){
            return '';
        }
        if(!array_key_exists('id', $response[0])){
            return '';
        }
        return $response[0]['id'];
    }

    public function addSchoolYear($data) {
        $response = $this->Connector->postRequest('/school_year', [], $data);


        if($response === false){
            return $this->Connector->getLastResponse();
        }

        return $response;
    }

    public function getSchoolYear($id) {

        $response = $this->Connector->getRequest('/school_year/' . $id, []);
        if ($response === false) {
            return $this->Connector->getLastResponse();
        }

        return $response;
    }

    public function updateSchoolYear($id, $data) {

        $response = $this->Connector->putRequest('/school_year/' . $id, [], $data);
        if ($response === false) {
            return $this->Connector->getLastResponse();
        }

        return $response;
    }

    public function deleteSchoolYear($id) {
        $response = $this->Connector->deleteRequest('/school_year/' . $id, []);
        if($response === false){
            return $this->Connector->getLastResponse();
        }

        return $response;
    }

    public function updateSchoolYearPeriod($period_id, $data) {
        $data['start_date'] = date('Y-m-d H:i:00', strtotime($data['start_date']));
        $data['end_date'] = date('Y-m-d H:i:00', strtotime($data['end_date']));

        $response = $this->Connector->putRequest('/period/' . $period_id, [], $data);

        if($this->Connector->getLastCode() === 422) {
            return $this->Connector->getLastResponse();
        }

        if($response === false){
            return $this->Connector->getLastResponse();
        }

        return $response;
    }


    public function getSchoolYearPeriod($id) {
        $response = $this->Connector->getRequest('/period/' . $id, []);
        if ($response === false) {
            return $this->Connector->getLastResponse();
        }

        return $response;
    }

    public function checkSchoolYearPeriod($year_id, $data, $period_id = null) {
        $year = $this->getSchoolYear($year_id);

        foreach($year['periods'] as $period) {

            $s1 = strtotime($period['start_date']);
            $e1 = strtotime($period['end_date']);

            $s2 = strtotime($data['start_date']);
            $e2 = strtotime($data['end_date']);

            if($period_id != getUUID($period, 'get')) {
                if ($s2 >= $s1 && $s2 <= $e1) {
                    return false;
                }
                if ($e2 >= $s1 && $e2 <= $e1) {
                    return false;
                }
            }
        }

        return true;
    }


    public function addSchoolYearPeriod($school_year_id, $data) {
        $school_year = $this->getSchoolYear($school_year_id);

        $data['school_year_id'] = $school_year['id'];

        $data['start_date'] = date('Y-m-d H:i:00', strtotime($data['start_date']));
        $data['end_date'] = date('Y-m-d H:i:00', strtotime($data['end_date']));

        $response = $this->Connector->postRequest('/period', [], $data);


        if($this->Connector->getLastCode() === 422) {
            return $this->Connector->getLastResponse();
        }

        if($response === false){
            return $this->Connector->getLastResponse();
        }



        return $response;
    }

    public function deleteSchoolYearPeriod($id) {
        $response = $this->Connector->deleteRequest('/period/' . $id, []);
        if($response === false){
            return $this->Connector->getLastResponse();
        }

        return $response;
    }
}
