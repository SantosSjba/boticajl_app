<?php

namespace App\Helpers;

use App\Constants\PermisosConstantes;

/**
 * Menú principal filtrado por rol y estado de caja (igual que proyecto antiguo).
 * Visibilidad por rol y caja según PermisosConstantes y config/permisos_farmacia.php.
 */
class MenuHelper
{
    public static function getMainNavItems(): array
    {
        $items = [];
        $routeCajaApertura = route('caja.apertura');
        $routeCajaCierre = route('caja.cierre');
        $routeCajaSeguimiento = route('caja.seguimiento');

        $items[] = [
            'icon' => 'dashboard',
            'name' => 'Inicio',
            'path' => '/dashboard',
        ];

        $items[] = [
            'name' => 'Consultas',
            'icon' => 'tables',
            'path' => '#',
            'subItems' => [
                ['name' => 'Productos farmacéuticos', 'path' => route('consulta.productos')],
            ],
        ];

        if (PermisosHelper::isAdministrador()) {
            $items[] = [
                'name' => 'Mantenimiento',
                'icon' => 'task',
                'path' => '#',
                'subItems' => [
                    ['name' => 'Cliente / Laboratorio', 'path' => route('mantenimiento.clientes.index')],
                    ['name' => 'Producto', 'path' => route('mantenimiento.productos.index')],
                    ['name' => 'Forma farmacéutica', 'path' => route('mantenimiento.categorias.index')],
                    ['name' => 'Presentación', 'path' => route('mantenimiento.presentaciones.index')],
                    ['name' => 'Usuario', 'path' => route('mantenimiento.usuarios.index')],
                    ['name' => 'Síntomas', 'path' => route('mantenimiento.sintomas.index')],
                    ['name' => 'Lote', 'path' => route('mantenimiento.lotes.index')],
                ],
            ];
        } else {
            $items[] = [
                'name' => 'Clientes',
                'icon' => 'user-profile',
                'path' => route('mantenimiento.clientes.index'),
            ];
        }

        if (PermisosHelper::puedeVerMenuVentas()) {
            $items[] = [
                'name' => 'Ventas',
                'icon' => 'ecommerce',
                'path' => '#',
                'subItems' => [
                    ['name' => 'Ventas', 'path' => route('ventas.index')],
                    ['name' => 'Consulta ventas', 'path' => route('ventas.consulta.index')],
                    ['name' => 'Consulta tickets', 'path' => route('ventas.tickets.index')],
                    ['name' => 'Nota de crédito', 'path' => route('notacredito.index')],
                ],
            ];
        }

        $cajaSubItems = [];
        if (PermisosHelper::puedeVerCajaApertura()) {
            $cajaSubItems[] = ['name' => 'Apertura de caja', 'path' => $routeCajaApertura];
        }
        if (PermisosHelper::puedeVerCajaCierre()) {
            $cajaSubItems[] = ['name' => 'Cierre de caja', 'path' => $routeCajaCierre];
        }
        if (PermisosHelper::puedeVerCajaSeguimiento()) {
            $cajaSubItems[] = ['name' => 'Seguimiento de caja', 'path' => $routeCajaSeguimiento];
            $cajaSubItems[] = ['name' => 'Cuadre de caja', 'path' => route('reportes.cuadrecaja')];
        }
        if (! empty($cajaSubItems)) {
            $items[] = [
                'name' => 'Caja',
                'icon' => 'support-ticket',
                'path' => '#',
                'subItems' => $cajaSubItems,
            ];
        }

        $items[] = [
            'name' => 'Reportes',
            'icon' => 'charts',
            'path' => '#',
            'subItems' => array_values(array_filter([
                ['name' => 'Rpt. Ventas del día', 'path' => route('reportes.ventas.dia')],
                PermisosHelper::isAdministrador() ? ['name' => 'Rpt. Ventas', 'path' => route('reportes.ventas.rango')] : null,
                PermisosHelper::isAdministrador() ? ['name' => 'Ingresos por tipo de pago', 'path' => route('reportes.ingresos-por-pago')] : null,
                PermisosHelper::isAdministrador() ? ['name' => 'Rpt. Compras', 'path' => route('reportes.compras.rango')] : null,
                PermisosHelper::isAdministrador() ? ['name' => 'Rpt. Compras del día', 'path' => route('reportes.compras.dia')] : null,
            ])),
        ];

        if (PermisosHelper::isAdministrador()) {
            $items[] = [
                'name' => 'Compras',
                'icon' => 'ecommerce',
                'path' => '#',
                'subItems' => [
                    ['name' => 'Compras', 'path' => route('compras.create')],
                    ['name' => 'Consulta compras', 'path' => route('compras.consulta.index')],
                ],
            ];
            $items[] = ['icon' => 'task', 'name' => 'Backup', 'path' => route('backup.index')];
            $items[] = ['name' => 'Configuración', 'icon' => 'ui-elements', 'path' => route('configuracion.index')];
        }

        return $items;
    }

    public static function getOthersItems(): array
    {
        return [
            [
                'icon' => 'user-profile',
                'name' => 'Perfil',
                'path' => route('profile'),
            ],
        ];
    }

    public static function getMenuGroups(): array
    {
        return [
            [
                'title' => 'Sistema Farmacia',
                'items' => self::getMainNavItems(),
            ],
            [
                'title' => 'Cuenta',
                'items' => self::getOthersItems(),
            ],
        ];
    }

    /** Icon names for frontend (Vue uses same keys to render SVG). */
    public static function getIconNames(): array
    {
        return ['dashboard', 'ecommerce', 'user-profile', 'task', 'tables', 'charts', 'ui-elements', 'support-ticket'];
    }
}
