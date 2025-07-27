<?php

namespace App\Controller;

use App\Entity\Todo;
use App\Repository\TodoRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Response;

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
        return $this->json($data);
    }

    #[Route('/todos', name: 'api_todos_create', methods: ['POST'])]
    public function create(Request $request, EntityManagerInterface $em): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        if (empty($data['title'])) {
            return $this->json(['error' => 'Title is required'], Response::HTTP_BAD_REQUEST);
        }
        $todo = new Todo();
        $todo->setTitle($data['title']);
        $todo->setIsCompleted($data['isCompleted'] ?? false); // Default to false if not provided
        $em->persist($todo);
        $em->flush();
        return $this->json([
            'id' => $todo->getId(),
            'title' => $todo->getTitle(),
            'isCompleted' => $todo->isCompleted(),
        ], Response::HTTP_CREATED);
    }

    #[Route('/todos/{id}', name: 'api_todos_update', methods: ['PUT'])]
    public function update(Request $request, Todo $todo, EntityManagerInterface $em): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if (empty($data['title'])) {
            return $this->json(['error' => 'Title is required'], Response::HTTP_BAD_REQUEST);
        }
        $todo->setTitle($data['title']);
        $em->flush();
        return $this->json([
            'id' => $todo->getId(),
            'title' => $todo->getTitle(),
            'isCompleted' => $todo->isCompleted(),
        ]);
    }

    #[Route('/todos/{id}/toggle', name: 'api_todos_toggle_status', methods: ['PUT'])]
    public function toggleStatus(Todo $todo, EntityManagerInterface $em): JsonResponse
    {
        $todo->setIsCompleted(!$todo->isCompleted());
        $em->flush();
        return $this->json([
            'id' => $todo->getId(),
            'title' => $todo->getTitle(),
            'isCompleted' => $todo->isCompleted(),
        ]);
    }

    #[Route('/todos/{id}', name: 'api_todos_delete', methods: ['DELETE'])]
    public function delete(Todo $todo, EntityManagerInterface $em): JsonResponse
    {
        $em->remove($todo);
        $em->flush();
        return $this->json(['message' => 'Todo deleted successfully'], Response::HTTP_NO_CONTENT);

    }
}
