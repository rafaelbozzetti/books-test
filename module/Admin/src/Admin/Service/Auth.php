<?php
namespace Admin\Service;
 
use Admin\Service\Service;
use Admin\Service\Builder;
use Zend\Authentication\AuthenticationService;
use Zend\Authentication\Adapter\DbTable as AuthAdapter;
use Zend\Authentication\Result;
 
/**
 * Serviço responsável pela autenticação da aplicação
 * 
 * @category Admin
 * @package Service
 * @author  Elton Minetto<eminetto@coderockr.com>
 */
class Auth extends Service
{
    /**
     * Adapter usado para a autenticação
     * @var Zend\Db\Adapter\Adapter
     */
    private $dbAdapter;
 
    /** 
     * Construtor da classe
     *
     * @return void
     */
    public function __construct($dbAdapter = null)
    {
        $this->dbAdapter = $dbAdapter;
    }
 
    /**
     * Faz a autenticação dos usuários
     * 
     * @param array $params
     * @return array
     */
    public function authenticate($params)
    {
        if (!isset($params['username']) || !isset($params['password'])) {
            throw new \Exception("Parâmetros inválidos");
        }
        
        $password = md5($params['password']);
        $auth = new AuthenticationService();
        $authAdapter = new AuthAdapter($this->dbAdapter);
        $authAdapter
            ->setTableName('users')
            ->setIdentityColumn('username')
            ->setCredentialColumn('password')
            ->setIdentity($params['username'])
            ->setCredential($password);
        $result = $auth->authenticate($authAdapter);
        
        if (! $result->isValid()) {
            throw new \Exception("Login ou senha inválidos");
        }
 
        //salva o user na sessão
        $session = $this->getServiceManager()->get('Session');        
        $session->offsetSet('user', $authAdapter->getResultRowObject());
 
        return $this;
    }
 
    /**
     * Faz o logout do sistema
     *
     * @return void
     */
    public function logout() {
        $auth = new AuthenticationService();
        $session = $this->getServiceManager()->get('Session');
        $session->offsetUnset('user');
        $auth->clearIdentity();
        return true;
    }

    /**
     * Autoriza ou não acesso 
     * @param  string $moduleName
     * @param  string $controllerName 
     * @param  string $actionName
     * @return bool 
     */
    public function authorize($moduleName, $controllerName, $actionName)
    {

        $auth = new AuthenticationService();
        $role = 'guest';
        if ($auth->hasIdentity()) {
            $session = $this->getServiceManager()->get('Session');
            $user = $session->offsetGet('user');
            $role = 'admin'; // $user->role  :D
        }

        $resource = $controllerName . '.' . $actionName;
        $acl = $this->getServiceManager()->get('Admin\Service\Builder')->build();
        if ($acl->isAllowed($role, $resource)) {
            return true;
        }
        return false;
    }
 
}