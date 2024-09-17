<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contest extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description', 'options', 'result', 'event_id'];

    /**
     * Get the event that the contest belongs to.
     */
    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    /**
     * Get the predicted made for this contest.
     */
    public function predictions()
    {
        return $this->hasMany(Prediction::class);
    }
}
