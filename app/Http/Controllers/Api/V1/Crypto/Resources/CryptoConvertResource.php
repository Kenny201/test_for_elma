<?php

namespace App\Http\Controllers\Api\V1\Crypto\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CryptoConvertResource extends JsonResource
{
    /**
     * @param Request $request
     * @return array
     */
    public function toArray($request): array
    {
        return [
            'currency_from' => $this->currency_from,
            'currency_to' => $this->currency_to,
            'value' => $this->value,
            'converted_value' => $this->converted_value,
            'rate' => $this->rate,
            'created_at' => $this->created_at
        ];
    }
}
