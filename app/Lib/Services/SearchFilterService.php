<?php

App::uses('BaseService', 'Lib/Services');


/**
 * Class TestsService
 *
 *
 */
class SearchFilterService extends BaseService {

	public function add($searchFilter){
		$response = $this->Connector->postRequest('/search_filter', [], $searchFilter);
		return $response;
	}

	public function edit($uuid,$searchFilter){
		$response = $this->Connector->putRequest('/search_filter/' . $uuid, [], $searchFilter);
		return $response;
	}

	public function delete($uuid){
		$response = $this->Connector->deleteRequest('/search_filter/' . $uuid, []);
		return $response;
	}

	public function get($key){
		$response = $this->Connector->getRequest('/search_filter/' . $key, []);
		return $response;
	}

}