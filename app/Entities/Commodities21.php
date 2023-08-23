<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;
use DB;
use App\Entities\CommoditiesCombos21;

class Commodities21 extends Model
{
    protected $table = 'commodities_paso2_1';

    public function preguntas()
    {
        return $this->hasMany(CommoditiesCombos21::class, 'pregunta_id');
    }

}