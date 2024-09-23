<?php

namespace App\Controller;

use ApiPlatform\Metadata\Get;
use App\Service\StorageService;
use ApiPlatform\Metadata\ApiResource;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[ApiResource(
    operations: [
        new Get(
            uriTemplate: '/api/process-json',
            controller: ProcessController::class,
            name: 'process_json'
        )
    ]
)]
class ProcessController extends AbstractController
{
    private StorageService $storageService;

    public function __construct(StorageService $storageService) {
        $this->storageService = $storageService;
    }

    #[Route('/api/process-json', name: 'process_json', methods: ['GET'])]
    public function processJson(){
        $result = $this->storageService->processJson();

        return new JsonResponse($result);
    }
}
