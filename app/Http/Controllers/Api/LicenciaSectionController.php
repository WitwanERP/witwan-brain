<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Licencia;
use App\Models\Seccion;
use App\Services\SeccionTreeService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class LicenciaSectionController extends Controller
{
    public function __construct(
        private SeccionTreeService $treeService
    ) {}

    public function getSecciones(string $key, Request $request): JsonResponse
    {
        $licencia = Licencia::where('licencia_key', $key)->first();

        if (!$licencia) {
            return response()->json([
                'error' => 'Licencia no encontrada',
            ], 404);
        }

        $sistemaId = $request->input('sistema');
        $idioma = $request->input('idioma', 'es');

        $secciones = $licencia->secciones()
            ->when($sistemaId, fn($q) => $q->where('fk_sistema_id', $sistemaId))
            ->orderBy('fk_sistema_id')
            ->orderBy('seccion_grupo')
            ->orderBy('orden')
            ->get()
            ->map(function ($seccion) use ($idioma) {
                return [
                    'id' => $seccion->seccion_id,
                    'key' => $seccion->seccion_key,
                    'sistema_id' => $seccion->fk_sistema_id,
                    'grupo' => $seccion->getGrupoTraducido($idioma),
                    'nombre' => $seccion->getNombreTraducido($idioma),
                    'uri' => $seccion->seccion_uri,
                    'icono' => $seccion->icono,
                    'orden' => $seccion->orden,
                    'padre_id' => $seccion->fk_seccion_id,
                    'es_permiso' => $seccion->esPermiso(),
                ];
            });

        return response()->json([
            'licencia' => [
                'id' => $licencia->licencia_id,
                'nombre' => $licencia->licencia_nombre,
            ],
            'secciones' => $secciones,
        ]);
    }

    public function getMenu(string $key, Request $request): JsonResponse
    {
        $licencia = Licencia::where('licencia_key', $key)->first();

        if (!$licencia) {
            return response()->json(['error' => 'Licencia no encontrada'], 404);
        }

        $sistemaId = $request->input('sistema');
        $idioma = $request->input('idioma', 'es');

        $secciones = $licencia->secciones()
            ->when($sistemaId, fn($q) => $q->where('fk_sistema_id', $sistemaId))
            ->orderBy('fk_sistema_id')
            ->orderBy('seccion_grupo')
            ->orderBy('orden')
            ->get();

        $menu = [];
        foreach ($secciones as $seccion) {
            $sistema = $seccion->fk_sistema_id;
            $grupo = $seccion->getGrupoTraducido($idioma);

            if (!isset($menu[$sistema])) {
                $menu[$sistema] = [
                    'sistema_id' => $sistema,
                    'sistema_nombre' => Seccion::getSistemas()[$sistema] ?? 'Desconocido',
                    'grupos' => [],
                ];
            }

            if (!isset($menu[$sistema]['grupos'][$grupo])) {
                $menu[$sistema]['grupos'][$grupo] = [
                    'nombre' => $grupo,
                    'icono' => $seccion->icono,
                    'items' => [],
                ];
            }

            $menu[$sistema]['grupos'][$grupo]['items'][] = [
                'id' => $seccion->seccion_id,
                'key' => $seccion->seccion_key,
                'nombre' => $seccion->getNombreTraducido($idioma),
                'uri' => $seccion->seccion_uri,
                'orden' => $seccion->orden,
                'padre_id' => $seccion->fk_seccion_id,
                'es_permiso' => $seccion->esPermiso(),
            ];
        }

        foreach ($menu as &$sistema) {
            $sistema['grupos'] = array_values($sistema['grupos']);
        }

        return response()->json([
            'licencia' => $licencia->licencia_nombre,
            'menu' => array_values($menu),
        ]);
    }

    public function verificarPermiso(string $key, string $seccionKey): JsonResponse
    {
        $licencia = Licencia::where('licencia_key', $key)->first();

        if (!$licencia) {
            return response()->json(['error' => 'Licencia no encontrada'], 404);
        }

        $tienePermiso = $licencia->secciones()
            ->where('seccion_key', $seccionKey)
            ->exists();

        return response()->json([
            'licencia' => $licencia->licencia_nombre,
            'permiso' => $seccionKey,
            'autorizado' => $tienePermiso,
        ]);
    }

    public function getArbol(string $key, Request $request): JsonResponse
    {
        $licencia = Licencia::where('licencia_key', $key)->first();

        if (!$licencia) {
            return response()->json(['error' => 'Licencia no encontrada'], 404);
        }

        $sistemaId = $request->input('sistema');

        $secciones = $licencia->secciones()
            ->when($sistemaId, fn($q) => $q->where('fk_sistema_id', $sistemaId))
            ->orderBy('fk_sistema_id')
            ->orderBy('orden')
            ->get();

        $arbol = $this->treeService->buildTree($secciones);

        return response()->json([
            'licencia' => $licencia->licencia_nombre,
            'arbol' => $arbol,
        ]);
    }
}
