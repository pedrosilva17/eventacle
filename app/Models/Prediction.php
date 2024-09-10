<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;

class Prediction extends Pivot
{
    use HasFactory;

    public $incrementing = true;

    protected $table = 'predictions';

    protected $fillable = ['user_id', 'user_name', 'contest_id', 'event_id', 'prediction_name', 'points'];

    /**
     * Get the event this prediction was made for.
     */
    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    /**
     * Get the user who made this prediction.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
