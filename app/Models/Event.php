<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Event extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'slug', 'description', 'creator_id', 'start_time'];

    /**
     * Automatically generate a url slug when creating an event.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($event) {
            if (empty($event->slug)) {
                $event->slug =
                    str_replace(' ', '-',
                        preg_replace('/\s+/', ' ',
                            preg_replace('/[^A-Za-z0-9 ]/', '',
                                iconv('UTF-8', 'ASCII//TRANSLIT',
                                    strtolower($event->name))))).
                    '-'.
                    md5($event->name.$event->creator_id);
            }
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
