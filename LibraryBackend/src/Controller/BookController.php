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

    //TODO: 
    //View books, add book, query books, delete books

    #[Route('/all', 'all_books')]
    public function allBooks(): Response {
        $pageTitle = 'All Books';

        return $this->render('allbooks.html.twig', [
            'title' => $pageTitle,
            'api_endpoint' => 'get_all_books'
        ]);
    }

    #[Route('/new', 'new_book')]
    public function newBook(): Response {
        $pageTitle = 'Add new book';

        return $this->render('newbook.html.twig', [
            'title' => $pageTitle,
            'api_endpoint' => 'add_book'
        ]);
    }
}