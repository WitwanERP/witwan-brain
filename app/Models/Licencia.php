<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Licencia extends Model
{
    protected $table = 'licencia';
    protected $primaryKey = 'licencia_id';
    public $timestamps = false;

    protected $fillable = [
        'licencia_nombre',
        'licencia_facturacion',
        'licencia_key',
        'licencia_base',
        'licencia_logo_login',
        'licencia_logo_voucher',
        'licencia_logo',
        'licencia_url',
        'licencia_pais',
        'fk_licencia_id',
        'base_colectora',
        'activar_cron',
        'prioridad',
        'host_db',
        'user_db',
        'pass_db',
        'app_url',
    ];

    protected $hidden = [
        'pass_db',
        'user_db',
        'host_db',
    ];

    public function secciones(): BelongsToMany
    {
        return $this->belongsToMany(
            Seccion::class,
            'rel_licenciaseccion',
            'fk_licencia_id',
            'fk_seccion_id'
        );
    }

    public function licenciaPadre(): BelongsTo
    {
        return $this->belongsTo(Licencia::class, 'fk_licencia_id', 'licencia_id');
    }
}
