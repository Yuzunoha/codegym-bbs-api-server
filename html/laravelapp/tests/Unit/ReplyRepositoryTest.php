<?php

namespace Tests\Unit;

use App\Models\Reply;
use App\Repositories\ReplyRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReplyRepositoryTest extends TestCase
{
    use RefreshDatabase;

    protected $rep;

    public function setUp(): void
    {
        parent::setUp();

        // 存在しないクラス
        // $this->rep = app(ReplyRepository::class);
    }

    public function test_example()
    {
        $this->assertTrue(true);
    }

    public function _test_insert()
    {
        [$thread_id, $user_id, $text, $ip_address] = [1, 2, '3', '4'];
        $replyModel = $this->rep->insert($thread_id, $user_id, $text, $ip_address);
        $this->assertDatabaseHas('replies', $replyModel->toArray());
    }

    public function _test_selectAll()
    {
        $this->rep->insert(1, 1, 'あ', '0.0.0.0');
        $this->rep->insert(1, 2, 'い', '0.0.0.0');

        $ret = $this->rep->selectAll();
        $this->assertSame(2, count($ret->toArray()));

        $this->assertEquals(1, $ret[0]->id);
        $this->assertEquals(1, $ret[0]->thread_id);
        $this->assertEquals(1, $ret[0]->user_id);
        $this->assertEquals('あ', $ret[0]->text);

        $this->assertEquals(2, $ret[1]->id);
        $this->assertEquals(1, $ret[1]->thread_id);
        $this->assertEquals(2, $ret[1]->user_id);
        $this->assertEquals('い', $ret[1]->text);
    }
}
