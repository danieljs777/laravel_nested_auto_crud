<?php

namespace App\Modulos\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

trait SubFilterTrait
{

    protected function subFilter(Request $request, Builder &$query)
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
                        $a_column = explode('|', self::SUBFILTROS[$key]);

                        $column = $a_column[0];

                        $value = '%' . trim($val) . '%';

                        if (isset($a_column[1]))
                        {
                            switch ($a_column[1])
                            {
                                case 'DATE_INTERVAL':
                                    if (!empty($val))
                                    {
                                        $dates = explode('até', $val);

                                        $from = \Carbon\Carbon::createFromFormat('d/m/Y', trim($dates[0]));
                                        $to   = \Carbon\Carbon::createFromFormat('d/m/Y H:i:s', trim($dates[1] . ' 23:59:59'));
                                        $q    = $q->whereBetween($column, [$from, $to]);
                                    }
                                    break;

                                default:
                                    break;
                            }
                        }

                        $q->orWhere($column, 'like', $value);
                    }
                }
            });
        }
    }

}
