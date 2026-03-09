<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Seccion extends Model
{
    protected $table = 'seccion';
    protected $primaryKey = 'seccion_id';
    public $timestamps = false;

    protected $fillable = [
        'fk_seccion_id',
        'seccion_key',
        'fk_sistema_id',
        'seccion_grupo',
        'seccion_grupo_en',
        'seccion_grupo_pt',
        'icono',
        'seccion_nombre',
        'seccion_nombre_en',
        'seccion_nombre_pt',
        'seccion_uri',
        'orden',
        'ordenmt',
    ];

    protected $casts = [
        'fk_sistema_id' => 'integer',
        'fk_seccion_id' => 'integer',
        'orden' => 'integer',
        'ordenmt' => 'integer',
    ];

    const SISTEMA_RECEPTIVO = 1;
    const SISTEMA_MAYORISTA = 2;
    const SISTEMA_MINORISTA = 3;
    const SISTEMA_CONSOLIDADOR = 4;
    const SISTEMA_ADMINISTRACION = 5;
    const SISTEMA_CONFIGURACION = 6;
    const SISTEMA_NACIONAL = 7;

    public static function getSistemas(): array
    {
        return [
            self::SISTEMA_RECEPTIVO => 'Receptivo',
            self::SISTEMA_MAYORISTA => 'Mayorista',
            self::SISTEMA_MINORISTA => 'Minorista',
            self::SISTEMA_CONSOLIDADOR => 'Consolidador',
            self::SISTEMA_ADMINISTRACION => 'Administración',
            self::SISTEMA_CONFIGURACION => 'Configuración',
            self::SISTEMA_NACIONAL => 'Nacional',
        ];
    }

    public function padre(): BelongsTo
    {
        return $this->belongsTo(Seccion::class, 'fk_seccion_id', 'seccion_id');
    }

    public function hijos(): HasMany
    {
        return $this->hasMany(Seccion::class, 'fk_seccion_id', 'seccion_id')
            ->orderBy('orden');
    }

    public function hijosRecursivos(): HasMany
    {
        return $this->hijos()->with('hijosRecursivos');
    }

    public function licencias(): BelongsToMany
    {
        return $this->belongsToMany(
            Licencia::class,
            'rel_licenciaseccion',
            'fk_seccion_id',
            'fk_licencia_id'
        );
    }

    public function getNombreTraducido(string $idioma = 'es'): string
    {
        return match($idioma) {
            'en' => $this->seccion_nombre_en ?: $this->seccion_nombre,
            'pt' => $this->seccion_nombre_pt ?: $this->seccion_nombre,
            default => $this->seccion_nombre,
        };
    }

    public function getGrupoTraducido(string $idioma = 'es'): string
    {
        return match($idioma) {
            'en' => $this->seccion_grupo_en ?: $this->seccion_grupo,
            'pt' => $this->seccion_grupo_pt ?: $this->seccion_grupo,
            default => $this->seccion_grupo,
        };
    }

    public function esPermiso(): bool
    {
        return empty($this->seccion_uri);
    }

    public function esSeccion(): bool
    {
        return !empty($this->seccion_uri);
    }

    public function scopePorSistema($query, int $sistemaId)
    {
        return $query->where('fk_sistema_id', $sistemaId);
    }

    public function scopeRaiz($query)
    {
        return $query->where('fk_seccion_id', 0);
    }

    public function scopePadres($query)
    {
        return $query->where('fk_seccion_id', 0);
    }
}
