<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Prediction extends Model
{
    use HasFactory;

    protected $fillable = ['prediction_name', 'points'];

    protected static function boot()
    {
        parent::boot();

        /**
         * Create an activity log when a prediction is created.
         */
        static::created(function ($event) {
            ActivityLog::create([
                'model_id' => $event->id,
                'model_type' => get_class($event),
            ]);
        });

        /**
         * Nullify the model_id in the activity log when a prediction is deleted.
         */
        static::deleted(function ($event) {
            ActivityLog::where('model_type', get_class($event))
                ->where('model_id', $event->id)
                ->update(['model_id' => null]);
        });
    }

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
