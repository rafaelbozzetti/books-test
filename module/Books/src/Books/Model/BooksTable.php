<?php
namespace Books\Model;

use Zend\Db\TableGateway\TableGateway;
use Zend\Authentication\AuthenticationService;

/**
 * @group Model
 */
class BooksTable
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

    public function filterAll($order = false)
    {

        $auth = new AuthenticationService();
        $identity = $auth->getIdentity(); // username

        $order = ($order ? $order : 'id');

        $sqlSelect = $this->tableGateway->getSql()->select();

        $sqlSelect->columns(array('*'));
        $sqlSelect->join('users', 'users.id = books.user_id', array(), 'left')
                  ->where("users.username = '$identity' ")
                  ->order("books.$order ASC");

        $resultSet = $this->tableGateway->selectWith($sqlSelect);
        return $resultSet;
    }

    public function getBooks($id)
    {
        $id  = (int) $id;
        $rowset = $this->tableGateway->select(array('id' => $id));
        $row = $rowset->current();
        if (!$row) {
            throw new \Exception("Could not find row $id");
        }
        return $row;
    }

    public function saveBooks(Books $book)
    {
        $data = array(
            'title' => $book->title,
            'user_id' => 1, // userid fixo paleativamente
            'description'  => $book->description,
            'author'  => $book->author,
        );

        $id = (int)$book->id;
        if ($id == 0) {
            $this->tableGateway->insert($data);
        } else {
            if ($this->getBooks($id)) {
                $this->tableGateway->update($data, array('id' => $id));
            } else {
                throw new \Exception('Form id does not exist');
            }
        }
    }

    public function deleteBooks($id)
    {
        $this->tableGateway->delete(array('id' => $id));
    }
}
