<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2014 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Books\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Books\Model\Books;
use Books\Model\BooksTable;
use Books\Form\BookForm;

/**
 * Controlador principal do módulo Books
 * @group Controller
 */
class IndexController extends AbstractActionController
{

    /*
     * TableGateway 
     */
    protected $booksTable;

    /*
     * Somente redireciona pra action list
     */
    public function indexAction()
    {               
        return $this->redirect()->toUrl('/books/list/title');
    }

    /*
     * Listagem de livros, recebe parametro para ordenação
     */
    public function listAction()
    {               
        $order = $this->params()->fromRoute('id', false);

        return new ViewModel(array(
            'books' => $this->getBooksTable()->filterAll($order),
        ));
    }

    /*
     * Adicionar livro
     */
    public function addAction()
    {        
        $form = new BookForm();
        $form->get('submit')->setValue('Add');

        $request = $this->getRequest();

        if ($request->isPost()) {

            $book = new Books();

            $form->setInputFilter($book->getInputFilter());
            $form->setData($request->getPost());

            if ($form->isValid()) {                

                $book->exchangeArray($form->getData());
                $this->getBooksTable()->saveBooks($book);

                return $this->redirect()->toRoute('books');
            }
        }
        return array('form' => $form);
        
    }

    /*
     * Editar livro
     */
    public function editAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) {
            return $this->redirect()->toRoute('books', array(
                'action' => 'add'
            ));
        }
        $book = $this->getBooksTable()->getBooks($id);

        $form  = new BookForm();
        $form->bind($book);
        $form->get('submit')->setAttribute('value', 'Save');

        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setInputFilter($book->getInputFilter());
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $this->getBooksTable()->saveBooks($form->getData());

                return $this->redirect()->toRoute('books');
            }
        }

        return array(
            'id' => $id,
            'form' => $form,
        );
    }

    /*
     * Remover livro
     */
    public function deleteAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) {
            return $this->redirect()->toRoute('books');
        }

        $request = $this->getRequest();
        if ($request->isPost()) {
            $del = $request->getPost('del', 'No');

            if ($del == 'Yes') {

                $id = (int) $request->getPost('id');
                $this->getBooksTable()->deleteBooks($id);
            }

            return $this->redirect()->toRoute('books');
        }

        return array(
            'id'    => $id,
            'book' => $this->getBooksTable()->getBooks($id)
        );
    }

    /*
     * Retorna TableGateway do modelo \Books\Model\BooksTabçe
     */
    public function getBooksTable()
    {
        if (!$this->booksTable) {
            $sm = $this->getServiceLocator();
            $this->booksTable = $sm->get('Books\Model\BooksTable');
        }
        return $this->booksTable;
    }

}