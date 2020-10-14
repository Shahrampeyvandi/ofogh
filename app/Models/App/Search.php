<?php

namespace App\Models\App;

use Illuminate\Database\Eloquent\Model;

class Search extends Model
{
    public function cunsomers()
    {
        return $this->belongsTo('App\Models\Cunsomers\Cunsomer','cunsomer_id');
    }
}
