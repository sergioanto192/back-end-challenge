<?php
namespace Tests\Unit;

use App\Controllers\ProductController;
use App\Services\Interfaces\ProductServiceInterface;
use App\Services\Interfaces\AuthServiceInterface;
use App\Handlers\ErrorHandler;
use App\Handlers\SuccessHandler;
use PHPUnit\Framework\TestCase;
use Mockery;

class ProductControllerTest extends TestCase
{
    private $mockProductService;
    private $mockAuthService;
    private $controller;

    public function setUp(): void
    {
       
       
        $this->mockProductService = Mockery::mock(ProductServiceInterface::class);
        $this->mockAuthService = Mockery::mock(AuthServiceInterface::class);

       
        $this->mockAuthService->shouldReceive('authenticate')
            ->andReturn(null);  // Simulating no authentication errors

        
        $this->controller = new ProductController(
            $this->mockProductService,
            $this->mockAuthService
        );
    }

    public function tearDown(): void
    {
        Mockery::close(); 
    }

    public function testIndexWithValidAuthentication()
    {
        
        $this->mockAuthService->shouldReceive('authenticate')->andReturn(null); // Nenhum erro de autenticação

        
        $this->mockProductService
            ->shouldReceive('getAll')
            ->once()
            ->with('name', 0)
            ->andReturn(['product1', 'product2']);

       
        $result = $this->controller->index();

      
        $this->assertEquals(['product1', 'product2'], $result);
    }

    public function testShowProductNotFound()
    {
       
        $this->mockAuthService->shouldReceive('authenticate')->andReturn(null);

       
        $this->mockProductService
            ->shouldReceive('getById')
            ->once()
            ->with(1, 0)
            ->andReturn(null);

        
        $result = $this->controller->show(1);

    
        $this->assertEquals(ErrorHandler::notFound('Produto não encontrado'), $result);
    }

    public function testStoreProductCreated()
    {
       
        $this->mockAuthService->shouldReceive('authenticate')->andReturn(null);

        
        $data = ['name' => 'Product 1', 'price' => 100, 'category_id' => 1];

       
        $this->mockProductService
            ->shouldReceive('create')
            ->once()
            ->with($data)
            ->andReturn(1);

        
        $result = $this->controller->store($data);

       
        $this->assertEquals(SuccessHandler::CreateOperationSuccess(1), $result);
    }

    public function testUpdateProductNotFound()
    {
       
        $this->mockAuthService->shouldReceive('authenticate')->andReturn(null);

      
        $data = ['name' => 'Updated Product', 'price' => 150];

       
        $this->mockProductService
            ->shouldReceive('getById')
            ->once()
            ->with(1, null)
            ->andReturn(null);

       
        $result = $this->controller->update(1, $data);

        
        $this->assertEquals(ErrorHandler::notFound('Produto não encontrado'), $result);
    }

    public function testDestroyProductNotFound()
    {
        
        $this->mockAuthService->shouldReceive('authenticate')->andReturn(null);

        
        $this->mockProductService
            ->shouldReceive('getById')
            ->once()
            ->with(1, null)
            ->andReturn(null);

      
        $result = $this->controller->destroy(1);

       
        $this->assertEquals(ErrorHandler::notFound('Produto não encontrado'), $result);
    }
}
