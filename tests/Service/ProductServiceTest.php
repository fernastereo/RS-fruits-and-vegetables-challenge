<?php

namespace App\Tests\Service;

use App\Entity\Product;
use App\Repository\ProductRepository;
use App\Service\ProductService;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;
use Mockery;

class ProductServiceTest extends TestCase
{
    protected $emMock;
    protected $productRepositoryMock;
    protected $productServiceMock;

    protected function setUp(): void
    {
        $this->emMock = Mockery::mock(EntityManagerInterface::class);
        $this->productRepositoryMock = Mockery::mock(ProductRepository::class);
        $this->emMock->shouldReceive('getRepository')
                    ->with(Product::class)
                    ->andReturn($this->productRepositoryMock);
        
        $this->productServiceMock = Mockery::mock(ProductService::class, [$this->emMock])->makePartial();
    }

    public function testCreateProduct()
    {
        $productMock = Mockery::mock(Product::class);

        $productMock->shouldReceive('setName')->once()->with('Apple');
        $productMock->shouldReceive('setQuantity')->once()->with(1000); 
        $productMock->shouldReceive('setUnit')->once()->with('g');

        $this->emMock->shouldReceive('persist')->once()->with($productMock);
        $this->emMock->shouldReceive('flush')->once();

        $result = $this->productServiceMock->createProduct('Apple', 1, 'kg', $productMock);

        $this->assertArrayHasKey('status', $result);
        $this->assertEquals('Resource added', $result['status']);
    }

    public function testListAll()
    {
        $productMock = Mockery::mock(Product::class);
        $productMock->shouldReceive('getId')->andReturn(1);
        $productMock->shouldReceive('getName')->andReturn('Apple');
        $productMock->shouldReceive('getQuantity')->andReturn(1000);
        $productMock->shouldReceive('getUnit')->andReturn('g');

        $queryBuilderMock = Mockery::mock('Doctrine\ORM\QueryBuilder');
        $queryBuilderMock->shouldReceive('andWhere')->andReturnSelf();
        $queryBuilderMock->shouldReceive('setParameter')->andReturnSelf();

        $queryMock = Mockery::mock('Doctrine\ORM\Query');
        $queryMock->shouldReceive('getResult')->andReturn([$productMock]);

        $queryBuilderMock->shouldReceive('getQuery')->andReturn($queryMock);

        $this->productRepositoryMock->shouldReceive('createQueryBuilder')->andReturn($queryBuilderMock);

        $result = $this->productServiceMock->listAll(Product::class);

        $this->assertIsArray($result);
        $this->assertCount(1, $result);
        $this->assertEquals('Apple', $result[0]['name']);
    }

    public function testListOne()
    {
        $productMock = Mockery::mock(Product::class);
        $productMock->shouldReceive('getId')->andReturn(1);
        $productMock->shouldReceive('getName')->andReturn('Apple');
        $productMock->shouldReceive('getQuantity')->andReturn(1000);
        $productMock->shouldReceive('getUnit')->andReturn('g');

        $this->productRepositoryMock->shouldReceive('find')->with(1)->andReturn($productMock);

        $result = $this->productServiceMock->listOne(Product::class, 1);

        $this->assertArrayHasKey('name', $result);
        $this->assertEquals('Apple', $result['name']);
    }

    public function testSearchProducts()
    {
        $productMock = Mockery::mock(Product::class);
        $productMock->shouldReceive('getId')->andReturn(1);
        $productMock->shouldReceive('getName')->andReturn('Apple');
        $productMock->shouldReceive('getQuantity')->andReturn(1000);
        $productMock->shouldReceive('getUnit')->andReturn('g');

        $this->productRepositoryMock->shouldReceive('searchByName')->with('Apple')->andReturn([$productMock]);

        $result = $this->productServiceMock->searchProducts('Apple');

        $this->assertIsArray($result);
        $this->assertCount(1, $result);
        $this->assertEquals('Apple', $result[0]['name']);
    }

    public function testDeleteProduct()
    {
        $productMock = Mockery::mock(Product::class);
        
        $this->productRepositoryMock->shouldReceive('find')->with(1)->andReturn($productMock);
        $this->emMock->shouldReceive('remove')->with($productMock)->once();
        $this->emMock->shouldReceive('flush')->once();

        $result = $this->productServiceMock->deleteProduct(1, Product::class);

        $this->assertArrayHasKey('status', $result);
        $this->assertEquals('Resource removed', $result['status']);
    }

    protected function tearDown(): void
    {
        Mockery::close();
    }
}
