<?php

namespace App\Http\Controllers;

use App\Models\Licencia;
use App\Models\Seccion;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_licencias' => Licencia::count(),
            'total_secciones' => Seccion::count(),
            'secciones_por_sistema' => Seccion::select('fk_sistema_id', DB::raw('count(*) as total'))
                ->groupBy('fk_sistema_id')
                ->orderBy('fk_sistema_id')
                ->get()
                ->map(fn($item) => [
                    'sistema_id' => $item->fk_sistema_id,
                    'nombre' => Seccion::getSistemas()[$item->fk_sistema_id] ?? 'Otro',
                    'total' => $item->total,
                ]),
            'licencias_recientes' => Licencia::orderBy('licencia_id', 'desc')
                ->limit(5)
                ->get(['licencia_id', 'licencia_nombre', 'licencia_pais'])
                ->map(fn($l) => [
                    'id' => $l->licencia_id,
                    'nombre' => $l->licencia_nombre,
                    'pais' => $l->licencia_pais,
                ]),
        ];

        return Inertia::render('Dashboard', [
            'stats' => $stats,
        ]);
    }
}
