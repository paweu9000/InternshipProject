<?php

namespace App\Controller;

use App\Service\BookService;
use App\Model\BookDto;
use App\Service\JWTService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/book')]
class BookRestController extends AbstractController
{
    private BookService $bookService;
    private JWTService $jwtService;

    public function __construct(BookService $bookService, JWTService $jwtService) {
        $this->bookService = $bookService;
        $this->jwtService = $jwtService;
    }

    #[Route(path: '/{id}', name: 'find_book_by_id', methods: ['GET'])]
    public function findById(int $id, Request $request): JsonResponse {
        $this->jwtService->verifyToken($request);
        $book = $this->bookService->findById($id);

        return $this->json($book);
    }

    #[Route(name: 'add_book', methods: ['POST'])]
    public function addBook(
        #[MapRequestPayload] BookDto $bookDto,
        Request $request
    ): JsonResponse
    {
        $this->jwtService->verifyToken($request);
        $book = $this->bookService->saveBook($bookDto);
        return $this->json($book);
    }

    #[Route(path: '/{id}', name: 'update_book', methods: ['PUT'])]
    public function updateBook(
        #[MapRequestPayload] BookDto $bookDto,
        int $id,
        Request $request
    ): JsonResponse
    {
        $this->jwtService->verifyToken($request);
        $updatedBook = $this->bookService->updateBook($bookDto, $id);

        return $this->json($updatedBook);
    }

    #[Route(name: 'get_all_books', methods: ['GET'])]
    public function getAllBook(Request $request): JsonResponse
    {
        $this->jwtService->verifyToken($request);
        $books = $this->bookService->getAllBooks();
        return $this->json($books);
    }

    #[Route(path: '/{id}', name: 'delete_book', methods: ['DELETE'])]
    public function deleteBook(int $id, Request $request): JsonResponse 
    {
        $this->jwtService->verifyToken($request);
        $this->bookService->deleteBook($id);

        return new JsonResponse(null, 204);
    }
}
