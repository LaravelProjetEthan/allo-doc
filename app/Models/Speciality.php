<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Practitioner;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Speciality extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'specialities';

    /**
     * Liaison avec la table 'Practitioners' (oneToMany)
     *
     * @return HasMany
     */
    public function practitioners(): HasMany
    {
        return $this->hasMany(Practitioner::class);
    }
}
