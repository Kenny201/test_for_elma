<?php

namespace App\Http\Controllers\Api\V1\Crypto;

use App\Http\Controllers\Api\V1\Crypto\Requests\CryptoConvertRequest;
use App\Http\Controllers\Api\V1\Crypto\Resources\CryptoCollection;
use App\Http\Controllers\Api\V1\Crypto\Resources\CryptoConvertCollection;
use App\Http\Controllers\Api\V1\Crypto\Resources\CryptoConvertResource;
use App\Http\Controllers\Api\V1\Crypto\Resources\CryptoResource;
use App\Http\Controllers\Controller;
use App\Models\Convert;
use App\Models\Course;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\QueryBuilder;

class CryptoController extends Controller
{
    public function index()
    {
        $cryptocurrency = QueryBuilder::for(Course::class)
            ->allowedFilters('currency')
            ->get();

        if ($cryptocurrency->count() === 0){
            return response()->json(
                ['error' => 'Добавьте пары из источников'],
                404, [],
                JSON_UNESCAPED_UNICODE
            );
        }

        return new CryptoCollection($cryptocurrency);
    }

    public function store(CryptoConvertRequest $request)
    {
        $request->validated();

        $rate = Course::where([
            ['name', $request->currency_from],
            ['currency', $request->currency_to],
            ['source_id', $request->source_id]])
            ->latest()
            ->pluck('rate_including_commission')
            ->first();

        if (!$rate) {
            return response()->json(
                ['error' => 'Такой пары не найдено'],
                404, [],
                JSON_UNESCAPED_UNICODE
            );
        }

        $currency_from = $request->currency_from;
        $currency_to = $request->currency_to;
        $value = $request->value;
        $converted_value = $rate * $value;

        $id_converted_crypto = Convert::create([
            'currency_from' => $currency_from,
            'currency_to' => $currency_to,
            'value' => $value,
            'converted_value' => $converted_value,
            'rate' => $rate
        ])->id;

        return new CryptoConvertResource(Convert::find($id_converted_crypto));
    }

    public function show($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        //
    }

    public function destroy($id)
    {
        //
    }
}
