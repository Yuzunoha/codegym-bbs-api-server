<?php

namespace Tests\Unit;

use App\Repositories\ReplyRepository;
use App\Services\ReplyService;
use Mockery;
use PHPUnit\Framework\TestCase;

class ReplyServiceTest extends TestCase
{
    public function test_投稿()
    {
        [$thread_id, $user_id, $text, $ip_address] = [1, 2, '3', '4'];
        $mockReplyRepository = (object)Mockery::mock(ReplyRepository::class);
        $mockReplyRepository
            ->shouldReceive('insert')
            ->once()
            ->andReturn('ダミー戻り値');
        $replyService = new ReplyService($mockReplyRepository);
        $actual = $replyService->create($thread_id, $user_id, $text, $ip_address);
        $this->assertSame($actual, 'ダミー戻り値');
    }
}
