<?php

namespace App\Http\Controllers;

use App\Helpers\PermisosHelper;
use App\Models\CajaApertura;
use App\Models\Configuracion;
use App\Models\Producto;
use App\Models\Usuario;
use App\Models\Venta;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    public function index(Request $request): Response
    {
        $data = $this->getData($request);
        $user = auth()->user();
        $usu = $user?->usuario ?? '';
        $hoy = now()->toDateString();
        $config = Configuracion::first();
        $razonSocial = $config?->razon_social ?? '';
        $simboloMoneda = $data['simboloMoneda'];
        $cajaHoy = CajaApertura::where('usuario', $usu)->where('fecha', $hoy)->orderByDesc('idcaja_a')->first();
        $montoCaja = $cajaHoy ? (float) $cajaHoy->monto : 0;

        $dias = ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'];
        $meses = ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];
        $fechaTexto = $dias[(int) date('w')].' '.date('d').' de '.$meses[(int) date('n') - 1].' del '.date('Y');

        $isAdministrador = PermisosHelper::isAdministrador();

        $props = [
            'title' => 'Inicio',
            'fechaTexto' => $fechaTexto,
            'usuario' => $usu,
            'razonSocial' => $razonSocial,
            'montoCaja' => $montoCaja,
            'simboloMoneda' => $simboloMoneda,
            'isAdministrador' => $isAdministrador,
            'showComprasTile' => $isAdministrador,
            'productosPorVencer' => $data['productosPorVencer'],
            'productosBajoStock' => $data['productosBajoStock'],
        ];

        if ($isAdministrador) {
            $props['fechaDesde'] = $data['fechaDesde'];
            $props['fechaHasta'] = $data['fechaHasta'];
            $props['filtroUsuario'] = $data['filtroUsuario'];
            $props['listaUsuarios'] = collect($data['listaUsuarios'])->map(fn ($u) => [
                'idusu' => $u->idusu ?? $u['idusu'],
                'usuario' => $u->usuario ?? $u['usuario'] ?? '',
                'nombres' => $u->nombres ?? $u['nombres'] ?? null,
            ])->values()->all();
            $props['ventas'] = $data['ventas'];
            $props['costos'] = $data['costos'];
            $props['ganancia'] = $data['ganancia'];
            $props['gastos'] = $data['gastos'];
            $props['neto'] = $data['neto'];
        }

        return Inertia::render('Dashboard', $props);
    }

    /** @return array<string, mixed> */
    protected function getData(Request $request): array
    {
        try {
            return $this->getDataInternal($request);
        } catch (\Throwable $e) {
            report($e);
            if (config('app.debug')) {
                throw $e;
            }
            return [
                'fechaDesde' => now()->startOfMonth()->toDateString(),
                'fechaHasta' => now()->endOfMonth()->toDateString(),
                'filtroUsuario' => 0,
                'listaUsuarios' => [],
                'ventas' => 0,
                'costos' => 0,
                'ganancia' => 0,
                'gastos' => 0,
                'neto' => 0,
                'productosPorVencer' => [],
                'productosBajoStock' => [],
                'simboloMoneda' => 'S/',
            ];
        }
    }

    /** @return array<string, mixed> */
    protected function getDataInternal(Request $request): array
    {
        $primerDiaMes = now()->startOfMonth()->toDateString();
        $ultimoDiaMes = now()->endOfMonth()->toDateString();
        $fechaDesde = $request->input('fecha_desde', $primerDiaMes);
        $fechaHasta = $request->input('fecha_hasta', $ultimoDiaMes);
        $filtroUsuario = (int) $request->input('filtro_usuario', 0);

        if (! strtotime($fechaDesde) || ! strtotime($fechaHasta)) {
            $fechaDesde = $primerDiaMes;
            $fechaHasta = $ultimoDiaMes;
        }
        if ($fechaDesde > $fechaHasta) {
            $fechaDesde = $primerDiaMes;
            $fechaHasta = $ultimoDiaMes;
        }

        $listaUsuarios = Usuario::where('estado', 'Activo')
            ->orderBy('nombres')
            ->get(['idusu', 'usuario', 'nombres']);

        $queryVentas = Venta::whereBetween('fecha_emision', [$fechaDesde.' 00:00:00', $fechaHasta.' 23:59:59'])
            ->where('estado', '!=', 'anulado');
        if ($filtroUsuario > 0) {
            $queryVentas->where('idusuario', $filtroUsuario);
        }
        $ventas = (float) $queryVentas->sum('total');

        $queryCostos = DB::table('detalleventa')
            ->join('productos', 'detalleventa.idproducto', '=', 'productos.idproducto')
            ->join('venta', 'detalleventa.idventa', '=', 'venta.idventa')
            ->whereBetween('venta.fecha_emision', [$fechaDesde.' 00:00:00', $fechaHasta.' 23:59:59'])
            ->where('venta.estado', '!=', 'anulado');
        if ($filtroUsuario > 0) {
            $queryCostos->where('venta.idusuario', $filtroUsuario);
        }
        $costos = (float) $queryCostos->selectRaw(
            'COALESCE(SUM(LEAST(productos.precio_compra * detalleventa.cantidad, detalleventa.importe_total)), 0) as total'
        )->value('total');

        $ganancia = $ventas - $costos;
        $gastos = 0;
        $neto = $ganancia - $gastos;

        $productosPorVencer = Producto::query()
            ->join('lote', 'productos.idlote', '=', 'lote.idlote')
            ->whereRaw('DATE_SUB(lote.fecha_vencimiento, INTERVAL 14 DAY) <= CURDATE()')
            ->select('productos.*', 'lote.fecha_vencimiento')
            ->orderBy('lote.fecha_vencimiento')
            ->get()
            ->map(function ($p) {
                $fecVen = $p->fecha_vencimiento ?? $p->getAttribute('fecha_vencimiento');
                $fecVenStr = $fecVen instanceof \DateTimeInterface
                    ? $fecVen->format('Y-m-d')
                    : ($fecVen ? (string) $fecVen : null);

                return [
                    'idproducto' => $p->idproducto,
                    'codigo' => $p->codigo,
                    'descripcion' => $p->descripcion,
                    'fecha_vencimiento' => $fecVenStr ? Carbon::parse($fecVenStr)->format('Y-m-d') : null,
                    'precio_venta' => (float) $p->precio_venta,
                    'estado' => $p->estado,
                ];
            });

        $productosBajoStock = Producto::query()
            ->join('lote', 'productos.idlote', '=', 'lote.idlote')
            ->whereRaw('productos.stock <= GREATEST(COALESCE(productos.stockminimo, 0), 1)')
            ->select('productos.*', 'lote.fecha_vencimiento')
            ->orderBy('productos.stock')
            ->get()
            ->map(function ($p) {
                $fecVen = $p->fecha_vencimiento ?? $p->getAttribute('fecha_vencimiento');
                $fecVenStr = $fecVen instanceof \DateTimeInterface
                    ? $fecVen->format('Y-m-d')
                    : ($fecVen ? (string) $fecVen : null);

                return [
                    'idproducto' => $p->idproducto,
                    'codigo' => $p->codigo,
                    'descripcion' => $p->descripcion,
                    'fecha_vencimiento' => $fecVenStr ? Carbon::parse($fecVenStr)->format('Y-m-d') : null,
                    'stock' => (int) $p->stock,
                    'precio_venta' => (float) $p->precio_venta,
                    'estado' => $p->estado,
                ];
            });

        $simboloMoneda = Configuracion::first()?->simbolo_moneda ?? 'S/';

        return [
            'fechaDesde' => $fechaDesde,
            'fechaHasta' => $fechaHasta,
            'filtroUsuario' => $filtroUsuario,
            'listaUsuarios' => $listaUsuarios,
            'ventas' => $ventas,
            'costos' => $costos,
            'ganancia' => $ganancia,
            'gastos' => $gastos,
            'neto' => $neto,
            'productosPorVencer' => $productosPorVencer,
            'productosBajoStock' => $productosBajoStock,
            'simboloMoneda' => $simboloMoneda,
        ];
    }
}
