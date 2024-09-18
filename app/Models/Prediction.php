<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Prediction extends Model
{
    use HasFactory;

    protected $fillable = ['prediction_name', 'points'];

    /**
     * Get the contest this prediction was made for.
     */
    public function contest(): BelongsTo
    {
        return $this->belongsTo(Contest::class);
    }

    /**
     * Get the event this prediction was made for.
     */
    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    /**
     * Get the (registered) user who made this prediction.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
