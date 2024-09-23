<?php

namespace App\Controller;

use App\Service\FruitService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class FruitController extends AbstractController
{
    private $fruitService;

    public function __construct(FruitService $fruitService)
    {
        $this->fruitService = $fruitService;
    }

    #[Route('/api/fruits', name: 'add_fruit', methods: ['POST'])]
    public function add(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $name = $data['name'];
        $quantity = $data['quantity'];
        $unit = $data['unit'];

        $result = $this->fruitService->add($name, $quantity, $unit);

        return $this->json($result);
    }

    #[Route('/api/fruits', name: 'list_fruits', methods: ['GET'])]
    public function list(Request $request): JsonResponse
    {
        $filters = $request->query->all();
        $fruits = $this->fruitService->list($filters);
        
        return $this->json($fruits);
    }

    #[Route('/api/fruit/search', name: 'search_fruit', methods: ['GET'])]
    public function search(Request $request): JsonResponse
    {
        $term = $request->query->get('term');
        if(!$term) {
            return $this->json(['error' => 'Search term is required'], 400);
        }

        $fruits = $this->fruitService->search($term);

        return $this->json($fruits);
    }

    #[Route('/api/fruit/{id}', name: 'get_fruit', methods: ['GET'])]
    public function get(string $id): JsonResponse
    {
        $fruit = $this->fruitService->get($id);

        return $this->json($fruit);
    }

    #[Route('/api/fruit/{id}', name: 'remove_fruit', methods: ['DELETE'])]
    public function remove(string $id): JsonResponse
    {
        $result = $this->fruitService->remove($id);

        return $this->json($result);
    }   
}
