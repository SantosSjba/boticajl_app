<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Usuario extends Authenticatable
{
    protected $table = 'usuario';

    protected $primaryKey = 'idusu';

    public $timestamps = false;

    protected $fillable = [
        'usuario',
        'clave',
        'cargo_usu',
        'nombres',
        'email',
        'telefono',
        'fechaingreso',
        'tipo',
        'estado',
    ];

    protected $hidden = [
        'clave',
    ];

    public function getAuthIdentifierName(): string
    {
        return 'idusu';
    }

    public function getAuthPassword(): string
    {
        return $this->clave;
    }

    public function hasLegacyPassword(): bool
    {
        $clave = $this->getAuthPassword();
        return strlen($clave) === 32 && ctype_xdigit($clave);
    }

    public function getRememberTokenName(): ?string
    {
        return null;
    }
}
