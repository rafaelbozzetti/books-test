<?php
namespace BooksTest\Model;

use Books\Model\BooksTable;
use Books\Model\Books;
use Zend\Db\ResultSet\ResultSet;
use PHPUnit_Framework_TestCase;

/**
 * @group Model
 */
class BooksTableTest extends PHPUnit_Framework_TestCase
{
    public function testFetchAllReturnsAllBookss()
    {
        $resultSet        = new ResultSet();
        $mockTableGateway = $this->getMock('Zend\Db\TableGateway\TableGateway',
                                           array('select'), array(), '', false);
        $mockTableGateway->expects($this->once())
                         ->method('select')
                         ->with()
                         ->will($this->returnValue($resultSet));

        $booksTable = new BooksTable($mockTableGateway);

        $this->assertSame($resultSet, $booksTable->fetchAll());
    }



    public function testCanRetrieveAnBooksByItsId()
    {
        $book = new Books();
        $book->exchangeArray(array('id'     => 123,
                                   'title' => 'The Military Wives',
                                   'description' => 'The Military Wives',
                                   'author'  => 'Paulo Coelho'));

        $resultSet = new ResultSet();
        $resultSet->setArrayObjectPrototype(new Books());
        $resultSet->initialize(array($book));

        $mockTableGateway = $this->getMock('Zend\Db\TableGateway\TableGateway', array('select'), array(), '', false);
        $mockTableGateway->expects($this->once())
                         ->method('select')
                         ->with(array('id' => 123))
                         ->will($this->returnValue($resultSet));

        $bookTable = new BooksTable($mockTableGateway);

        $this->assertSame($book, $bookTable->getBooks(123));
    }

    public function testCanDeleteAnBooksByItsId()
    {
        $mockTableGateway = $this->getMock('Zend\Db\TableGateway\TableGateway', array('delete'), array(), '', false);
        $mockTableGateway->expects($this->once())
                         ->method('delete')
                         ->with(array('id' => 123));

        $bookTable = new BooksTable($mockTableGateway);
        $bookTable->deleteBooks(123);
    }

    public function testSaveBooksWillInsertNewBooksIfTheyDontAlreadyHaveAnId()
    {
        $bookData = array('title' => 'In My Dreams',
                          'user_id' => 1, // na mao, paleativamente 
                          'description' => 'The Military Wives', 
                          'author' => 'Paulo Coelho');

        $book     = new Books();
        $book->exchangeArray($bookData);

        $mockTableGateway = $this->getMock('Zend\Db\TableGateway\TableGateway', array('insert'), array(), '', false);
        $mockTableGateway->expects($this->once())
                         ->method('insert')
                         ->with($bookData);

        $bookTable = new BooksTable($mockTableGateway);
        $bookTable->saveBooks($book);
    }

    public function testSaveBooksWillUpdateExistingBooksIfTheyAlreadyHaveAnId()
    {
        $bookData = array('id' => 123, 
                          'title' => 'In My Dreams',
                          'description' => 'The Military Wives',
                          'author' => 'Paulo Coelho');

        $book = new Books();
        $book->exchangeArray($bookData);

        $resultSet = new ResultSet();
        $resultSet->setArrayObjectPrototype(new Books());
        $resultSet->initialize(array($book));

        $mockTableGateway = $this->getMock('Zend\Db\TableGateway\TableGateway',
                                           array('select', 'update'), array(), '', false);
        $mockTableGateway->expects($this->once())
                         ->method('select')
                         ->with(array('id' => 123))
                         ->will($this->returnValue($resultSet));

        $mockTableGateway->expects($this->once())
                         ->method('update')
                         ->with(array('title' => 'In My Dreams',
                                      'user_id' => 1,
                                      'description' => 'The Military Wives', 
                                      'author' => 'Paulo Coelho'),
                                array('id' => 123));

        $bookTable = new BooksTable($mockTableGateway);
        $bookTable->saveBooks($book);
    }

    public function testExceptionIsThrownWhenGettingNonexistentBooks()
    {
        $resultSet = new ResultSet();
        $resultSet->setArrayObjectPrototype(new Books());
        $resultSet->initialize(array());

        $mockTableGateway = $this->getMock('Zend\Db\TableGateway\TableGateway', array('select'), array(), '', false);
        $mockTableGateway->expects($this->once())
                         ->method('select')
                         ->with(array('id' => 123))
                         ->will($this->returnValue($resultSet));

        $bookTable = new BooksTable($mockTableGateway);

        try
        {
            $bookTable->getBooks(123);
        }
        catch (\Exception $e)
        {
            $this->assertSame('Could not find row 123', $e->getMessage());
            return;
        }

        $this->fail('Expected exception was not thrown');
    }
    
}
