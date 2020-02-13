<?php

namespace LaravelNestedAutoCrud\Services;

use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Excel;
use LaravelNestedAutoCrud\Modulos\Traits\
{
    OrderByTrait,
    FiltroTrait,
    SubFiltroTrait
};

/**
 * BaseService is a class helper that provides CRUD operations for all modules,
 * including validation, exception handling, response with http codes output and general operations
 *
 * @author daniel (daniel.js at gmail dot com)
 */
class BaseService
{


    use OrderByTrait,
        FiltroTrait,
        SubFiltroTrait;

    protected $model;
    protected $view_model;
    protected $request;


    public function __construct($model, $view_model, $request)
    {
        $this->model      = $model;
        $this->view_model = $view_model;
        $this->request    = $request;

    }

    private function query(): Builder
    {
        return $this->view_model::query();

    }

    public function index($callback = null)
    {
        $limite = $this->request->input('limite');
        $search = $this->request->input('search');
        $page   = $this->request->input('page');

        $list = $this->query()->select('*');

        $this->orderBy($this->request, $list);

        $this->filtro($this->request, $list);

        $this->subFiltro($this->request, $list);

        if ($callback !== null)
        {
            $callback($this->request, $list);
        }

        $response = $list->paginate($limite ?? 10, ['*'], $page ?? 1, $page ?? 1);

        $response = array_merge((array) $response->toArray(), ['roles' => $this->roles()]);

        return response()->json($response);

    }

    public function show(int $id, $relations = [])
    {

        try
        {
            $obj_model = $this->model::with($relations)->findOrFail($id);
            $response  = array_merge((array) $obj_model->toArray(), ['roles' => $this->roles()]);

            return response()->json($response, 200);
        }
        catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e)
        {
            return response()->json(['success' => false, 'message' => 'Not Found'], 404);
        }
        catch (\Exception $error)
        {
            Log::error('#### ' . __CLASS__ . ':' . __METHOD__ . ':' . __LINE__, [$error]);
            return response()->json(['success' => false, 'message' => $error->getMessage()], 422);
        }

    }

    private function substore(&$data, &$tab, &$obj_model, &$class)
    {
        if (!isset($data[$tab]) && empty($data[$tab]))
            return;

        $req_ids = array_filter($data[$tab], function($row)
        {
            return isset($row['id']);
        });

        $req_ids = array_map(function($row)
        {
            return $row['id'];
        }, $req_ids
        );

        if (count($req_ids) == 0)
        {
            $obj_model->{$tab}()->delete();
        }
        else
        {
            $obj_model->{$tab}()->whereNotIn('id', $req_ids)->delete();
        }

        foreach ($data[$tab] as $tab_data)
        {

            $return = $this->processDefaultChild($tab_data, $obj_model->getModel(), $tab);

            if ($return !== TRUE && !is_object($return))
            {
//                dd($obj_model->getModel(), $return);
                // throw new \Exception($return);
            }

            $sub_model  = new $class();
            $child_tabs = $sub_model->getModel()->getChilds();

            if (count($child_tabs) > 0)
            {
                foreach ($child_tabs as $_tab => $_class)
                {
                    $this->substore($tab_data, $_tab, $return, $_class);
                }
            }

            $sub_model = null;
        }

    }

    public function store($json_return = false, $callback = null)
    {

        try
        {

            DB::beginTransaction();

            $data = $this->request->all();

            $db_data               = self::validate($data, $this->model);
            $db_data['usuario_id'] = $this->request->user()->id;
            $obj_model             = $this->model::create($db_data);

            $tabs = $this->model->getChilds();

            if (sizeof($tabs) > 0)
            {
                foreach ($tabs as $tab => $class)
                {
                    $this->substore($data, $tab, $obj_model, $class);
                }
            }

            if (!is_null($callback) && $obj_model !== NULL)
                $callback($obj_model);

            DB::commit();
        }
        catch (\Exception $error)
        {
            Log::error('#### ' . __CLASS__ . ':' . __METHOD__ . ':' . __LINE__, [$error]);
            DB::rollBack();
            if ($json_return)
            {
                if (config('app.env') !== 'production')
                    return response()->json(['success' => false, 'message' => $error->getMessage(), 'error' => (Array) ($error)], 422);
                else
                    return response()->json(['success' => false, 'message' => $error->getMessage()], 422);
            }
            else
            {
                if (config('app.env') !== 'production')
                    dd($error);
                else
                    return false;
            }
        }

        if ($json_return)
            return response()->json(['success' => true, 'message' => '', 'object' => $obj_model], 200);
        else
            return $obj_model;

    }

    public function update(int $id, $json_return = false, $callback = null)
    {
        $_data = $this->request->except(['usuario_id']);

        try
        {
            DB::beginTransaction();
            $data      = self::validate($_data, $this->model);
            $obj_model = $this->model::findOrFail($id);
            $obj_model->update($data);

            $tabs = $this->model->getChilds();

            if (sizeof($tabs) > 0)
            {
                foreach ($tabs as $tab => $class)
                {
                    $this->substore($_data, $tab, $obj_model, $class);
                }
            }

            if (!is_null($callback) && $obj_model !== NULL)
                $callback($obj_model);

            DB::commit();
        }
        catch (\Illuminate\Database\Eloquent\ModelNotFoundException $error)
        {
            Log::error('#### ' . __CLASS__ . ':' . __METHOD__ . ':' . __LINE__, [$error]);
            DB::rollBack();
            if ($json_return)
                return response()->json(['success' => false, 'message' => 'Not Found'], 404);
            else
                return false;
        }
        catch (\Exception $error)
        {
            Log::error('#### ' . __CLASS__ . ':' . __METHOD__ . ':' . __LINE__, [$error]);
            DB::rollBack();
            if ($json_return)
            {
                if (config('app.env') !== 'production')
                    return response()->json(['success' => false, 'message' => $error->getMessage(), 'error' => (Array) ($error)], 422);
                else
                    return response()->json(['success' => false, 'message' => $error->getMessage()], 422);
            }
            else
            {
                if (config('app.env') !== 'production')
                    dd($error);
                else
                    return false;
            }
        }

        if ($json_return)
            return response()->json(['success' => true, 'message' => '', 'object' => $obj_model], 200);
        else
            return $obj_model;

    }

    public function destroy(int $id, $callback = null)
    {

        try
        {
            DB::beginTransaction();

            $obj_model = $this->model::findOrFail($id);

            $result = $obj_model->destroy($id);

            if (!is_null($callback) && $obj_model !== NULL)
                $callback_r = $callback($obj_model);

            DB::commit();

            return response()->json(['success' => true], 200);
        }
        catch (\Illuminate\Database\Eloquent\ModelNotFoundException $error)
        {
            return response()->json(['success' => false, 'message' => 'Not Found'], 404);
        }
        catch (\Exception $error)
        {
            Log::error('#### ' . __CLASS__ . ':' . __METHOD__ . ':' . __LINE__, [$error]);
            DB::rollBack();
            return response()->json(['success' => false, 'message' => $error->getMessage()], 422);
        }

    }

    public function find($id)
    {
        try
        {
            $obj_model = $this->model::findOrFail($id);

            return $obj_model;
        }
        catch (\Illuminate\Database\Eloquent\ModelNotFoundException $error)
        {
            throw $error;
        }

    }

    public function execute($id, $callback = null)
    {
//        USAGE:
//        $result = parent::execute($id, function ($_obj_model) use ($id, $data, $json_return)
//                {
//                    try
//                    {
//                        $db_data               = $data;
//                        $db_data['usuario_id'] = $this->request->user()->id;
//
//                        if (isset($db_data['id']) && $db_data['id'] != '')
//                            $_obj_model->followups()->where('id', $db_data['id'])->update($db_data);
//                        else
//                            $_obj_model->followups()->create($db_data);
//                    }
//                    catch (Exception $e)
//                    {
//                        if ($json_return)
//                            return response()->json(['success' => false, 'message' => $error->getMessage()], 422);
//                        else
//                            return $e->getMessage();
//                    }
//
//                    if ($json_return)
//                        return response()->json(['success' => true, 'message' => ''], 200);
//                    else
//                        return TRUE;
//                });

        $obj_model = $this->find($id);

//        if (method_exists($obj_model, 'getStatusCode'))
//            return response()->json(['success' => false, 'message' => 'Not Found'], 404);

        if (!is_null($callback) && $obj_model !== NULL)
            return $callback($obj_model);

    }

    public function processChilds($data, $child_node, $default_fk = "", $json_return = false, $use_transaction = false)
    {

        try
        {
            if ($use_transaction)
                DB::beginTransaction();

            foreach ($data['ids'] as $id)
            {
                $_obj_model = $this->find($id);

                $db_data              = $data[$child_node];
                $db_data[$default_fk] = $id;

                $result = $this->processDefaultChild($db_data, $_obj_model, $child_node, $default_fk, false);
            }

            if ($use_transaction)
                DB::commit();

//            if (!$result)
//                break;
        }
        catch (\Illuminate\Database\Eloquent\ModelNotFoundException $error)
        {
            if ($use_transaction)
                DB::rollBack();

            if ($json_return)
                return response()->json(['success' => false, 'message' => 'Not Found'], 404);
            else
                return false;
        }
        catch (Exception $error)
        {
            if ($use_transaction)
                DB::rollBack();

            if ($json_return)
                return response()->json(['success' => false, 'message' => $error->getMessage()], 422);
            else
                return $error->getMessage();
        }

        if ($json_return)
            return response()->json(['success' => true]);
        else
            return true;

    }

    public function processDefaultChild($child_data, $model, $child_node, $default_fk = "", $json_return = false)
    {

        if (isset($child_data['ids']))
            unset($child_data['ids']);

        if ($model === null)
            return false;

        $input = [];
        try
        {

            $obj = self::validate($child_data, $model->{$child_node}()->getModel());

            if (in_array('usuario_id', $model->{$child_node}()->getModel()->getFillable()))
                $obj['usuario_id'] = $this->request->user()->id;

//            \DB::connection()->enableQueryLog();

            if (isset($obj['id']) && $obj['id'] != '')
            {
                $_id = $obj['id'];
                unset($obj['id']);

                $model->{$child_node}()->where('id', $_id)->update($obj);
                $child_obj = $model->{$child_node}->find($_id);
            }
            else
            {
                $child_obj = $model->{$child_node}()->create($obj);
            }

//            $queries = \DB::getQueryLog();
//            dd($queries);
        }
        catch (Exception $error)
        {
            if ($json_return)
                return response()->json(['success' => false, 'message' => $error->getMessage()], 422);
            else
                return false;
        }

        if ($json_return)
            return response()->json(['success' => true, 'message' => ''], 200);
        else
//            dd("HEY HO");
            return $child_obj;

    }

    public static function validate($array, $model)
    {
        $_model = (!is_object($model)) ? new $model() : $_model = $model;

        $atts = $_model->getFillable();
        foreach ($array as $key => $item)
        {
            if ($key == 'id')
                continue;

            if (is_array($item))
            {
                unset($array[$key]);
            }
            else
            {
                if (is_numeric(
                                $key))
                    unset($array[$key]);

                if (array_search($key, $atts, false) === FALSE)
                    unset($array[$key]);
            }
        }



        return $array;

    }

    public function roles()
    {
        $role = [];
        return $role;

    }

    public function download($filename = "SampleSheet")
    {
        $model  = $this->view_model;
        $fields = $model->getFillable();

        $download = $model->get();
        $download = collect($download->toArray());

        $download = collect($download)->map(function($item) use($fields)
        {

            $download = [];

            foreach ($fields as $field)
            {
                if (isset($item[$field]))
                    $download[$field] = $item[$field];
            }


            return $download;
        });


        return Excel::create($filename, function($excel) use ($download, $filename)
                {
                    $excel->sheet($filename, function ($sheet) use ($download)
                    {
                        $sheet->fromArray($download);
                    });
                }
                )->download('xlsx');

    }

}
