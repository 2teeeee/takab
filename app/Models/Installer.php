<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Installer extends Model
{
    protected $fillable = [
        'user_id',
        'address',
        'experience',
        'description',
        'status',
        'status_note',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function wholesalers(): BelongsToMany
    {
        return $this->belongsToMany(
            User::class,
            'installer_wholesaler',
            'installer_id',
            'wholesaler_id'
        )->withTimestamps();
    }
}
