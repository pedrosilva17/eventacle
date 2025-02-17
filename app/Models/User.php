<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    use HasProfilePhoto;
    use Notifiable;
    use TwoFactorAuthenticatable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array<int, string>
     */
    protected $appends = [
        'profile_photo_url',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    protected static function boot()
    {
        parent::boot();

        static::updated(function ($user) {
            if ($user->isDirty('name')) {
                \DB::table('predictions')
                    ->where('user_id', $user->id)
                    ->update(['user_name' => $user->name]);
                \DB::table('leaderboards')
                    ->where('user_id', $user->id)
                    ->update(['user_name' => $user->name]);
            }
        });
    }

    /**
     * Get the events created by the user.
     */
    public function eventsCreated(): HasMany
    {
        return $this->hasMany(Event::class, 'creator_id');
    }

    /**
     * Get the predictions made by the user.
     */
    public function predictions()
    {
        return $this->hasMany(Prediction::class);
    }

    public function predictionsByContest()
    {
        return $this->predictions()->orderByDesc('points')->get()->groupBy('contest_id');
    }

    /**
     * Get the existing events in which the user has predicted.
     */
    public function eventsPredicted()
    {
        return Event::findMany($this->predictions()->get()->pluck('event_id'));
    }

    /**
     * Get the number of events the user has predicted: Ongoing or ended, even if deleted (post-finish).
     */
    public function totalNumEventsPredicted(): int
    {
        return $this->eventsPredicted()->where('has_winners', false)->count() + $this->leaderboardEntries()->get()->count();
    }

    /**
     * Get the prediction leaderboards the user appears in.
     */
    public function leaderboardEntries(): HasMany
    {
        return $this->hasMany(LeaderboardEntry::class)->orderByDesc('score');
    }

    /**
     * Get the number of times the user has placed in the podium when predicting events.
     */
    public function podiums(): array
    {
        $positionCount = [0, 0, 0];
        $userEntries = $this->leaderboardEntries()->get();
        $eventEntries = LeaderboardEntry::whereIn('event_id', array_map(function ($entry) {
            return $entry['event_id'];
        }, $userEntries->toArray()))->get();
        foreach ($userEntries as $entry) {
            $top3 = array_values(array_map(function ($entry) {
                return $entry['score'];
            },
                $eventEntries->where('event_id', $entry->event_id)->sortByDesc('score')->take(3)->toArray()));
            if ($eventEntries->where('event_id', $entry->event_id)->count() <= 3 || in_array($entry->score, $top3) !== false) {
                $idx = array_search($entry->score, $top3);
                $positionCount[$idx]++;
            }
        }

        return $positionCount;
    }

    public function podiumCount(): int
    {
        return array_sum($this->podiums());
    }

    public function winRate(): float
    {
        return round(($this->podiums()[0] / max($this->leaderboardEntries()->get()->count(), 1)) * 100, 2);
    }
}
