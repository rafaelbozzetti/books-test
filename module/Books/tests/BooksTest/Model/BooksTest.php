<?php
namespace BooksTest\Model;

use Books\Model\Books;
use PHPUnit_Framework_TestCase;

/**
 * @group Model
 */
class BooksTest extends PHPUnit_Framework_TestCase
{
    public function testBooksInitialState()
    {
        $book = new Books();
        $this->assertNull($book->id, '"id" should initially be null');
        $this->assertNull($book->title, '"title" should initially be null');
        $this->assertNull($book->description, 'description should initially be null');
        $this->assertNull($book->author, 'Paulo Coelho');
    }

    public function testExchangeArraySetsPropertiesCorrectly()
    {
        $book = new Books();
        $data  = array('id'     => 123,
                       'title'  => 'some title',
                       'description' => 'some description',
                       'author' => 'some author qualquer');

        $book->exchangeArray($data);
        
        $this->assertSame($data['id'], $book->id, '"id" não foi definido corretamente');
        $this->assertSame($data['title'], $book->title, '"title" não foi definido corretamente');
        $this->assertSame($data['description'], $book->description, '"description" não foi definido corretamente');
        $this->assertSame($data['author'], $book->author, '"author" não foi definido corretamente');
    }

    public function testExchangeArraySetsPropertiesToNullIfKeysAreNotPresent()
    {
        $book = new Books();
        $book->exchangeArray(array('id'     => 123,
                                   'title'  => 'some title',
                                   'description' => 'some description',
                                   'author' => 'Paulo Coelho'));
        $book->exchangeArray(array());

        
        $this->assertNull($book->id, '"id" should have defaulted to null');
        $this->assertNull($book->title, '"title" should have defaulted to null');
        $this->assertNull($book->description, 'description should have defaulted to null');
        $this->assertNull($book->author, 'Paulo Coelho');
    }
}
