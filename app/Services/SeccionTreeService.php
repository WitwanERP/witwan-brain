<?php

namespace App\Services;

use App\Models\Seccion;
use Illuminate\Support\Collection;

class SeccionTreeService
{
    public function buildTree(Collection $secciones, int $parentId = 0): array
    {
        $tree = [];

        foreach ($secciones as $seccion) {
            if ($seccion->fk_seccion_id === $parentId) {
                $children = $this->buildTree($secciones, $seccion->seccion_id);

                $node = [
                    'id' => $seccion->seccion_id,
                    'nombre' => $seccion->seccion_nombre,
                    'nombre_en' => $seccion->seccion_nombre_en,
                    'nombre_pt' => $seccion->seccion_nombre_pt,
                    'key' => $seccion->seccion_key,
                    'grupo' => $seccion->seccion_grupo,
                    'uri' => $seccion->seccion_uri,
                    'icono' => $seccion->icono,
                    'sistema_id' => $seccion->fk_sistema_id,
                    'padre_id' => $seccion->fk_seccion_id,
                    'orden' => $seccion->orden,
                    'es_permiso' => $seccion->esPermiso(),
                    'children' => $children,
                ];

                $tree[] = $node;
            }
        }

        usort($tree, fn($a, $b) => $a['orden'] <=> $b['orden']);

        return $tree;
    }

    public function buildTreeBySistema(int $sistemaId = null): array
    {
        $query = Seccion::query()->orderBy('fk_sistema_id')->orderBy('orden');

        if ($sistemaId) {
            $query->porSistema($sistemaId);
        }

        $secciones = $query->get();

        return $this->buildTree($secciones);
    }

    public function buildTreeForLicencia(int $licenciaId, int $sistemaId = null): array
    {
        $query = Seccion::query()
            ->whereHas('licencias', fn($q) => $q->where('licencia_id', $licenciaId))
            ->orderBy('fk_sistema_id')
            ->orderBy('orden');

        if ($sistemaId) {
            $query->porSistema($sistemaId);
        }

        $secciones = $query->get();

        return $this->buildTree($secciones);
    }
}
