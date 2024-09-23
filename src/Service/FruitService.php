<?php 

namespace App\Service;

use App\Entity\Fruit;
use App\Service\ProductService;

class FruitService extends ProductService
{
    public function add(string $name, float $quantity, $unit)
    {
        return $this->createProduct($name, $quantity, $unit, new Fruit());
        
    }

    public function remove(string $id)
    {
        return $this->deleteProduct($id, Fruit::class);
    }

    public function list(?array $filters = []): array
    {
        return $this->listAll(Fruit::class, $filters);
    }

    public function get(string $id): array
    {
        return $this->listOne(Fruit::class, $id);
    }

    public function search(string $term): array
    {
        return $this->searchProducts($term);
    }

    // More methods for Fruit if needed
}