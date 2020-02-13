<?php

namespace Golaw\Modulos\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

trait FiltroTrait
{

    protected function filtro(Request $request, Builder &$query)
    {
        if ($request->has('search') && is_array($request->input('search')) && !empty($request->input('search')['main']))
        {

            $search = '%' . $request->input('search')['main'] . '%';

            foreach (self::FILTROS as $key => $coluna)
            {
                $query->orWhere($coluna, 'like', $search);
            }
        }
    }

}
