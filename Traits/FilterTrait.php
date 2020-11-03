<?php

namespace App\Modulos\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

trait FilterTrait
{

    protected function filter(Request $request, Builder &$query)
    {
        if ($request->has('search') && is_array($request->input('search')) && !empty($request->input('search')['main']))
        {

            $search = '%' . $request->input('search')['main'] . '%';

            foreach (self::FILTERS as $key => $column)
            {
                $query->orWhere($column, 'like', $search);
            }
        }
    }

}
