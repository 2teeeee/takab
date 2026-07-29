<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'mobile',
        'password',
        'moaref_code',
        'moaref_id',
        'national_code',
        'registered_by'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class);
    }


    public function sentLetters(): HasMany
    {
        return $this->hasMany(Letter::class, 'sender_id');
    }

    public function receivedLetters(): BelongsToMany
    {
        return $this->belongsToMany(
            Letter::class,
            'letter_receivers'
        )
            ->withPivot(['status', 'read_at'])
            ->withTimestamps();
    }

    public function letterReceivers(): HasMany
    {
        return $this->hasMany(LetterReceiver::class);
    }

    public function installRequests(): HasMany
    {
        return $this->hasMany(InstallRequest::class);
    }

    public function schedules(): HasMany
    {
        return $this->hasMany(InstallSchedule::class, 'installer_id');
    }

    public function inventories(): HasMany
    {
        return $this->hasMany(ProductUser::class);
    }

    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'product_user')
            ->withPivot('quantity')
            ->withTimestamps();
    }

    public function scopeRole($query, string|array $roles)
    {
        $roles = is_array($roles) ? $roles : [$roles];

        return $query->whereHas('roles', function ($q) use ($roles) {
            $q->whereIn('name', $roles);
        });
    }

    public function hasRole(string|array $roles): bool
    {
        $userRoles = $this->roles->pluck('name')->toArray();

        if (is_array($roles)) {
            return (bool) array_intersect($roles, $userRoles);
        }

        return in_array($roles, $userRoles);
    }

    public function hasAnyRole(array|string $roles): bool
    {
        $roles = is_array($roles) ? $roles : [$roles];

        return $this->roles()
            ->whereIn('name', $roles)
            ->exists();
    }

    public function assignRole(string $roleName): void
    {
        $role = Role::where('name', $roleName)->firstOrFail();
        $this->roles()->syncWithoutDetaching([$role->id]);
    }
}
