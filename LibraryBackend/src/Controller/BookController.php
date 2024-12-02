<?php

namespace App\Controller;

use App\Service\BookService;
use App\Model\BookDto;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/book', 'BookController')]
class BookController extends AbstractController
{
    private BookService $bookService;

    public function __construct(BookService $bookService) {
        $this->bookService = $bookService;
    }

    #[Route(name: 'add_book', methods: ['POST'])]
    public function addBook(
        #[MapRequestPayload] BookDto $bookDto
    ): JsonResponse
    {
        $book = $this->bookService->saveBook($bookDto);
        return $this->json($book);
    }

    #[Route(name: 'get_all_books', methods: ['GET'])]
    public function getAllBook(): JsonResponse
    {
        $books = $this->bookService->getAllBooks();
        return $this->json($books);
    }

    #[Route(path: '/{id}', name: 'delete_book', methods: ['DELETE'])]
    public function deleteBook(int $id): JsonResponse 
    {
        $this->bookService->deleteBook($id);

        return new JsonResponse(null, 204);
    }
}
