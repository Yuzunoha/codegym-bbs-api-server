<?php

namespace App\Http\Paginators;

use Illuminate\Pagination\LengthAwarePaginator;

class YuzuPaginator extends LengthAwarePaginator
{
    public function toArray(): array
    {
        return [
            'data' => $this->items->toArray(),
            'current_page' => $this->currentPage(),
            'last_page' => $this->lastPage(),
            'per_page' => $this->perPage(),
        ];
    }
}
