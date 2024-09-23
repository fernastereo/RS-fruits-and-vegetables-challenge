<?php

namespace App\Tests\Service;

use App\Service\VegetableService;
use App\Entity\Vegetable;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;

class VegetableServiceTest extends TestCase
{
    private $entityManagerMock;
    private $productRepositoryMock;
    private $vegetableService;

    protected function setUp(): void
    {
        $this->entityManagerMock = $this->createMock(EntityManagerInterface::class);
        $this->productRepositoryMock = $this->createMock(ProductRepository::class);

        $this->entityManagerMock->method('getRepository')
            ->willReturn($this->productRepositoryMock);

        $this->vegetableService = new VegetableService($this->entityManagerMock);
    }

    public function testAdd()
    {
        $name = 'Onion';
        $quantity = 100;
        $unit = 'g';

        $this->entityManagerMock->expects($this->once())
            ->method('persist');
        $this->entityManagerMock->expects($this->once())
            ->method('flush');

        $result = $this->vegetableService->add($name, $quantity, $unit);

        $this->assertArrayHasKey('status', $result);
        $this->assertEquals('Resource added', $result['status']); 
    }

    public function testRemove()
    {
        $id = '123';
        $vegetableMock = $this->createMock(Vegetable::class);

        $this->productRepositoryMock->expects($this->once())
            ->method('find')
            ->with($id)
            ->willReturn($vegetableMock);

        $this->entityManagerMock->expects($this->once())
            ->method('remove')
            ->with($vegetableMock); 
        $this->entityManagerMock->expects($this->once())
            ->method('flush');

        $result = $this->vegetableService->remove($id);

        $this->assertArrayHasKey('status', $result);
        $this->assertEquals('Resource removed', $result['status']); 
    }


    public function testGet()
    {
        $id = 123;
        $vegetableMock = $this->createMock(Vegetable::class);

        $vegetableMock->method('getId')->willReturn($id);
        $vegetableMock->method('getName')->willReturn('Onion');
        $vegetableMock->method('getQuantity')->willReturn(100.00);
        $vegetableMock->method('getUnit')->willReturn('g');

        $this->productRepositoryMock->expects($this->once())
            ->method('find')
            ->with($id)
            ->willReturn($vegetableMock);

        $result = $this->vegetableService->get($id);

        $this->assertArrayHasKey('id', $result);
        $this->assertEquals($id, $result['id']);
        $this->assertEquals('Onion', $result['name']);
        $this->assertEquals(100, $result['quantity']);
        $this->assertEquals('g', $result['unit']);
    }

    public function testSearch()
    {
        $term = 'Onion';
        $vegetable = new Vegetable();
        $vegetable->setName($term);

        $this->productRepositoryMock->expects($this->once())
            ->method('searchByName')
            ->with($term)
            ->willReturn([$vegetable]);

        $result = $this->vegetableService->search($term);

        $this->assertIsArray($result);
        $this->assertCount(1, $result);
        $this->assertEquals('Onion', $result[0]['name']);
    }
}