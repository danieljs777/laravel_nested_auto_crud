<?php

namespace LaravelNestedAutoCrud\Modulos\DbmCrm\Leads\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Lead extends \LaravelNestedAutoCrud\Models\BaseModel
{


    // SoftDeletes
    use SoftDeletes;

    protected $table    = "leads";
    protected $fillable = [
        "usuario_id",
        "tipo_pessoa_id",
        "nome_principal",
        "nome_secundario",
        "ramo_atividade_id",
        "segmento_id",
        "como_conheceu_id",
        "canal_id",
        "socio_relacionamento_id",
        "colaborador_id",
        "lideranca_id",
        "observacao",
        "status_id",
        "last_status_update"
    ];
    // campos que representam datas
    protected $dates    = [
        'created_at',
        'updated_at',
        'deleted_at',
        "last_status_update"
    ];
    protected $childs   = [
        'enderecos' => "\LaravelNestedAutoCrud\Modulos\DbmCrm\Leads\Models\LeadEndereco",
        'contatos'  => "\LaravelNestedAutoCrud\Modulos\DbmCrm\Leads\Models\LeadContato",
    ];


    public function contatos()
    {
        return $this->hasMany(LeadContato::class, 'lead_id', 'id');

    }

    public function status()
    {
        return $this->hasOne(\LaravelNestedAutoCrud\Models\Lista::class, 'id', 'status_id');

    }

    public function enderecos()
    {
        return $this->hasMany(LeadEndereco::class, 'lead_id', 'id');

    }

    public function usuario()
    {
        return $this->belongsTo(User::class, 'usuario_id', 'id');

    }

}
