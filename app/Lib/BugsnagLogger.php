<?php

App::uses("AppVersionDetector", "Lib");

class BugsnagLogger
{

    private $bugsnag;

    private $defaultFilters = [
        'api_key', 'session_hash', 'main_address', 'main_city', 'main_country', 'main_postal', 
        'invoice_address', 'visit_address', 'visit_postal', 'visit_city', 'visit_country', 
        'username', 'name_first', 'name_suffix', 'abbreviation', 'password', 'wachtwoord'
    ];

    private static $instance = null;

    private static $releasesPath  = "../../../.dep/releases";

    public function __construct($register = false)
    {
        if (Configure::read('bugsnag-key-cake') == null) {
            return;
        }
        
        $this->bugsnag = Bugsnag\Client::make(Configure::read('bugsnag-key-cake'));

        $headers = AppVersionDetector::getAllHeaders();

        $this->addFilter($this->defaultFilters)->configureAppversion()->setMetaData([
            'app' => array_merge(AppVersionDetector::detect($headers), ["allowed" => AppVersionDetector::isVersionAllowed($headers)])
        ]);

        $this->bugsnag->setErrorReportingLevel(E_ERROR);

        if ($register) {
            $this->register();

            $this->bugsnag->leaveBreadcrumb("Bugsnag loaded");
        }
    }

    protected function register()
    {
        Bugsnag\Handler::register($this->bugsnag);
    }

    public function unsetUser()
    {
//        $this->bugsnag->registerCallback(function($report){
//
//            $report->setUser([]);
//
//            $this->register();
//        });

        $this->configureUser([]);

        return $this;
    }

    public function configureUser($user = null) {
        $userData = [];
        if(null == $user && count($user)){
            $userData = [
                'id' => isset($user['id']) ? $user['id'] : '-',
                'uuid' => isset($user['uuid']) ? $user['uuid'] : '-',
                'roles' => isset($user['roles']) ? $user['roles'] : '-',
                'isToetsenbakker' => isset($user['isToetsenbakker']) ? $user['isToetsenbakker'] : false,
                'is_temp_teacher' => isset($user['is_temp_teacher']) ? $user['is_temp_teacher'] : false
            ];
        }

//        $this->bugsnag->registerCallback(function($report) use ($userData){
//            if ($userData == null) {
//                return;
//            }
//
//            $report->setUser($userData);
//        });

        if(null !== $user){
            $this->setMetaData([
                'userData' => $userData
            ]);
        }

        $this->register();

        return $this;
    }

    private function configureAppversion() {
        $realPath = realpath(ROOT);
        if(!$realPath){
            return $this;
        }
        $ar = explode(DS,$realPath);
        if(count($ar) === 0){
            return $this;
        }
        $version = end($ar);

        if (!$version) {
            return $this;
        }

        $this->bugsnag->setAppVersion($version);

        if (Configure::read('bugsnag-release-stage') != null) {
            $this->bugsnag->setReleaseStage(Configure::read('bugsnag-release-stage'));
        }

        return $this;
    }

    public static function getInstance($register = false)
    {
        if (self::$instance == null)
        {
            self::$instance = new BugsnagLogger($register);
        }
        return self::$instance;
    }

    public function setMetaData($array = [], $merge = true)
    {
        if ($this->bugsnag == null) {
            return $this;
        }

        $this->bugsnag->setMetaData($array, $merge);

        return $this;
    }

    public function addFilter($array)
    {
        if ($this->bugsnag == null) {
            return $this;
        }
    
        //setFilters is deprecated and followed up by setRedactedKeys
        if (method_exists($this->bugsnag, 'setRedactedKeys')) {
            $this->bugsnag->setRedactedKeys(array_merge($this->bugsnag->getRedactedKeys(), $array));
        } else {
            $this->bugsnag->setFilters(array_merge($this->bugsnag->getFilters(), $array));
        }

        return $this;
    }

    /**
     * @param exception: exception extending Exception
     * 
     * Reporting an exception will report stracktraces etc.
     */
    public function notifyException($exception)
    {
        if ($this->bugsnag == null) {
            return $this;
        }

        $this->bugsnag->notifyException($exception);

        return $this;
    }

    /**
     * @param type string: Type of exception
     * @param string string: Name of error
     * 
     * Reporting an exception will report stracktraces etc.
     */
    public function notifyError($type, $string)
    {
        if ($this->bugsnag == null) {
            return $this;
        }

        $this->bugsnag->notifyError($type, $string);

        return $this;
    }


	/**
	 * Slightly modified version of http://www.geekality.net/2011/05/28/php-tail-tackling-large-files/
	 * @author Torleif Berger, Lorenzo Stanco
	 * @link http://stackoverflow.com/a/15025877/995958
	 * @license http://creativecommons.org/licenses/by/3.0/
	 */
	private function tailCustom($filepath, $lines = 1, $adaptive = true) {

		// Open file
		$f = @fopen($filepath, "rb");
		if ($f === false) return false;

		// Sets buffer size, according to the number of lines to retrieve.
		// This gives a performance boost when reading a few lines from the file.
		if (!$adaptive) $buffer = 4096;
		else $buffer = ($lines < 2 ? 64 : ($lines < 10 ? 512 : 4096));

		// Jump to last character
		fseek($f, -1, SEEK_END);

		// Read it and adjust line number if necessary
		// (Otherwise the result would be wrong if file doesn't end with a blank line)
		if (fread($f, 1) != "\n") $lines -= 1;
		
		// Start reading
		$output = '';
		$chunk = '';

		// While we would like more
		while (ftell($f) > 0 && $lines >= 0) {

			// Figure out how far back we should jump
			$seek = min(ftell($f), $buffer);

			// Do the jump (backwards, relative to where we are)
			fseek($f, -$seek, SEEK_CUR);

			// Read a chunk and prepend it to our output
			$output = ($chunk = fread($f, $seek)) . $output;

			// Jump back to where we started reading
			fseek($f, -mb_strlen($chunk, '8bit'), SEEK_CUR);

			// Decrease our line counter
			$lines -= substr_count($chunk, "\n");

		}

		// While we have too many lines
		// (Because of buffer size we might have read too many)
		while ($lines++ < 0) {

			// Find first newline and remove all text before that
			$output = substr($output, strpos($output, "\n") + 1);

		}

		// Close file and return
		fclose($f);
		return trim($output);

	}

}
