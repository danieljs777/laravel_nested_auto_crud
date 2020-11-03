<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 *
 * @author daniel
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

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class, 'user_id', 'id');

    }

    public function file()
    {
        return $this->hasOne(\App\Models\File::class, 'id', 'file_id');

    }

}
