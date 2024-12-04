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
}