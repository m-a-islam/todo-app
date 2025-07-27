<?php

namespace App\Controller;

use App\Repository\TodoRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api')]
final class TodoController extends AbstractController
{
    #[Route('/todos', name: 'api_todos_index', methods: ['GET'])]
    public function index(TodoRepository $todoRepository): JsonResponse
    {
        // Use the repository to find all Todo items
        $todos = $todoRepository->findAll();
        $data = [];
        foreach ($todos as $todo) {
            $data[] = [
                'id' => $todo->getId(),
                'title' => $todo->getTitle(),
                'isCompleted' => $todo->isCompleted(), // 'make:entity' creates a getter named isIsCompleted() for a boolean
            ];
        }

        // The 'json()' method is a shortcut to create a JsonResponse
        // It automatically converts the PHP objects to a JSON array

        return $this->json($data);
    }
}
