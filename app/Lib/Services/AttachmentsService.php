<?php

App::uses('BaseService', 'Lib/Services');

/**
 * Class AttachmentsService
 *
 *
 */
class AttachmentsService extends BaseService {

    public function getAttachments($question_id) {
        $response = $this->Connector->getRequest('/question/' . $question_id . '/attachment', []);
        if ($response === false) {
            return $this->Connector->getLastResponse();
        }

        return $response;
    }

    public function getAttachment($question_id) {
        $response = $this->Connector->getRequest('/question/' . $question_id . '/attachment', []);
      
        return $response;
    }
}
