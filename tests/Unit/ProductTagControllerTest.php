<?php
namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\Controllers\ProductTagController;
use App\Services\Interfaces\ProductTagServiceInterface;
use App\Services\Interfaces\AuthServiceInterface;
use Mockery;

class ProductTagControllerTest extends TestCase
{
    protected $controller;
    protected $mockProductTagService;
    protected $mockAuthService;

    //isntancia a classe injetando os objetos
    protected function setUp(): void
    {
        
        $this->mockProductTagService = Mockery::mock(ProductTagServiceInterface::class);
        $this->mockAuthService = Mockery::mock(AuthServiceInterface::class);

       
        $this->controller = new ProductTagController(
            $this->mockProductTagService,
            $this->mockAuthService
        );
    }

   
    public function testMethodsReturnAuthErrorIfAuthenticationFails()
    {
      
        $this->mockAuthService->shouldReceive('authenticate')->andReturn('Authentication failed');
        
       
        $response = $this->controller->link(1, [1, 2]);
        $this->assertEquals('Authentication failed', $response);

       
        $response = $this->controller->unlink(1, 1);
        $this->assertEquals('Authentication failed', $response);

       
        $response = $this->controller->getTagsByProduct(1);
        $this->assertEquals('Authentication failed', $response);

       
        $response = $this->controller->getProductsByTag(1);
        $this->assertEquals('Authentication failed', $response);
    }

    
    public function testLinkTagsToProduct()
    {
    
        $this->mockAuthService->shouldReceive('authenticate')->andReturn(null);

       
        $this->mockProductTagService->shouldReceive('linkTagsToProduct')
            ->with(1, [1, 2])
            ->once();

       
        $this->mockProductTagService->shouldReceive('linkTagsToProduct')->andReturn('success');

        $response = $this->controller->link(1, [1, 2]);

        $this->assertEquals('success', $response);
    }

 
    public function testUnlinkTagFromProduct()
    {
        
        $this->mockAuthService->shouldReceive('authenticate')->andReturn(null);

        
        $this->mockProductTagService->shouldReceive('unlinkTagFromProduct')
            ->with(1, 1)
            ->once();

        
        $this->mockProductTagService->shouldReceive('unlinkTagFromProduct')->andReturn('success');

        $response = $this->controller->unlink(1, 1);

        $this->assertEquals('success', $response);
    }


    public function testGetTagsByProduct()
    {
       
        $this->mockAuthService->shouldReceive('authenticate')->andReturn(null);

       
        $this->mockProductTagService->shouldReceive('getTagsByProduct')
            ->with(1)
            ->once()
            ->andReturn(['tag1', 'tag2']); 

        $response = $this->controller->getTagsByProduct(1);

        $this->assertEquals(['tag1', 'tag2'], $response);
    }

 
    public function testGetProductsByTag()
    {
        
        $this->mockAuthService->shouldReceive('authenticate')->andReturn(null);

        
        $this->mockProductTagService->shouldReceive('getProductsByTag')
            ->with(1)
            ->once()
            ->andReturn(['product1', 'product2']); // Example response

        $response = $this->controller->getProductsByTag(1);

        $this->assertEquals(['product1', 'product2'], $response);
    }

    protected function tearDown(): void
    {
        Mockery::close();
    }
}