<?php

namespace LaravelNestedAutoCrud\Modulos\DbmCrm\Leads\Models;

use Illuminate\Database\Eloquent\Model;

class LeadEndereco extends \LaravelNestedAutoCrud\Models\BaseModel
{

    protected $table    = "leads_enderecos";
    protected $hidden   = [
        'created_at',
        'updated_at',
        'deleted_at',
        'usuario_id',
    ];
    protected $fillable = [
        'usuario_id',
        'cep',
        'tipo_endereco_id',
        'pais',
        'estado',
        'cidade',
        'bairro',
        'logradouro',
        'numero',
        'complemento',
    ];


    public function tipo()
    {
        return $this->hasOne(Lista::class, 'id', 'tipo_endereco_id');

    }

    public function usuario()
    {
        return $this->belongsTo(User::class, 'usuario_id', 'id');

    }

}
