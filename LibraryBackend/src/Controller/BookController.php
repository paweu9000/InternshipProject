<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class BookController extends AbstractController
{
    #[Route('/', 'index')]
    public function index(): Response {
        $pageTitle = 'Homepage';

        return $this->render('index.html.twig', [
            'title' => $pageTitle,
            'content' => 'Index'
        ]);
    }

    #[Route('/all', 'all_books')]
    public function allBooks(): Response {
        $pageTitle = 'All Books';

        return $this->render('allbooks.html.twig', [
            'title' => $pageTitle,
            'api_endpoint' => 'get_all_books',
            'base_book_endpoint' => 'find_book_by_id'
        ]);
    }

    #[Route('/new', 'new_book')]
    public function newBook(): Response {
        $pageTitle = 'Add new book';

        return $this->render('newbook.html.twig', [
            'title' => $pageTitle,
            'api_endpoint' => 'add_book',
            'success_url' => 'get_book_by_id'
        ]);
    }

    #[Route('/{id}', 'get_book_by_id', requirements: ['id' => '\d+'])]
    public function getBookById(int $id) {
        $pageTitle = "Book no. " . $id;

        return $this->render('book.html.twig', [
            'title' => $pageTitle,
            'book_id' => $id,
            'api_endpoint' => 'find_book_by_id',
            'delete_redirect' => 'index',
            'delete_endpoint' => 'delete_book',
            'edit_endpoint' => 'update_book'
        ]);
    }
}