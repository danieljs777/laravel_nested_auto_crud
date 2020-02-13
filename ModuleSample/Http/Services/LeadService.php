<?php

namespace LaravelNestedAutoCrud\Modulos\DbmCrm\Leads\Http\Services;

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;
use LaravelNestedAutoCrud\Modulos\Traits\
{
    OrderByTrait,
    FiltroTrait,
    SubFiltroTrait
};
use LaravelNestedAutoCrud\Modulos\DbmCrm\Leads\Models\
{
    Lead,
    LeadView,
    LeadEndereco,
    LeadContato,
    LeadContatoEmail,
    LeadContatoTelefone,
    LeadContatoEndereco
};
use LaravelNestedAutoCrud\Modulos\DbmCrm\Leads\Http\Requests\ServiceRequest;

class LeadService extends \LaravelNestedAutoCrud\Services\BaseService
{


    use OrderByTrait,
        FiltroTrait,
        SubFiltroTrait;

    const FILTROS    = [
        'id'              => 'view_leads.id',
        'nome_principal'  => 'view_leads.nome_principal',
        'nome_secundario' => 'view_leads.nome_secundario',
        'tipo_pessoa'     => 'view_leads.tipo_pessoa',
        'created_at'      => 'view_leads.created_at',
        'telefones'       => 'view_leads.telefones',
        'emails'          => 'view_leads.emails',
        'como_conheceu'   => 'view_leads.como_conheceu',
        'canal'           => 'view_leads.canal',
        'socio'           => 'view_leads.socio'
    ];
    const SUBFILTROS = [
        'id'              => 'view_leads.id',
        'nome_principal'  => 'view_leads.nome_principal',
        'nome_secundario' => 'view_leads.nome_secundario',
        'tipo_pessoa'     => 'view_leads.tipo_pessoa',
        'created_at'      => 'view_leads.created_at',
        'telefones'       => 'view_leads.telefones',
        'emails'          => 'view_leads.emails',
        'como_conheceu'   => 'view_leads.como_conheceu',
        'canal'           => 'view_leads.canal',
        'socio'           => 'view_leads.socio'
    ];
    const ORDENACOES = [
        'id'              => 'view_leads.id',
        'nome_principal'  => 'view_leads.nome_principal',
        'nome_secundario' => 'view_leads.nome_secundario',
        'tipo_pessoa'     => 'view_leads.tipo_pessoa',
        'created_at'      => 'view_leads.created_at',
        'telefones'       => 'view_leads.telefones',
        'emails'          => 'view_leads.emails',
        'como_conheceu'   => 'view_leads.como_conheceu',
        'canal'           => 'view_leads.canal',
        'socio'           => 'view_leads.socio'
    ];


    public function __construct(Lead $model, LeadView $view_model, ServiceRequest $request)
    {
        parent::__construct($model, $view_model, $request);

    }

    public function show(int $id, $relations = [])
    {
        return parent::show($id, ['enderecos', 'contatos.emails', 'contatos.telefones']);

    }

    public function list()
    {
        return parent::index(function (Request $request, Builder &$query)
                {

                });

    }

    public function store($json_return = false, $callback = null)
    {
        $object = parent::store(true, function ($_object)
                {

                });

        if ($object === false)
            return response()->json(['success' => false, 'message' => 'Um erro aconteceu. Tente novamente.'], 422);

        return $object;

    }

    public function update(int $id, $json_return = false, $callback = null)
    {
        return parent::update($id, true);

    }

    public function destroy(int $id, $callback = null)
    {
        $result = parent::destroy($id, function ($_obj_model)
                {
                    $_obj_model->contatos()->telefones()->delete();
                    $_obj_model->contatos()->emails()->delete();
                    $_obj_model->contatos()->delete();
                    $_obj_model->enderecos()->delete();
                });

        return $result;

    }

}
