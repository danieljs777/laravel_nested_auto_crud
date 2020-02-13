<?php

namespace Golaw\Modulos\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

trait SubFiltroTrait
{

    protected function subFiltro(Request $request, Builder &$query)
    {
        if ($request->has('search') && is_array($request->input('search')))
        {

            $search = $request->input('search');

            $query->where(function($q) use($search)
            {
                foreach ($search as $key => $val)
                {
                    if (in_array($key, array_keys(self::SUBFILTROS)))
                    {
                        $a_coluna = explode('|', self::SUBFILTROS[$key]);

                        $coluna = $a_coluna[0];

                        $value = '%' . trim($val) . '%';

                        if (isset($a_coluna[1]))
                        {
                            switch ($a_coluna[1])
                            {
                                case 'DATE_INTERVAL':
                                    if (!empty($val))
                                    {
                                        $dates = explode('até', $val);

                                        $from = \Carbon\Carbon::createFromFormat('d/m/Y', trim($dates[0]));
                                        $to   = \Carbon\Carbon::createFromFormat('d/m/Y H:i:s', trim($dates[1] . ' 23:59:59'));
                                        $q    = $q->whereBetween($coluna, [$from, $to]);
                                    }
                                    break;

                                default:
                                    break;
                            }
                        }

                        $q->orWhere($coluna, 'like', $value);
                    }
                }
            });
        }
    }

}
