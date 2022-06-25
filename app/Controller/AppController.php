<?php
/**
 * Application level Controller
 *
 * This file is application-wide controller file. You can put all
 * application-wide controller-related methods here.
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
 * @package       app.Controller
 * @since         CakePHP(tm) v 0.2.9
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */

use PhpParser\Node\Stmt\TryCatch;

App::uses('Controller', 'Controller');
App::uses('AuthService', 'Lib/Services');
App::uses('HtmlConverter', 'Lib');
App::uses('UsersService', 'Lib/Services');
App::uses('CakeEmail', 'Network/Email');
App::uses('UnauthorizedException', 'Lib/Exceptions');

/**
 * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @property AuthService $AuthService
 *
 * @package        app.Controller
 * @link        http://book.cakephp.org/2.0/en/controllers.html#the-app-controller
 */
class AppController extends Controller
{

    /**
     * Components used by every controller.
     *
     * @var array
     */
    public $components = array(
        'Auth' => array(
            'authenticate' => array('TCCore'),
            'loginAction' => array('controller' => 'users', 'action' => 'login'),
            'logoutRedirect' => '/',
            'loginRedirect' => '/'
        ),
        'Session',
        'Cookie'
    );

    /**
     * This is executed before the start of each action in the controllers.
     *
     * @return void
     */
    public function beforeFilter()
    {

        $this->AuthService = new AuthService();
        $this->UsersService = new UsersService();

        $headers = AppVersionDetector::getAllHeaders();

        if(!$this->Session->check('TLCHeader') || !$this->Session->check('TLCHeader') == 'not secure...') {
            $this->handleHeaderCheck($headers);
        } else {
            // set the details always when there is a tlctestcorrectversion header
            if(isset($headers['tlctestcorrectversion'])){
                $this->handleHeaderCheck($headers);
            }
        }

        if ($this->Auth->loggedIn()) {
            $this->AuthService->setUser(AuthComponent::user('username'));
            $this->AuthService->setApiKey(AuthComponent::user('api_key'));
            $this->AuthService->setSessionHash(AuthComponent::user('session_hash'));
        }

        if ($this->request->isAjax() || $this->here == '/app' || strstr($this->here, '/users/reset_password')) {
            $this->layout = 'ajax';
        }

        $this->Auth->allow('get_header_session');
        $this->Auth->allow('is_in_browser');
    }

    protected function handleHeaderCheck($headers)
    {
        $this->Session->write('headers', 'unset headers');

        $this->Session->write('TLCVersion', 'unset version');
        $this->Session->write('TLCOs', 'unset os');

        if (isset($headers['tlc'])) {
            $this->Session->write('TLCHeader', $headers['tlc']);
        } else {
            $this->Session->write('TLCHeader', 'not secure...');
        }

        $version = AppVersionDetector::detect($headers);

        $this->Session->write('headers', $headers);

        $this->Session->write('TLCVersion', $version['app_version']);
        $this->Session->write('TLCOs', $version['os']);

        $versionCheckResult = AppVersionDetector::isVersionAllowed($headers);


        $this->Session->write('TLCVersionCheckResult', $versionCheckResult);
    }

    //todo: Deze methode echo'd in de response, dat verpest aanroepende methodes die zelf nog schrijven in de response
    public function formResponse($status, $data = array())
    {
        $this->autoRender = false;
        echo json_encode([
            'status' => $status ? 1 : 0,
            'data' => $data
        ]);
    }

    public function hasRole($find)
    {
        $roles = AuthComponent::user('roles');

        foreach ($roles as $role) {
            if ($role['name'] == $find) {
                return true;
            }
        }

        return false;
    }

    public function isAuthorizedAs($roles, $blockRequest = true)
    {
        if ($this->UsersService->hasRole($roles)) {
            return;
        }

        $userId = AuthComponent::user('id');
        $route = Router::url();
        $server = $_SERVER['HTTP_HOST'];
        $requestType = $_SERVER['REQUEST_METHOD'];

        //source: https://stackoverflow.com/a/5249921
        $userRoles = implode(", ", array_column($this->UsersService->getRoles(), 'name'));

        $message = "[ACCESS DENIED 403 || SERVER: $server] User with ID $userId and with role $userRoles tried to access route '$route' with request type '$requestType' and access has " . (($blockRequest) ? ' been denied' : ' NOT been denied') . ". The route should only be accessible for roles " . implode(", ", $roles);

        //write message to log
        CakeLog::write('alert', $message);

        $subject = "Access denied to user $userId - " . date('m/d/Y h:i:s a', time());

        //send email on any *portal.test-correct.nl server
        //just try to send email and otherwise not
        if (strpos($server, 'portal.test-correct.nl') !== false) {
            try {
                App::uses('BugsnagLogger', 'Lib');
                BugsnagLogger::getInstance()->setMetaData([
                    'message' => $message
                ])->notifyException(new UnauthorizedException($subject));
            } catch (\Throwable $error) {
            }
        }

        if ($blockRequest) {
            //disable this for now to get see if it will cause any issues
            //without causing any issues actually, because it is disabled.
            http_response_code(403);
            die;
        }
    }

    public function isNotInBrowser($take_id) {
        $headers = AppVersionDetector::getAllHeaders();
        $isInBrowser = AppVersionDetector::isInBrowser($headers);
        $versionCheckResult = AppVersionDetector::isVersionAllowed($headers);
        if ($versionCheckResult == AllowedAppType::NOTALLOWED && !$isInBrowser) {
            http_response_code(403);
            exit();
        }
        $allowedBrowserTesting = $this->AnswersService->is_allowed_inbrowser_testing($take_id);
        if ($isInBrowser && !$allowedBrowserTesting) {
            $message = 'Let op! Student probeert de toets te starten vanuit de console. id:'.AuthComponent::user('id').';';
            BugsnagLogger::getInstance()->setMetaData([
                'versionCheckResult' => $versionCheckResult,
                'headers' => $headers,
                'user_id' => AuthComponent::user('id'),
                'user_uuid' => AuthComponent::user('uuid')
            ])->notifyException(
                new StudentFraudDetectionException("Console hack error: (". $message .")")
            );
            http_response_code(403);
            exit();
        }
    }

    public function getallheaders()
    {
        $headers = array();
        foreach ($_SERVER as $name => $value) {
            if (substr($name, 0, 5) == 'HTTP_') {
                $headers[strtolower(str_replace(' ', '-', str_replace('_', ' ', substr($name, 5))))] = $value;
            }
        }

        return $headers;
    }

    protected function stripTagsWithoutMath($string)
    {
        return strip_tags($string, '<math>,<maction>,<menclose>,<merror>,<mfenced>,<mfrac>,<mi>,<mlongdiv>,<mlongdiv>,<mn>,<mo>,<mover>,<mpadded>,<mphantom>,<mprescripts>,<mroot>,<mrow>,<mscarries>,<msgroup>,<msline>,<mspace>,<msqrt>,<msrow>,<mstack>,<mstyle>,<msub>,<msubsup>,<msup>,<mtable>,<mtd>,<mtext>,<mtr>,<munder>,<munderover>,<none>,<presub>,<presubsup>,<sub>,<subsup>,<supsemantics>,<p>,<img>');
    }

    public function isCitoTest($test)
    {
        return $test['scope'] == 'cito';
    }

    public function isCitoQuestion($question)
    {
        return $question['scope'] == 'cito';
    }

    public function getMaskFromQuestionIfAvailable($question)
    {
        $metaDataAr = [];
        $return = null;
        if(strlen($question['metadata']) > 1){
            $metaDataAr = explode('|',$question['metadata']);
        }
        foreach($metaDataAr as $item){
            if(substr_count($item,'mask:')){
                $return = str_replace('mask:','',$item);
            }
        }
        return $return;
    }

    public function isClosedQuestion($question)
    {
        return $this->isCitoQuestion($question);
    }

    function is_eu_numeric($value)
    {
        return (preg_match ("/^(-){0,1}([0-9]+)(,[0-9][0-9][0-9])*([0-9]){0,1}([0-9]*)$/", $value) == 1);
    }

    function transformDrawingAnswer($answer, $base64 = false)
    {
        $drawingAnswer = json_decode($answer['json'])->answer;

        if (strpos($drawingAnswer, 'http') === false) {
            $drawingAnswerUrl = $this->TestTakesService->getDrawingAnswerUrl($drawingAnswer, $base64);
            $this->set('drawing_url', $this->getCorrectUrlsInString($drawingAnswerUrl));
            return $drawingAnswerUrl;
        }
        return false;
    }

    protected function getEducationLevelYears()
    {
        return [
//          0 => 'Alle',
            1 => 1,
            2 => 2,
            3 => 3,
            4 => 4,
            5 => 5,
            6 => 6
        ];
    }

    protected function handleRequestFilterAndOrderParams($params, $dataKey, $referenceAr = [])
    {
        $filters = array();
        parse_str($params['filters'], $filters);
        $filters = $filters['data'][$dataKey];
        $params['filters'] = [];
        foreach($referenceAr as $filterLaravelKey => $filterCakeKey){

            if (!empty($filters[$filterCakeKey])) {
                $params['filter'][$filterLaravelKey] = $filters[$filterCakeKey];
            }
        }

        if (!empty($filters['created_at_start'])) {
            $params['filter']['created_at_start'] = date('Y-m-d 00:00:00', strtotime($filters['created_at_start']));
        }

        if (!empty($filters['created_at_end'])) {
            $params['filter']['created_at_end'] = date('Y-m-d 00:00:00', strtotime($filters['created_at_end']));
        }

        return $this->handleRequestOrderParameters($params);
    }

    function handleRequestOrderParameters($params, $sortKey = 'id', $direction = 'desc')
    {
        if ((!isset($params['sort']) || empty($params['sort'])) ||
            (!isset($params['direction']) || empty($params['direction']))) {
            $params['order'] = [$sortKey => $direction];
        } else {
            $params['order'] = [$params['sort'] => $params['direction']];
            unset($params['sort'], $params['direction']);
        }

        return $params;
    }

    function getAppInfoFromSession()
    {
        return [
            'TLCVersion'            => CakeSession::read('TLCVersion'),
            'TLCOs'                 => CakeSession::read('TLCOs'),
            'TLCHeader'             => CakeSession::read('TLCHeader'),
            'TLCVersionCheckResult' => CakeSession::read('TLCVersionCheckResult'),
            'TLCVersioncheckResult' => CakeSession::read('TLCVersionCheckResult'),
            'headers'               => $this->getallheaders(),
        ];
    }

    public function setUserLanguage()
    {
        if(is_null(AuthComponent::user('school_location')['school_language_cake'])){
            $language = strtolower(substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2));
            if(!in_array($language,['en','nl'])){
                $language = 'nld';
            }
            if($language === 'en') {
                $language = 'eng';
            }
            $this->Session->write('Config.language', $language);
        }
        else{
            $this->Session->write('Config.language', AuthComponent::user('school_location')['school_language_cake']);
        }
    }

    public function returnToLaravelUrl($userId, $params = [])
    {
        $returnUrl = $this->UsersService->getReturnToLaravelUrl($userId, $params);
        $appInfo = $this->getAppInfoFromSession();

        if ($appInfo['TLCOs'] == 'iOS') {
            $separator = '?';
            if (strpos($returnUrl['url'], '?') !== false) {
               $separator = '&';
            }
            $returnUrl['url'] = $returnUrl['url'] . $separator . 'device=ipad';
        }

        return $returnUrl;
    }

    public function getCorrectUrlsInString($string)
    {
        return HelperFunctions::getInstance()->getCorrectUrlsInString($string);
    }
}
