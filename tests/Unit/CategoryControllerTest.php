<?php 
namespace Tests\Unit;

use App\Controllers\CategoryController;
use App\Services\Interfaces\CategoryServiceInterface;
use App\Services\Interfaces\AuthServiceInterface;
use Mockery;
use PHPUnit\Framework\TestCase;

class CategoryControllerTest extends TestCase
{
    protected $mockCategoryService;
    protected $mockAuthService;
    protected $controller;

    // Set up the mocks and controller
    public function setUp(): void
    {
        parent::setUp();

        // Mock CategoryServiceInterface and AuthServiceInterface
        $this->mockCategoryService = Mockery::mock(CategoryServiceInterface::class);
        $this->mockAuthService = Mockery::mock(AuthServiceInterface::class);

        // Mock authentication to return null (success) or an error message (failure)
        $this->mockAuthService->shouldReceive('authenticate')->andReturn(null);

        // Instantiate the controller with the mocked dependencies
        $this->controller = new CategoryController(
            $this->mockCategoryService,
            $this->mockAuthService
        );
    }

    // Test that the index method returns all categories
    public function testIndexReturnsCategories()
    {
        $this->mockCategoryService
            ->shouldReceive('getAllCategories')
            ->once()
            ->andReturn(['Category 1', 'Category 2']);

        $response = $this->controller->index();
        $this->assertEquals(['Category 1', 'Category 2'], $response);
    }

    // Test that the show method returns a specific category
    public function testShowReturnsCategory()
    {
        $this->mockCategoryService
            ->shouldReceive('getCategoryById')
            ->once()
            ->with(1)
            ->andReturn(['id' => 1, 'name' => 'Category 1']);

        $response = $this->controller->show(1);
        $this->assertEquals(['id' => 1, 'name' => 'Category 1'], $response);
    }

    // Test that the store method creates a category
    public function testStoreCreatesCategory()
    {
        $data = ['name' => 'Category 1'];
        $this->mockCategoryService
            ->shouldReceive('createCategory')
            ->once()
            ->with($data)
            ->andReturn(true); // Assuming the category is created successfully

        $response = $this->controller->store($data);
        $this->assertTrue($response);
    }

    // Test that the update method updates a category
    public function testUpdateUpdatesCategory()
    {
        $data = ['name' => 'Updated Category'];
        $this->mockCategoryService
            ->shouldReceive('updateCategory')
            ->once()
            ->with(1, $data)
            ->andReturn(true);

        $response = $this->controller->update(1, $data);
        $this->assertTrue($response);
    }

    // Test that the destroy method deletes a category
    public function testDestroyDeletesCategory()
    {
        $this->mockCategoryService
            ->shouldReceive('deleteCategory')
            ->once()
            ->with(1)
            ->andReturn(true);

        $response = $this->controller->destroy(1);
        $this->assertTrue($response);
    }

    // Test that the methods return an error if authentication fails
    public function testMethodsReturnAuthErrorIfAuthenticationFails()
    {
        $this->mockAuthService->shouldReceive('authenticate')->andReturn('Authentication failed');

        $response = $this->controller->index();
        $this->assertEquals('Authentication failed', $response);

        $response = $this->controller->show(1);
        $this->assertEquals('Authentication failed', $response);

        $response = $this->controller->store(['name' => 'Category 1']);
        $this->assertEquals('Authentication failed', $response);

        $response = $this->controller->update(1, ['name' => 'Updated Category']);
        $this->assertEquals('Authentication failed', $response);

        $response = $this->controller->destroy(1);
        $this->assertEquals('Authentication failed', $response);
    }

    // Clean up Mockery
    public function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}
