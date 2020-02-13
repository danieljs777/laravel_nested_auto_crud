<?php

namespace LaravelNestedAutoCrud\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @author daniel (daniel.js at gmail dot com)
 */

class BaseModel extends Model
{

    protected $childs = [];


    function getTable()
    {
        return $this->table;

    }

    function getPrimaryKey()
    {
        return $this->primaryKey;

    }

    function getFillable()
    {
        return $this->fillable;

    }

    function getChilds()
    {
        return $this->childs;

    }

    public function usuario()
    {
        return $this->belongsTo(\LaravelNestedAutoCrud\Models\User::class, 'usuario_id', 'id');

    }

}
