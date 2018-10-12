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

App::uses('Controller', 'Controller');
App::uses('AuthService', 'Lib/Services');
App::uses('HtmlConverter', 'Lib');

/**
 * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @property AuthService $AuthService
 *
 * @package		app.Controller
 * @link		http://book.cakephp.org/2.0/en/controllers.html#the-app-controller
 */
class AppController extends Controller {

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
        $headers = $this->getallheaders();
        // $headers = $_SERVER;

        // if(!$this->Session->check("TLCHeader")){
        // $lowercaseheaders = array_change_key_case($headers);

        if( !$this->Session->check('TLCHeader') ) {
            if(isset($headers['tlc'])) {
                $this->Session->write('TLCHeader',$headers['tlc']);
            } else {
                $this->Session->write('TLCHeader','not secure...');
            }
        }

        // }
        
        // die(var_dump($this->Session->read('TLCHeader')));

        // echo '<pre> ';
        // die(var_dump($lowercaseheaders));


        if(!$this->Session->check('TLCVersion')) {
            if(isset($headers['tlctestcorrectversion'])) {
              $this->Session->Write('TLCVersion', $headers['tlctestcorrectversion']);
            }else{
                $this->Session->Write('TLCVersion', 'x');
            }
        }

        $this->Session->Write('headers',$headers);

        $this->AuthService = new AuthService();

        if ($this->Auth->loggedIn()) {
            $this->AuthService->setUser(AuthComponent::user('username'));
            $this->AuthService->setApiKey(AuthComponent::user('api_key'));
            $this->AuthService->setSessionHash(AuthComponent::user('session_hash'));
        }
        if($this->request->isAjax() || $this->here == '/app' || strstr($this->here, '/users/reset_password')) {
            $this->layout = 'ajax';
        }

        $this->Auth->allow('get_header_session');
    }

    public function formResponse($status, $data = array()) {
        $this->autoRender = false;
         echo json_encode([
             'status' => $status ? 1 : 0,
             'data' => $data
         ]);
    }

    public function hasRole($find) {
        $roles = AuthComponent::user('roles');

        foreach($roles as $role) {
            if($role['name'] == $find) {
                return true;
            }
        }

        return false;
    }

    public function getallheaders() {
        $headers = array ();
        foreach ($_SERVER as $name => $value) {
            if (substr($name, 0, 5) == 'HTTP_') {
                $headers[strtolower(str_replace(' ', '-', str_replace('_', ' ', substr($name, 5))))] = $value;
            }
        }

        return $headers;
    }
}
