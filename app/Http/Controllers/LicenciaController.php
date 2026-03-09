<?php

namespace App\Http\Controllers;

use App\Models\Licencia;
use App\Models\Seccion;
use App\Services\SeccionTreeService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class LicenciaController extends Controller
{
    public function __construct(
        private SeccionTreeService $treeService
    ) {}

    public function index(Request $request)
    {
        $search = $request->input('search');

        $licencias = Licencia::query()
            ->when($search, fn($q) => $q->where('licencia_nombre', 'like', "%{$search}%"))
            ->withCount('secciones')
            ->orderBy('licencia_nombre')
            ->paginate(20)
            ->withQueryString();

        return Inertia::render('Licencias/Index', [
            'licencias' => $licencias,
            'filters' => [
                'search' => $search,
            ],
        ]);
    }

    public function show(Licencia $licencia, Request $request)
    {
        $sistemaId = $request->input('sistema');

        $seccionesAsignadas = $licencia->secciones()
            ->when($sistemaId, fn($q) => $q->where('fk_sistema_id', $sistemaId))
            ->orderBy('fk_sistema_id')
            ->orderBy('seccion_grupo')
            ->orderBy('orden')
            ->pluck('seccion_id')
            ->toArray();

        $todasSecciones = Seccion::query()
            ->when($sistemaId, fn($q) => $q->porSistema($sistemaId))
            ->orderBy('fk_sistema_id')
            ->orderBy('seccion_grupo')
            ->orderBy('orden')
            ->get();

        $arbol = $this->treeService->buildTree($todasSecciones);

        return Inertia::render('Licencias/Show', [
            'licencia' => $licencia,
            'todasSecciones' => $todasSecciones,
            'arbol' => $arbol,
            'seccionesAsignadas' => $seccionesAsignadas,
            'sistemas' => Seccion::getSistemas(),
            'sistemaActual' => $sistemaId ? (int) $sistemaId : null,
        ]);
    }

    public function syncSecciones(Request $request, Licencia $licencia)
    {
        $request->validate([
            'secciones' => 'array',
            'secciones.*' => 'integer|exists:seccion,seccion_id',
        ]);

        $licencia->secciones()->sync($request->input('secciones', []));

        return back()->with('success', 'Secciones actualizadas correctamente.');
    }

    public function toggleSeccion(Request $request, Licencia $licencia)
    {
        $request->validate([
            'seccion_id' => 'required|integer|exists:seccion,seccion_id',
        ]);

        $seccionId = $request->input('seccion_id');

        if ($licencia->secciones()->where('seccion_id', $seccionId)->exists()) {
            $licencia->secciones()->detach($seccionId);
            $action = 'removed';
        } else {
            $licencia->secciones()->attach($seccionId);
            $action = 'added';
        }

        return response()->json([
            'success' => true,
            'action' => $action,
        ]);
    }

    public function copiarSecciones(Request $request, Licencia $licencia)
    {
        $request->validate([
            'licencia_origen_id' => 'required|integer|exists:licencia,licencia_id',
        ]);

        $licenciaOrigen = Licencia::findOrFail($request->input('licencia_origen_id'));
        $seccionesOrigen = $licenciaOrigen->secciones()->pluck('seccion_id')->toArray();

        $licencia->secciones()->sync($seccionesOrigen);

        return back()->with('success', "Se copiaron {$seccionesOrigen} secciones de {$licenciaOrigen->licencia_nombre}.");
    }

    public function create()
    {
        $licenciasPadre = Licencia::orderBy('licencia_nombre')->get(['licencia_id', 'licencia_nombre']);

        return Inertia::render('Licencias/Create', [
            'licenciasPadre' => $licenciasPadre,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'licencia_nombre' => 'required|string|max:255',
            'licencia_facturacion' => 'nullable|string|max:255',
            'licencia_key' => 'required|string|max:30|unique:licencia,licencia_key',
            'licencia_base' => 'required|string|max:150',
            'licencia_logo_login' => 'nullable|string|max:150',
            'licencia_logo_voucher' => 'nullable|string|max:150',
            'licencia_logo' => 'nullable|string|max:150',
            'licencia_url' => 'nullable|string|max:200',
            'licencia_pais' => 'nullable|string|max:200',
            'fk_licencia_id' => 'nullable|integer|exists:licencia,licencia_id',
            'base_colectora' => 'nullable|string|max:150',
            'activar_cron' => 'boolean',
            'prioridad' => 'nullable|integer|min:0|max:9',
            'host_db' => 'nullable|string|max:150',
            'user_db' => 'nullable|string|max:150',
            'pass_db' => 'nullable|string|max:150',
            'app_url' => 'nullable|string|max:150',
        ]);

        // Valores por defecto para campos vacíos
        $validated['licencia_facturacion'] = $validated['licencia_facturacion'] ?? '';
        $validated['licencia_logo_login'] = $validated['licencia_logo_login'] ?? '';
        $validated['licencia_logo_voucher'] = $validated['licencia_logo_voucher'] ?? '';
        $validated['licencia_logo'] = $validated['licencia_logo'] ?? '';
        $validated['licencia_url'] = $validated['licencia_url'] ?? '';
        $validated['licencia_pais'] = $validated['licencia_pais'] ?? '';
        $validated['fk_licencia_id'] = $validated['fk_licencia_id'] ?? 0;
        $validated['base_colectora'] = $validated['base_colectora'] ?? '';
        $validated['activar_cron'] = $validated['activar_cron'] ?? false;
        $validated['prioridad'] = $validated['prioridad'] ?? 0;
        $validated['host_db'] = $validated['host_db'] ?? '';
        $validated['user_db'] = $validated['user_db'] ?? '';
        $validated['pass_db'] = $validated['pass_db'] ?? '';
        $validated['app_url'] = $validated['app_url'] ?? '';

        Licencia::create($validated);

        return redirect()->route('licencias.index')->with('success', 'Licencia creada correctamente.');
    }

    public function edit(Licencia $licencia)
    {
        $licenciasPadre = Licencia::where('licencia_id', '!=', $licencia->licencia_id)
            ->orderBy('licencia_nombre')
            ->get(['licencia_id', 'licencia_nombre']);

        // Mostrar campos ocultos para edición
        $licencia->makeVisible(['host_db', 'user_db', 'pass_db']);

        return Inertia::render('Licencias/Edit', [
            'licencia' => $licencia,
            'licenciasPadre' => $licenciasPadre,
        ]);
    }

    public function update(Request $request, Licencia $licencia)
    {
        $validated = $request->validate([
            'licencia_nombre' => 'required|string|max:255',
            'licencia_facturacion' => 'nullable|string|max:255',
            'licencia_key' => 'required|string|max:30|unique:licencia,licencia_key,' . $licencia->licencia_id . ',licencia_id',
            'licencia_base' => 'required|string|max:150',
            'licencia_logo_login' => 'nullable|string|max:150',
            'licencia_logo_voucher' => 'nullable|string|max:150',
            'licencia_logo' => 'nullable|string|max:150',
            'licencia_url' => 'nullable|string|max:200',
            'licencia_pais' => 'nullable|string|max:200',
            'fk_licencia_id' => 'nullable|integer|exists:licencia,licencia_id',
            'base_colectora' => 'nullable|string|max:150',
            'activar_cron' => 'boolean',
            'prioridad' => 'nullable|integer|min:0|max:9',
            'host_db' => 'nullable|string|max:150',
            'user_db' => 'nullable|string|max:150',
            'pass_db' => 'nullable|string|max:150',
            'app_url' => 'nullable|string|max:150',
        ]);

        // Valores por defecto para campos vacíos
        $validated['licencia_facturacion'] = $validated['licencia_facturacion'] ?? '';
        $validated['licencia_logo_login'] = $validated['licencia_logo_login'] ?? '';
        $validated['licencia_logo_voucher'] = $validated['licencia_logo_voucher'] ?? '';
        $validated['licencia_logo'] = $validated['licencia_logo'] ?? '';
        $validated['licencia_url'] = $validated['licencia_url'] ?? '';
        $validated['licencia_pais'] = $validated['licencia_pais'] ?? '';
        $validated['fk_licencia_id'] = $validated['fk_licencia_id'] ?? 0;
        $validated['base_colectora'] = $validated['base_colectora'] ?? '';
        $validated['activar_cron'] = $validated['activar_cron'] ?? false;
        $validated['prioridad'] = $validated['prioridad'] ?? 0;
        $validated['host_db'] = $validated['host_db'] ?? '';
        $validated['user_db'] = $validated['user_db'] ?? '';
        $validated['app_url'] = $validated['app_url'] ?? '';

        // Solo actualizar contraseña si se proporciona una nueva
        if (empty($validated['pass_db'])) {
            unset($validated['pass_db']);
        }

        $licencia->update($validated);

        return redirect()->route('licencias.index')->with('success', 'Licencia actualizada correctamente.');
    }

    public function destroy(Licencia $licencia)
    {
        // Eliminar relaciones con secciones
        $licencia->secciones()->detach();

        $licencia->delete();

        return redirect()->route('licencias.index')->with('success', 'Licencia eliminada correctamente.');
    }
}
