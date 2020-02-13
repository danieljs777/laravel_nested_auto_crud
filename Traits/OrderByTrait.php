<?php

namespace Golaw\Modulos\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

trait OrderByTrait 
{
    protected function orderBy(Request $request, Builder &$query)
    {
        if ($request->has('ordination')) {
            $key = array_keys($request->input('ordination'))[0] ?? null; 
            $value = array_values($request->input('ordination'))[0] ?? null;

            if (!is_null($key) && !is_null($value)) {
                if (in_array($key, array_keys(self::ORDENACOES)) && in_array($value, ['asc', 'desc'])) {
                    
                    $coluna = self::ORDENACOES[$key];
                    $query->orderBy($coluna, $value);
                }
            }
        } else {
            $query->orderBy('id', 'desc');
        }
    }
}