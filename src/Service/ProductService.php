<?php

namespace App\Service;

use Exception;
use App\Entity\Fruit;
use App\Entity\Product;
use App\Entity\Vegetable;
use Doctrine\ORM\EntityManagerInterface;
use App\Interface\ProductServiceInterface;
use App\Repository\ProductRepository;

abstract class ProductService implements ProductServiceInterface
{
    public const PRIMARY_UNIT = 'g';
    public const SECUNDARY_UNIT = 'kg';
    private ProductRepository $productRepository;
    protected $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
        $this->productRepository = $em->getRepository(Product::class);
    }

    protected function convertToGrams(int $quantity, string $unit): float
    {
        return ($unit === self::SECUNDARY_UNIT) ? $quantity * 1000 : $quantity;
    }

    protected function createProduct(string $nombre, float $quantity, string $unit, Product $product)
    {
        try {
            $quantity = $this->convertToGrams($quantity, $unit);

            $product->setName($nombre);
            $product->setQuantity($quantity);
            $product->setUnit(self::PRIMARY_UNIT);

            $this->em->persist($product);
            $this->em->flush();

            return [
                'status' => 'Resource added',
                'data' => $product
            ];
        } catch (Exception $e) {
            return [
                'error' => $e->getMessage(),
            ];
        }
    }

    public function listAll(string $product, ?array $filters = []): array
    {
        try {
            $queryBuilder = $this->em->getRepository($product)->createQueryBuilder('f');

            if(!empty($filters)){
                foreach($filters as $key => $value){
                    $queryBuilder->andWhere("f.$key = :$key")->setParameter($key, $value);
                }
            }

            $results = $queryBuilder->getQuery()->getResult();
            
            return $results 
            ? array_map(fn($result) => [
                    'id' => $result->getId(),
                    'name' => $result->getName(),
                    'quantity' => $result->getQuantity(),
                    'unit' => $result->getUnit()
                ], $results)
            : [
                'error' =>  'This resource was not found',
            ];
        } catch (Exception $e) {
            return [
                'error' => $e->getMessage(),
            ];
        }
    }

    public function listOne(string $product, string $id): array
    {
        try {
            $result = $this->em->getRepository($product)->find($id);
            
            return $result
            ?   [
                    'id' => $result->getId(),
                    'name' => $result->getName(),
                    'quantity' => $result->getQuantity(),
                    'unit' => $result->getUnit()
                ]
            :   [
                    'error' =>  'This resource was not found',
                ];
        } catch (Exception $e) {
            return [
                'error' => $e->getMessage(),
            ];
        }
    }

    public function searchProducts(string $term): array
    {
        try {
            $results = $this->productRepository->searchByName($term);
            
            return $results
                ? array_map(fn($result) => [
                    'id' => $result->getId(),
                    'name' => $result->getName(),
                    'quantity' => $result->getQuantity(),
                    'unit' => $result->getUnit()
                ], $results)
            :   [
                    'error' =>  'This resource was not found',
                ];
        } catch (Exception $e) {
            return [
                'error' => $e->getMessage(),
            ];
        }
    }

    public function deleteProduct (string $id, string $product)
    {
        try {
            $result = $this->em->getRepository($product)->find($id);
            $this->em->remove($result);
            $this->em->flush();

            return ['status' => 'Resource removed'];
        } catch (Exception $e) {
            return [
                'error' => $e->getMessage(),
            ];
        }
    }
}
