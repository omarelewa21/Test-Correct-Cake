<?php
/**
 * Created by PhpStorm.
 * User: erik
 * Date: 30/07/2019
 * Time: 14:23
 */

class SobitLogger
{
    protected $mainDateTime;
    protected $mainStartTime;
    protected $mainUrl;
    protected $mainMethod;

    protected $subDateTime;
    protected $subStartTime;
    protected $subUrl;
    protected $subMethod;

    protected $subs = [];
    protected $file;

    // Hold the class instance.
    private static $instance = null;

    // The constructor is private
    // to prevent initiation with outer code.
    private function __construct()
    {
        $this->file = sprintf('../sobitLogs/sobitLogger-%s.log',date('Y-m-d'));
    }

    // The object is created from within the class itself
    // only if the class has no instance.
    public static function getInstance()
    {
        if (self::$instance == null)
        {
            self::$instance = new SobitLogger();
        }

        return self::$instance;
    }

    private function getTime(){
        return microtime(true);
    }

    public function startMain($url, $method){
        $this->mainDateTime = new \DateTime('now');
        $this->mainStartTime = $this->getTime();
        $this->mainUrl = $url;
        $this->mainMethod = $method;
        return $this;
    }

    public function endMain(){
        $duration = ($this->getTime() - $this->mainStartTime) * 1000; // in ms
        $totalSubTime = 0;
        foreach($this->subs as $sub){
            $sub = (object) $sub;

            $totalSubTime += $sub->duration;
        }
        $this->log('mainStart', $this->mainDateTime, $this->mainUrl, $this->mainMethod, $duration, $duration-$totalSubTime);
        foreach($this->subs as $sub){
            $sub = (object) $sub;
            $this->log('sub', $sub->dateTime, $sub->url, $sub->method, $sub->duration);
        }
        $this->log('mainEnd');
        return $this;
    }

    protected function resetSub(){
        $this->subDateTime = null;
        $this->subUrl = false;
        $this->subStartTime = false;
        $this->subMethod = false;
    }

    public function startSub($url, $method){
        $this->subDateTime = new \DateTime('now');
        $this->subStartTime = $this->getTime();
        $this->subUrl = $url;
        $this->subMethod = $method;
        return $this;
    }

    public function endSub(){
        $duration = ($this->getTime() - $this->subStartTime) * 1000; // in ms
        $this->subs[] = [
            'dateTime' => $this->subDateTime,
            'startTime' => $this->subStartTime,
            'url' => $this->subUrl,
            'method' => $this->subMethod,
            'duration' => $duration,
        ];
        $this->resetSub();
        return $this;
    }

    protected function log($type, $dateTime = false, $url = '', $method = '', $duration = 0, $cakeTime = 0){
        if($type === 'mainEnd'){
            $line = sprintf("\n");
        }
        else if($type === 'mainStart') {
            $line = sprintf("%s%s %01.2f | %s | %s | %01.2f |  %01.2f%%\n",
                ($type == 'sub') ? '    ' : '',
                $dateTime->format('Y-m-d H:i:s'),
                $duration,
                strtoupper($method),
                $url,
                $cakeTime,
                $cakeTime/$duration*100
            );
        }
        else {
            $line = sprintf("%s%s %01.2f | %s | %s\n",
                ($type == 'sub') ? '    ' : '',
                $dateTime->format('Y-m-d H:i:s'),
                $duration,
                strtoupper($method),
                $url
            );
        }
        file_put_contents($this->file, $line, FILE_APPEND | LOCK_EX);
    }
}