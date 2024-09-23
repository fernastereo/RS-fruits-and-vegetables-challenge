<?php

namespace App\Tests\Service;

use App\Service\FruitService;
use App\Entity\Fruit;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;

class FruitServiceTest extends TestCase
{
    private $entityManagerMock;
    private $productRepositoryMock;
    private $fruitService;

    protected function setUp(): void
    {
        $this->entityManagerMock = $this->createMock(EntityManagerInterface::class);
        $this->productRepositoryMock = $this->createMock(ProductRepository::class);

        $this->entityManagerMock->method('getRepository')
            ->willReturn($this->productRepositoryMock);

        $this->fruitService = new FruitService($this->entityManagerMock);
    }

    public function testAdd()
    {
        $name = 'Apple';
        $quantity = 100;
        $unit = 'g';

        $this->entityManagerMock->expects($this->once())
            ->method('persist');
        $this->entityManagerMock->expects($this->once())
            ->method('flush');

        $result = $this->fruitService->add($name, $quantity, $unit);

        $this->assertArrayHasKey('status', $result);
        $this->assertEquals('Resource added', $result['status']); 
    }

    public function testRemove()
    {
        $id = '123';
        $fruitMock = $this->createMock(Fruit::class);

        $this->productRepositoryMock->expects($this->once())
            ->method('find')
            ->with($id)
            ->willReturn($fruitMock);

        $this->entityManagerMock->expects($this->once())
            ->method('remove')
            ->with($fruitMock); 
        $this->entityManagerMock->expects($this->once())
            ->method('flush');

        $result = $this->fruitService->remove($id);

        $this->assertArrayHasKey('status', $result);
        $this->assertEquals('Resource removed', $result['status']); 
    }


    public function testGet()
    {
        $id = 123;
        $fruitMock = $this->createMock(Fruit::class);

        $fruitMock->method('getId')->willReturn($id);
        $fruitMock->method('getName')->willReturn('Apple');
        $fruitMock->method('getQuantity')->willReturn(100.00);
        $fruitMock->method('getUnit')->willReturn('g');

        $this->productRepositoryMock->expects($this->once())
            ->method('find')
            ->with($id)
            ->willReturn($fruitMock);

        $result = $this->fruitService->get($id);

        $this->assertArrayHasKey('id', $result);
        $this->assertEquals($id, $result['id']);
        $this->assertEquals('Apple', $result['name']);
        $this->assertEquals(100, $result['quantity']);
        $this->assertEquals('g', $result['unit']);
    }

    public function testSearch()
    {
        $term = 'Apple';
        $fruit = new Fruit();
        $fruit->setName($term);

        $this->productRepositoryMock->expects($this->once())
            ->method('searchByName')
            ->with($term)
            ->willReturn([$fruit]);

        $result = $this->fruitService->search($term);

        $this->assertIsArray($result);
        $this->assertCount(1, $result);
        $this->assertEquals('Apple', $result[0]['name']);
    }
}