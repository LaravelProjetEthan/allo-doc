<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Speciality;
use App\Models\Appointment;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Practitioner extends Model
{
    protected $fillable = [
        'firstname',
        'lastname',
        'email',
        'address',
        'zipcode',
        'city',
        'speciality_id',
        'user_id'
    ];

    /**
     * Liaison avec la table 'Users' (oneToMany)
     *
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Liaison avec la table 'Specialities' (oneToMany)
     *
     * @return BelongsTo
     */
    public function speciality(): BelongsTo
    {
        return $this->belongsTo(Speciality::class);
    }

    /**
     * Liaison avec la table 'Appointments' (ManyToMany)
     *
     * @return HasMany
     */
    public function appointments(): HasMany
    {
        return $this->hasMany(Appointment::class);
    }
}
