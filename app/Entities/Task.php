<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;
use DB;

class Task extends Model
{
    protected $table = 'task';

    protected $fillable = [
        'id_completo',
        'nombre',
        'tipo_de_sitio',
        'estado',
        'marca',
        'modelo',
        'idc',
        'seguimiento',
        'fecha_hora_cita',
        'hora_inicio',
        'hora_final',
        'estatus',
        'comentarios',
        'user_name',
        'user_asignado',
        'created_at',
        'updated_at',
        'id_unico'
    ];

}
