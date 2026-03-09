<?php

namespace App\Http\Controllers;

use App\Models\Seccion;
use App\Http\Requests\SeccionRequest;
use App\Services\SeccionTreeService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class SeccionController extends Controller
{
    public function __construct(
        private SeccionTreeService $treeService
    ) {}

    public function index(Request $request)
    {
        $sistemaId = $request->input('sistema');

        $query = Seccion::query()
            ->orderBy('fk_sistema_id')
            ->orderBy('seccion_grupo')
            ->orderBy('orden');

        if ($sistemaId) {
            $query->porSistema($sistemaId);
        }

        $secciones = $query->get();
        $arbol = $this->treeService->buildTree($secciones);

        return Inertia::render('Secciones/Index', [
            'secciones' => $secciones,
            'arbol' => $arbol,
            'sistemas' => Seccion::getSistemas(),
            'sistemaActual' => $sistemaId ? (int) $sistemaId : null,
        ]);
    }

    public function create(Request $request)
    {
        $sistemaId = $request->input('sistema', 1);

        $seccionesPadre = Seccion::padres()
            ->where('fk_sistema_id', $sistemaId)
            ->orderBy('seccion_grupo')
            ->orderBy('orden')
            ->get();

        $maxOrden = Seccion::where('fk_sistema_id', $sistemaId)->max('orden') ?? 0;

        return Inertia::render('Secciones/Create', [
            'sistemas' => Seccion::getSistemas(),
            'seccionesPadre' => $seccionesPadre,
            'sistemaActual' => (int) $sistemaId,
            'siguienteOrden' => $maxOrden + 1,
        ]);
    }

    public function store(SeccionRequest $request)
    {
        $seccion = Seccion::create($request->validated());

        return redirect()
            ->route('secciones.index', ['sistema' => $seccion->fk_sistema_id])
            ->with('success', 'Sección creada correctamente.');
    }

    public function edit(Seccion $seccion)
    {
        $seccion->load(['padre', 'hijos']);

        $seccionesPadre = Seccion::where('seccion_id', '!=', $seccion->seccion_id)
            ->where('fk_sistema_id', $seccion->fk_sistema_id)
            ->padres()
            ->orderBy('seccion_grupo')
            ->orderBy('orden')
            ->get();

        return Inertia::render('Secciones/Edit', [
            'seccion' => $seccion,
            'sistemas' => Seccion::getSistemas(),
            'seccionesPadre' => $seccionesPadre,
        ]);
    }

    public function update(SeccionRequest $request, Seccion $seccion)
    {
        $seccion->update($request->validated());

        return redirect()
            ->route('secciones.index', ['sistema' => $seccion->fk_sistema_id])
            ->with('success', 'Sección actualizada correctamente.');
    }

    public function destroy(Seccion $seccion)
    {
        if ($seccion->hijos()->exists()) {
            return back()->with('error', 'No se puede eliminar una sección con subsecciones. Elimina primero las subsecciones.');
        }

        $seccion->licencias()->detach();

        $sistemaId = $seccion->fk_sistema_id;
        $seccion->delete();

        return redirect()
            ->route('secciones.index', ['sistema' => $sistemaId])
            ->with('success', 'Sección eliminada correctamente.');
    }

    public function reorder(Request $request)
    {
        $request->validate([
            'secciones' => 'required|array',
            'secciones.*.id' => 'required|integer|exists:seccion,seccion_id',
            'secciones.*.orden' => 'required|integer|min:0',
        ]);

        foreach ($request->input('secciones') as $item) {
            Seccion::where('seccion_id', $item['id'])
                ->update(['orden' => $item['orden']]);
        }

        return response()->json(['success' => true]);
    }

    public function getSeccionesPorSistema(Request $request)
    {
        $sistemaId = $request->input('sistema');

        $seccionesPadre = Seccion::padres()
            ->where('fk_sistema_id', $sistemaId)
            ->orderBy('seccion_grupo')
            ->orderBy('orden')
            ->get();

        $maxOrden = Seccion::where('fk_sistema_id', $sistemaId)->max('orden') ?? 0;

        return response()->json([
            'seccionesPadre' => $seccionesPadre,
            'siguienteOrden' => $maxOrden + 1,
        ]);
    }
}
