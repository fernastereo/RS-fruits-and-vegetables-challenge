<?php

namespace App\Tests\Service;

use App\Service\StorageService;
use App\Service\FruitService;
use App\Service\VegetableService;
use Symfony\Component\HttpKernel\KernelInterface;
use PHPUnit\Framework\TestCase;
use Mockery;

class StorageServiceTest extends TestCase
{
    protected function tearDown(): void
    {
        Mockery::close();
    }

    public function testProcessJsonSuccessfully()
    {
        // Mocks
        $fruitServiceMock = $this->createMock(FruitService::class);
        $vegetableServiceMock = $this->createMock(VegetableService::class);

        $fruitsInput = [
            ['name' => 'Apple', 'quantity' => 100, 'unit' => 'g']
        ];

        $vegetablesInput = [
            ['name' => 'Carrot', 'quantity' => 200, 'unit' => 'g']
        ];

        $fruitServiceMock->expects($this->once())
            ->method('add')
            ->with('Apple', 100, 'g');

        $vegetableServiceMock->expects($this->once())
            ->method('add')
            ->with('Carrot', 200, 'g');

        $fruitServiceMock->method('list')->willReturn($fruitsInput);
        $vegetableServiceMock->method('list')->willReturn($vegetablesInput);
        
        foreach ($fruitsInput as $fruit) {
            $fruitServiceMock->add($fruit['name'], $fruit['quantity'], $fruit['unit']);
        }

        foreach ($vegetablesInput as $vegetable) {
            $vegetableServiceMock->add($vegetable['name'], $vegetable['quantity'], $vegetable['unit']);
        }

        $result = [
            'fruits' => $fruitServiceMock->list(),
            'vegetables' => $vegetableServiceMock->list()
        ];

        $this->assertIsArray($result);
        $this->assertArrayHasKey('fruits', $result);
        $this->assertArrayHasKey('vegetables', $result);
        $this->assertCount(1, $result['fruits']);
        $this->assertCount(1, $result['vegetables']);
        $this->assertEquals('Apple', $result['fruits'][0]['name']);
        $this->assertEquals('Carrot', $result['vegetables'][0]['name']);
    }
}
