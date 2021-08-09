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

        if(!$this->Session->check('TLCHeader')) {
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
        return strip_tags($string, '<math>,<maction>,<menclose>,<merror>,<mfenced>,<mfrac>,<mi>,<mlongdiv>,<mlongdiv>,<mn>,<mo>,<mover>,<mpadded>,<mphantom>,<mprescripts>,<mroot>,<mrow>,<mscarries>,<msgroup>,<msline>,<mspace>,<msqrt>,<msrow>,<mstack>,<mstyle>,<msub>,<msubsup>,<msup>,<mtable>,<mtd>,<mtext>,<mtr>,<munder>,<munderover>,<none>,<presub>,<presubsup>,<sub>,<subsup>,<supsemantics>');
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

}
