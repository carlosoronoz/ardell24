<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;

class User extends Authenticatable  implements FilamentUser
{
    use HasFactory, Notifiable;
    use HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'type_passport',
        'passport',
        'name',
        'surname',
        'email',
        'phone',
        'password',
        'image',
        'certificate',
        'status_certificate',
        'account_deleted'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'account_deleted' => 'boolean',
        'status_certificate' => 'boolean',
    ];

    public function canAccessPanel(Panel $panel): bool
    {
        return $this->hasPermissionTo('Panel');
    }

    public function Address()
    {
        return $this->hasMany(UserAddress::class, 'user_id', 'id');
    }
}
