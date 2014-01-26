<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2014 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Admin\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Admin\Model\User;
use Admin\Model\UsersTable;
use Admin\Controller\ActionController;
use Admin\Service\Auth;
use Admin\Form\LoginForm;
use Zend\Session\SessionManager;
use Zend\Session\Container;
use Zend\Authentication\AuthenticationService;
use Zend\Authentication\Adapter\DbTable as AuthAdapter;
use Zend\Authentication\Result;
use Zend\Authentication\Adapter\DbTable;

class AuthController extends ActionController
{

    /**
     * Listagem de livros
     * @return 
     */
    public function indexAction()
    {

        $auth = new AuthenticationService();
        $is_auth = $auth->hasIdentity(); 

        if($is_auth) {
            $this->redirect()->toUrl('/admin/auth/logout');
        }

        $form = new LoginForm();
        $form->get('submit')->setValue('Login');

        $request = $this->getRequest();

        if ($request->isPost()) {

            $user = new User();
            $post = $request->getPost();

            $form->setInputFilter($user->getInputFilter());
            $form->setData($post);

            if ($form->isValid()) {

                // service manager
                $service = $this->getService('Admin\Service\Auth');
                $auth = $service->authenticate(
                    array(
                        'username' => $post['username'],
                        'password' => $post['password']
                    )
                );            
            }
            
            return $this->redirect()->toRoute('books');
        }

        return array('form' => $form);
    }

    /*
     * Deslogar usuário
     */
    public function logoutAction()
    {

        $request = $this->getRequest();

        if ($request->isPost()) {

            $logout = $request->getPost('logout', 'No');

            if ($logout == 'Yes') {
                $service = $this->getService('Admin\Service\Auth');
                $auth = $service->logout();        
                return $this->redirect()->toRoute('admin');

            }else{
                return $this->redirect()->toRoute('books');

            }            
        }
    }

    /*
     * TableGateway
     */
    public function getUsersTable()
    {
        if (!$this->usersTable) {
            $sm = $this->getServiceLocator();
            $this->usersTable = $sm->get('Admin\Model\UsersTable');
        }
        return $this->usersTable;
    }

}
