<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Practitioner;
use App\Models\Patient;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Appointment extends Model
{
    /**
     * Liaison avec la table 'Patients' (oneToMany)
     *
     * @return BelongsTo
     */
    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    /**
     * Liaison avec la table 'Practitioners' (oneToMany)
     *
     * @return BelongsTo
     */
    public function practitioner(): BelongsTo
    {
        return $this->belongsTo(Practitioner::class);
    }

    /**
     * Change le comportement standard de la suppression
     * et le status du rendez-vous
     *
     * @return void
     */
    public function delete()
    {
        $this->status = 'canceled';
        $this->save();
    }
}
