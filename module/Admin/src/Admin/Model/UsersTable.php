<?php

namespace Admin\Model;

use Zend\Db\TableGateway\TableGateway;
use Zend\Authentication\Result;
use Zend\Authentication\AuthenticationService;
use Zend\Authentication\Adapter\DbTable as AuthAdapter;

/*
 * @group Model
 */
class UsersTable
{
    protected $tableGateway;

    public function __construct(TableGateway $tableGateway)
    {
        $this->tableGateway = $tableGateway;
    }

    public function fetchAll()
    {
        $resultSet = $this->tableGateway->select();
        return $resultSet;
    }

    public function authenticate($user)
    {

        $rowset = $this->tableGateway->select(array('username' => $user->getUsername()) );
        $row = $rowset->current();

        $session = $this->getServiceManager()->get('Session');
        $session->offsetSet('user', $row);

        return true;
    }

}
