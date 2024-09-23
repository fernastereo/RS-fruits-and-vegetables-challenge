<?php 

namespace App\Interface;

interface ProductServiceInterface
{
    public function add(string $name, float $quantity, string $unit);
    public function remove(string $id);
    public function list(?array $filters = []): array;
    public function get(string $id): array;
    public function search(string $query): array; 
}