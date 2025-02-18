<?php
use PHPUnit\Framework\TestCase;
use Mockery;
use App\Models\Category;
use PDOStatement;
use PDO;

class CategoryTest extends TestCase
{
    /**
     * @var Mockery\MockInterface
     */
    private $dbMock;

    /**
     * @var Mockery\MockInterface
     */
    private $pdoMock;

    /**
     * @var Mockery\MockInterface
     */
    private $pdoStatementMock;

    protected function setUp(): void
    {
       
        $this->pdoMock = Mockery::mock(PDO::class);
        
   
        $this->pdoStatementMock = Mockery::mock(PDOStatement::class);

     
        $this->dbMock = Mockery::mock('alias:App\Config\db');
        $this->dbMock->shouldReceive('connect')->andReturn($this->pdoMock);
    }

    public function testGetAll()
    {
        
        $this->pdoMock->shouldReceive('query')
            ->once()
            ->andReturn($this->pdoStatementMock);

       
        $this->pdoStatementMock->shouldReceive('fetchAll')
            ->once()
            ->andReturn([['id' => 1, 'name' => 'Electronics']]);

        
        $categories = Category::getAll();
        $this->assertCount(1, $categories);
        $this->assertEquals('Electronics', $categories[0]['name']);
    }

    public function testCreate()
    {
        
        $this->pdoMock->shouldReceive('prepare')
            ->once()
            ->andReturn($this->pdoStatementMock);

        $this->pdoStatementMock->shouldReceive('execute')
            ->once()
            ->with(['Books'])
            ->andReturn(true);

        $this->pdoMock->shouldReceive('lastInsertId')
            ->once()
            ->andReturn('1');

        
        $categoryId = Category::create('Books');
        $this->assertEquals('1', $categoryId); 
    }

    public function testUpdate()
    {
         
    $this->pdoMock->shouldReceive('prepare')
    ->once()
    ->andReturn($this->pdoStatementMock);

    
    $this->pdoStatementMock->shouldReceive('execute')
        ->once()
        ->with([ 'Updated Category', 1 ]) // Ordem correta dos parâmetros
        ->andReturn(true);

   
    $result = Category::update(1, 'Updated Category');
    $this->assertTrue($result);
    }

    public function testDelete()
    {
      
        $this->pdoMock->shouldReceive('prepare')
            ->once()
            ->andReturn($this->pdoStatementMock);

        $this->pdoStatementMock->shouldReceive('execute')
            ->once()
            ->with([1])
            ->andReturn(true);

        
        $result = Category::delete(1);
        $this->assertTrue($result);
    }

    protected function tearDown(): void
    {
        
        Mockery::close();
    }
}
