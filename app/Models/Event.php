<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'url', 'description', 'creator_id', 'start_time'];

    /**
     * Automatically generate a url when creating an event.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($event) {
            if (empty($event->url)) {
                $event->url = md5($event->name.$event->creator_id);
            }
        });
    }

    public function getRouteKeyName()
    {
        return 'url';
    }

    /**
     * Get the contests for the event.
     */
    public function contests()
    {
        return $this->hasMany(Contest::class);
    }

    /**
     * Get the creator of the event.
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'creator_id');
    }
}
