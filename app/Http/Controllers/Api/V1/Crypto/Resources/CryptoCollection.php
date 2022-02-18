<?php

namespace App\Http\Controllers\Api\V1\Crypto\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use JetBrains\PhpStorm\ArrayShape;
use Illuminate\Support\Collection;

/** @see \App\Models\Course */
class CryptoCollection extends ResourceCollection
{
    /**
     * @param Request $request
     * @return array
     */
    public function toArray($request): array
    {
        return [
            'data' => $this->collection,
        ];
    }
}
