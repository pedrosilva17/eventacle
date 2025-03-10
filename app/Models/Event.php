<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Event extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description', 'start_time', 'scoring_type'];

    protected static function boot()
    {
        parent::boot();

        /**
         * Automatically generate a url slug when creating an event.
         */
        static::creating(function ($event) {
            if (empty($event->slug)) {
                $event->slug =
                    str_replace(' ', '-',
                        preg_replace('/\s+/', ' ',
                            preg_replace('/[^A-Za-z0-9 ]/', '',
                                iconv('UTF-8', 'ASCII//TRANSLIT',
                                    strtolower($event->name))))).
                    '-'.
                    md5(Str::random(16).$event->name.$event->creator_id);
            }
        });

        /**
         * Create an activity log when an event is created.
         */
        static::created(function ($event) {
            ActivityLog::create([
                'model_id' => $event->id,
                'model_type' => get_class($event),
            ]);
        });

        /**
         * Nullify the model_id in the activity log when an event is deleted.
         */
        static::deleted(function ($event) {
            ActivityLog::where('model_type', get_class($event))
                ->where('model_id', $event->id)
                ->update(['model_id' => null]);
        });
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }

    /**
     * Get the contests for the event.
     */
    public function contests(): HasMany
    {
        return $this->hasMany(Contest::class);
    }

    /**
     * Get the creator of the event.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'creator_id');
    }

    /**
     * Get the predictions made for this event.
     *
     * @return void
     */
    public function predictions(): HasMany
    {
        return $this->hasMany(Prediction::class);
    }

    public function predictionsByContest(): Collection
    {
        return $this->predictions()->orderByDesc('points')->get()->groupBy('contest_id');
    }

    /**
     * Get the prediction leaderboard for this event.
     *
     * @return void
     */
    public function leaderboard(): HasMany
    {
        return $this->hasMany(LeaderboardEntry::class)->orderByDesc('score');
    }
}
