<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contest extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description', 'options', 'result'];

    protected static function boot()
    {
        parent::boot();

        static::updated(function ($contest) {
            if ($contest->isDirty('name')) {
                \DB::table('predictions')
                    ->where('contest_id', $contest->id)
                    ->update(['contest_name' => $contest->name]);
            }
        });
    }

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
