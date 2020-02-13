<?php

namespace LaravelNestedAutoCrud\Modulos\DbmCrm\Leads\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LeadContatoTelefone extends \LaravelNestedAutoCrud\Models\BaseModel
{


    // SoftDeletes
    use SoftDeletes;

    protected $table    = "leads_contatos_telefones";
    protected $fillable = [
        'usuario_id',
        'contato_id',
        'ddi',
        'telefone'
    ];
    // campos que representam datas
    protected $dates    = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];


    public function contato()
    {
        return $this->belongsTo(LeadContato::class, 'contato_id', 'id');

    }

    public function usuario()
    {
        return $this->belongsTo(User::class, 'usuario_id', 'id');

    }

}
