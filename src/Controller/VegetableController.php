<?php

namespace App\Controller;

use App\Service\VegetableService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class VegetableController extends AbstractController
{
    private $vegetableService;

    public function __construct(VegetableService $vegetableService)
    {
        $this->vegetableService = $vegetableService;
    }

    #[Route('/api/vegetables', name: 'add_vegetable', methods: ['POST'])]
    public function add(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $name = $data['name'];
        $quantity = $data['quantity'];
        $unit = $data['unit'];

        $result = $this->vegetableService->add($name, $quantity, $unit);

        return $this->json($result);
    }

    #[Route('/api/vegetables', name: 'list_vegetables', methods: ['GET'])]
    public function list(Request $request): JsonResponse
    {
        $filters = $request->query->all();
        $vegetables = $this->vegetableService->list($filters);
        
        return $this->json($vegetables);
    }

    #[Route('/api/vegetable/search', name: 'search_vegetable', methods: ['GET'])]
    public function search(Request $request): JsonResponse
    {
        $term = $request->query->get('term');
        if(!$term) {
            return $this->json(['error' => 'Search term is required'], 400);
        }

        $fruits = $this->vegetableService->search($term);

        return $this->json($fruits);
    }

    #[Route('/api/vegetable/{id}', name: 'get_vegetable', methods: ['GET'])]
    public function get(string $id): JsonResponse
    {
        $vegetable = $this->vegetableService->get($id);

        return $this->json($vegetable);
    }

    #[Route('/api/vegetable/{id}', name: 'remove_vegetable', methods: ['DELETE'])]
    public function remove(string $id): JsonResponse
    {
        $result = $this->vegetableService->remove($id);

        return $this->json($result);
    }   
}
