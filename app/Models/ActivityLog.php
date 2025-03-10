<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class ActivityLog extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'model_id',
        'model_type',
    ];

    /**
     * Get the model registered in the log.
     */
    public function loggable(): ?MorphTo
    {
        return $this->model_id !== null ? $this->morphTo('model') : null;
    }

    /**
     * Get the number of logs created for a given model per hour, day and week.
     *
     * @param  string  $model
     * @return int
     */
    public static function getModelPeriodicStats($model): array
    {
        $now = now();
        $hourAgo = $now->copy()->subHour();
        $dayAgo = $now->copy()->subDay();
        $weekAgo = $now->copy()->subWeek();

        $query = ActivityLog::where('model_type', $model);

        return [
            'hour' => $query->clone()->where('created_at', '>=', $hourAgo)->count(),
            'day' => $query->clone()->where('created_at', '>=', $dayAgo)->count(),
            'week' => $query->clone()->where('created_at', '>=', $weekAgo)->count(),
        ];
    }
}
