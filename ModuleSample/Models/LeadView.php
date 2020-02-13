<?php

namespace LaravelNestedAutoCrud\Modulos\DbmCrm\Leads\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LeadView extends \LaravelNestedAutoCrud\Models\BaseModel
{


    // SoftDeletes
    use SoftDeletes;

    protected $table    = "view_leads";
    protected $fillable = [
        'id',
        'nome_principal',
        'nome_secundario',
        'tipo_pessoa',
        'created_at',
        'telefones',
        'emails',
        'como_conheceu',
        'canal',
        'socio'
    ];
    // campos que representam datas
    protected $dates    = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];
    protected $childs   = [
        'enderecos' => "\LaravelNestedAutoCrud\Modulos\DbmCrm\Leads\Models\LeadEndereco",
        'contatos'  => "\LaravelNestedAutoCrud\Modulos\DbmCrm\Leads\Models\LeadContato",
    ];


    public function tipo_pessoa()
    {
        return $this->belongsTo(Lista::class, 'tipo_pessoa_id', 'id');

    }

    public function usuario()
    {
        return $this->belongsTo(User::class, 'usuario_id', 'id');

    }

}
