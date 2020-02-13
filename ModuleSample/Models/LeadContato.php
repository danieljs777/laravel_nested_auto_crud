<?php

namespace LaravelNestedAutoCrud\Modulos\DbmCrm\Leads\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LeadContato extends \LaravelNestedAutoCrud\Models\BaseModel
{


    // SoftDeletes
    use SoftDeletes;

    protected $table    = "leads_contatos";
    protected $fillable = [
        'usuario_id',
        'nome',
        'lead_id',
        'cargo',
        'depto',
        'idioma_id'
    ];
    // campos que representam datas
    protected $dates    = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];
    protected $childs   = [
        'telefones' => "\LaravelNestedAutoCrud\Modulos\DbmCrm\Leads\Models\LeadContatoTelefone",
        'emails'    => "\LaravelNestedAutoCrud\Modulos\DbmCrm\Leads\Models\LeadContatoEmail",
    ];


    public function telefones()
    {
        return $this->hasMany(LeadContatoTelefone::class, 'contato_id', 'id');

    }

    public function emails()
    {
        return $this->hasMany(LeadContatoEmail::class, 'contato_id', 'id');

    }

    public function usuario()
    {
        return $this->belongsTo(User::class, 'usuario_id', 'id');

    }

}
