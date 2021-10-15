<?php

namespace App\Http\Paginators;

use Illuminate\Pagination\LengthAwarePaginator;

class YuzuPaginator extends LengthAwarePaginator
{
    public function toArray(): array
    {
        return [
            'Yuzu' => 'Yuzu',
            'data' => $this->items->toArray(),
        ];
    }
}
