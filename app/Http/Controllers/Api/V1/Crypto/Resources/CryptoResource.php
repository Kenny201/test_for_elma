<?php

namespace App\Http\Controllers\Api\V1\Crypto\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use JetBrains\PhpStorm\ArrayShape;

class CryptoResource extends JsonResource
{
    /**
     * @param Request $request
     * @return array
     */
    public function toArray($request): array
    {
        return [
            'currency_from' => $this->name,
            'currency_to' => $this->currency,
            'source' => $this->source->name,
            'rate' => $this->rate
        ];
    }
}
