<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class LicenciaSeccion extends Pivot
{
    protected $table = 'rel_licenciaseccion';
    public $timestamps = false;
    public $incrementing = false;

    protected $fillable = [
        'fk_licencia_id',
        'fk_seccion_id',
    ];
}
