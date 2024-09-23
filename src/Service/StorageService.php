<?php

namespace App\Service;
use App\Service\FruitService;
use App\Service\VegetableService;
use Exception;
use Symfony\Component\HttpKernel\KernelInterface;

class StorageService
{
    protected string $request = '';
    private FruitService $fruitCollection;
    private VegetableService $vegetableCollection;
    private KernelInterface $kernel;

    public function __construct(FruitService $fruitCollection, VegetableService $vegetableCollection, KernelInterface $kernel)
    {
        $this->fruitCollection = $fruitCollection;
        $this->vegetableCollection = $vegetableCollection;
        $this->kernel = $kernel;
    }

    public function processJson() {
        try {
            $filePath = $this->kernel->getProjectDir() . '/request.json';
            $data = json_decode(file_get_contents($filePath), true);
    
            $fruits = array_filter($data, fn($item) => $item['type'] === 'fruit');
            $vegetables = array_filter($data, fn($item) => $item['type'] === 'vegetable');
    
            array_map(fn($fruit) => $this->fruitCollection->add($fruit['name'], $fruit['quantity'], $fruit['unit']), $fruits);
            array_map(fn($vegetable) => $this->vegetableCollection->add($vegetable['name'], $vegetable['quantity'], $vegetable['unit']), $vegetables);
    
            return [
                'fruits' => $this->fruitCollection->list(),
                'vegetables' => $this->vegetableCollection->list()
            ];

        } catch (Exception $e) {
            return [
                'error' => $e->getMessage(),
            ];
        }
    }
}
