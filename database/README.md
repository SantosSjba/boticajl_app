# Base de datos – Botica J&L

## MySQL (BD existente)

El proyecto usa **MySQL** por defecto (`config/database.php` y `.env.example` con `DB_CONNECTION=mysql`). La base de datos es la misma que la del proyecto antiguo: **`factosys_boticajl`**. Los datos de los seeders (tipo_documento, tipo_afectacion, unidad, moneda) usan los mismos IDs y códigos SUNAT que la BD existente para no romper referencias.

## Esquema vía migraciones

El esquema está definido en **migraciones** en `database/migrations/`, equivalentes a **`factosys_boticajl.sql`** (referencia en la raíz).

- **Migraciones 2025_03_06_***: crean todas las tablas (cache, catalogos, cliente, usuario, productos, venta, detalleventa, caja, jobs, sessions, users, etc.) en el orden correcto de dependencias (FK).
- Cada migración usa `Schema::hasTable()` antes de crear, así que puedes ejecutar `php artisan migrate` sobre una BD vacía o sobre una que ya tenga las tablas (p. ej. volcado previo del SQL).

## Crear la BD desde cero (MySQL)

```bash
# Crear la BD en MySQL (ejemplo: factosys_boticajl)
# En .env: DB_CONNECTION=mysql, DB_DATABASE=factosys_boticajl, DB_USERNAME, DB_PASSWORD

php artisan migrate
php artisan db:seed   # datos mínimos de catálogos (tipo_documento, tipo_afectacion, unidad, moneda)
```

## Seeders y BD existente

- Los seeders insertan solo si la tabla está vacía (`exists()`), así que son seguros sobre una BD que ya tiene datos.
- **IDs y códigos** de tipo_documento (1=DNI, 6=RUC, 4=Carné), tipo_afectacion (10=Gravado, 20=Exonerado, 30=Inafecto), unidad (iduni 1=NIU como en el proyecto antiguo) y moneda (PEN, USD) coinciden con la BD existente y con el uso en el código (p. ej. `idunidad = 1` por defecto en productos).

## Cambios futuros

- Nuevos cambios de esquema: `php artisan make:migration` y mantener el historial en `database/migrations/`.
- Nuevos datos iniciales: crear seeders en `database/seeders/` y registrarlos en `DatabaseSeeder`.
