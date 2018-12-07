<?php
App::uses('AppModel', 'Model');

class AttachmentSound extends AppModel
{
    const MimeMap = ["audio/mpeg"=>"mp3", "audio/ogg"=>"ogg"];

    function __construct($id, $file_name, $mimetype)
    {
        $this->attachment_id = $id;
        $this->file_name = $file_name;
        $this->mimetype = $mimetype;
    }

    // We gooien een URL over de muur, daarin kan ik geen audio/... meegeven zonder escape gedoe
    function getSoundType() {
        return AttachmentSound::MimeMap[$this->mimetype];
    }

    public static function getMimeFromSoundType($soundtype) {
        return array_search($soundtype,AttachmentSound::MimeMap);
    }

    public function cacheSound($attachmentContent)
    {
        $tempFileName = $this->getTemporaryFileName();
        if (!file_exists($tempFileName)) {
            file_put_contents($tempFileName, $attachmentContent);
        }
    }

    public function isCached() {
        return file_exists($this->getTemporaryFileName());
    }

    public function getCachedSound()
    {
        $content = file_get_contents($this->getTemporaryFileName());
        if(!$content) {
            throw Exception("Could not read " . $this->getTemporaryFileName());
        } else {
            return $content;
        }
    }

    public static function testIfIsSound($attachmentInfo)
    {
        return in_array($attachmentInfo["file_mime_type"], array_keys(AttachmentSound::MimeMap) );
    }

    public function getTemporaryFileName()
    {
        return Configure::read('sound_storage') . $this->attachment_id . $this->file_name . "." . $this->getSoundType();
    }
};

