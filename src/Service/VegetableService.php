<?php 

namespace App\Service;

use App\Entity\Vegetable;
use App\Service\ProductService;

class VegetableService extends ProductService
{
    public function add(string $name, float $quantity, $unit)
    {
        return $this->createProduct($name, $quantity, $unit, new Vegetable());
        
    }

    public function remove(string $id)
    {
        return $this->deleteProduct($id, Vegetable::class);
    }

    public function list(?array $filters = []): array
    {
        return $this->listAll(Vegetable::class, $filters);
    }

    public function get(string $id): array
    {
        return $this->listOne(Vegetable::class, $id);
    }

    public function search(string $term): array
    {
        return $this->searchProducts($term);
    }

    // More methods for Vegetable if needed
}