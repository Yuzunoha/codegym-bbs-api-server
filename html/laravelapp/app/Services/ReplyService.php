<?php

namespace App\Services;

use App\Models\User;
use App\Repositories\ReplyRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class ReplyService implements ReplyServiceInterface
{
    protected $replyRepository;
    protected $threadService;

    public function __construct(
        ReplyRepositoryInterface $replyRepository,
        ThreadServiceInterface $threadService
    ) {
        $this->replyRepository = $replyRepository;
        $this->threadService = $threadService;
    }

    public function create($thread_id, $user_id, $text, $ip_address)
    {
        /* thread_id が存在するか確認する */
        if (null === $this->threadService->selectById($thread_id)) {
            UtilService::throwHttpResponseException("thread_id ${thread_id} は存在しません。");
        }

        /* user_id が存在するか確認する */
        if (null === User::find($user_id)) {
            UtilService::throwHttpResponseException("user_id ${user_id} は存在しません。");
        }

        /* number を取得する */
        $number = count($this->replyRepository->selectByThreadId($thread_id)) + 1;

        return $this->replyRepository->insert($thread_id, $number, $user_id, $text, $ip_address);
    }

    public function selectAll()
    {
        return $this->replyRepository->selectAll();
    }

    public function selectByThreadId(int $thread_id): Collection
    {
        return $this->replyRepository->selectByThreadId($thread_id);
    }
}
