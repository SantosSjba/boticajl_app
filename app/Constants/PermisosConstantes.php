<?php

namespace App\Constants;

/**
 * Constantes de permisos y menús por rol (sistema antiguo migrado).
 * Centraliza roles y claves de permisos para menús y rutas.
 */
final class PermisosConstantes
{
    /*
    |--------------------------------------------------------------------------
    | Roles (deben coincidir con config/permisos_farmacia.php y BD)
    |--------------------------------------------------------------------------
    */
    public const ROLE_ADMINISTRADOR = 'ADMINISTRADOR';
    public const ROLE_USUARIO = 'USUARIO';

    /** @var array<string> Roles válidos del sistema */
    public const ROLES_VALIDOS = [
        self::ROLE_ADMINISTRADOR,
        self::ROLE_USUARIO,
    ];

    /*
    |--------------------------------------------------------------------------
    | Claves de permisos / menús (para administración y lógica de visibilidad)
    |--------------------------------------------------------------------------
    */
    public const MENU_INICIO = 'inicio';
    public const MENU_CONSULTAS = 'consultas';
    public const MENU_MANTENIMIENTO = 'mantenimiento';
    public const MENU_CLIENTES = 'clientes';
    public const MENU_VENTAS = 'ventas';
    public const MENU_CAJA = 'caja';
    public const MENU_REPORTES = 'reportes';
    public const MENU_COMPRAS = 'compras';
    public const MENU_BACKUP = 'backup';
    public const MENU_CONFIGURACION = 'configuracion';
    public const MENU_PERFIL = 'perfil';

    /** Menús que solo ve ADMINISTRADOR */
    public const MENUS_SOLO_ADMINISTRADOR = [
        self::MENU_MANTENIMIENTO,  // como grupo completo; USUARIO solo ve "Clientes"
        self::MENU_COMPRAS,
        self::MENU_BACKUP,
        self::MENU_CONFIGURACION,
    ];

    /** Menú Ventas: visible solo si caja abierta (config: ventas_requiere_caja_abierta) */
    public const MENU_VENTAS_REQUIERE_CAJA_ABIERTA = true;

    /** Subítems de Caja: Apertura solo si no hay caja abierta */
    public const CAJA_APERTURA_SOLO_SI_CERRADA = true;
    /** Subítems de Caja: Cierre solo si hay caja abierta */
    public const CAJA_CIERRE_SOLO_SI_ABIERTA = true;

    /**
     * Reportes: subítems solo para ADMINISTRADOR (Rpt. Ventas, Ingresos por pago, Rpt. Compras, etc.)
     * Todos ven: Rpt. Ventas del día.
     */
    public const REPORTES_SUBITEMS_SOLO_ADMIN = [
        'reportes.ventas.rango',
        'reportes.ingresos-por-pago',
        'reportes.compras.rango',
        'reportes.compras.dia',
    ];

    public static function roles(): array
    {
        return config('permisos_farmacia.roles', [
            self::ROLE_ADMINISTRADOR => self::ROLE_ADMINISTRADOR,
            self::ROLE_USUARIO       => self::ROLE_USUARIO,
        ]);
    }

    public static function rutasSoloAdministrador(): array
    {
        return config('permisos_farmacia.rutas_solo_administrador', []);
    }

    public static function ventasRequiereCajaAbierta(): bool
    {
        return config('permisos_farmacia.ventas_requiere_caja_abierta', true);
    }
}
