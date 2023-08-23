<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;
use DB;
use App\Entities\CommoditiesCombos3;

class Commodities3 extends Model
{
    protected $table = 'commodities_paso3';

    public function preguntas()
    {
        return $this->hasMany(CommoditiesCombos3::class, 'pregunta_id');
    }

}