<?php
use App\Models\Product;
use App\Config\db;
use PHPUnit\Framework\TestCase;
use Mockery;

class ProductTest extends TestCase
{
    // Limpeza dos mocks após cada teste
    public function tearDown(): void
    {
        Mockery::close();
    }

    // Teste do método getAll
    public function testGetAll()
    {
        $mockDb = Mockery::mock('alias:App\Config\db');
        $mockStmt = Mockery::mock(PDOStatement::class);
        
        $mockDb->shouldReceive('connect')->once()->andReturnSelf();
        $mockDb->shouldReceive('query')->once()->andReturn($mockStmt);
        
        $mockStmt->shouldReceive('fetchAll')->once()->andReturn([['id' => 1, 'name' => 'Product 1']]);
        
        $result = Product::getAll('name');
        $this->assertCount(1, $result);
        $this->assertEquals('Product 1', $result[0]['name']);
    }

    // Teste do método findById
    public function testFindById()
    {
        $mockDb = Mockery::mock('alias:App\Config\db');
        $mockStmt = Mockery::mock(PDOStatement::class);

        $mockDb->shouldReceive('connect')->once()->andReturnSelf();
        $mockDb->shouldReceive('prepare')->once()->andReturn($mockStmt);
        $mockStmt->shouldReceive('execute')->once()->with([1]);
        $mockStmt->shouldReceive('fetch')->once()->andReturn(['id' => 1, 'name' => 'Product 1']);
        
        $result = Product::findById(1);
        $this->assertEquals('Product 1', $result['name']);
    }

    // Teste do método create
    public function testCreate()
    {
        $mockDb = Mockery::mock('alias:App\Config\db');
        $mockStmt = Mockery::mock(PDOStatement::class);

        $mockDb->shouldReceive('connect')->once()->andReturnSelf();
        $mockDb->shouldReceive('prepare')->once()->andReturn($mockStmt);
        
        // Usando Mockery::on() para fazer a correspondência dos parâmetros
        $mockStmt->shouldReceive('execute')->once()->with(Mockery::on(function($args) {
            return $args[0] === 'Product 1' && $args[1] === 'Description of product' && $args[2] === 100 && $args[3] === 1 && $args[4] === 10 && $args[5] === 2;
        }))->andReturn(true);
        
        $mockDb->shouldReceive('lastInsertId')->once()->andReturn(1);
        
        $result = Product::create('Product 1', 'Description of product', 100, 1, 10, 2);
        $this->assertEquals(1, $result);
    }

    // Teste do método update
    public function testUpdate()
    {
        $mockDb = Mockery::mock('alias:App\Config\db');
        $mockStmt = Mockery::mock(PDOStatement::class);

        $mockDb->shouldReceive('connect')->once()->andReturnSelf();
        $mockDb->shouldReceive('prepare')->once()->andReturn($mockStmt);
        $mockStmt->shouldReceive('execute')->once()->with(['Product 2', 1])->andReturn(true); 
        $data = ['name' => 'Product 2'];
        $result = Product::update(1, $data);
        $this->assertTrue($result); // Agora deve passar
    }

    // Teste do método delete
    public function testDelete()
    {
        $mockDb = Mockery::mock('alias:App\Config\db');
        $mockStmt = Mockery::mock(PDOStatement::class);
    
        $mockDb->shouldReceive('connect')->once()->andReturnSelf();
        $mockDb->shouldReceive('prepare')->once()->andReturn($mockStmt);
        $mockStmt->shouldReceive('execute')->once()->with([1])->andReturn(true); 
        $result = Product::delete(1);
        $this->assertTrue($result); 
    }
}
