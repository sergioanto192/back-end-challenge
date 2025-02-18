<?php
use App\Models\Tag;
use App\Exceptions\DatabaseException;
use PHPUnit\Framework\TestCase;
use Mockery;

class TagTest extends TestCase
{
    protected $pdoMock;
    protected $pdoStatementMock;

    public function setUp(): void
    {
        parent::setUp();

        $this->pdoMock = Mockery::mock('overload:' . \App\Config\db::class);
        $this->pdoStatementMock = Mockery::mock('PDOStatement');
    }

    public function tearDown(): void
    {
        Mockery::close();
    }

    public function testGetAll()
    {
        
        $tags = [
            ['id' => 1, 'name' => 'Tag 1'],
            ['id' => 2, 'name' => 'Tag 2']
        ];

        $this->pdoMock->shouldReceive('connect')
            ->once()
            ->andReturnSelf();

        $this->pdoMock->shouldReceive('query')
            ->once()
            ->andReturn($this->pdoStatementMock);

        $this->pdoStatementMock->shouldReceive('fetchAll')
            ->once()
            ->with(\PDO::FETCH_ASSOC)
            ->andReturn($tags);

        $result = Tag::getAll();
        $this->assertEquals($tags, $result);
    }

    public function testGetAllWithException()
    {
        $this->pdoMock->shouldReceive('connect')
            ->once()
            ->andReturnSelf();

        $this->pdoMock->shouldReceive('query')
            ->once()
            ->andThrow(new \PDOException('Erro ao obter tags'));

        $this->expectException(DatabaseException::class);
        $this->expectExceptionMessage('Erro ao obter todas as tags: Erro ao obter tags');

        Tag::getAll();
    }
}
